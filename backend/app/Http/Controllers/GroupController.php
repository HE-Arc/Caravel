<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileUploadRequest;
use App\Http\Requests\GroupRequest;
use App\Models\Group;
use App\Models\User;
use App\Services\UploadFileService;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = $this->user->groups()->state(Group::ACCEPTED)->with('user')->get();
        return ["groups" => $groups];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GroupRequest $request)
    {
        $group = Group::create($request->validated());
        $group->user_id = Auth::id();
        $group->save();

        //attach the main user to this group - automatically approved
        $group->users()->attach(Auth::id(), ['isApprouved' => Group::ACCEPTED]);

        //return the user to the "show" of this created group
        return response()->json($group);
    }

    /**
     * Manage upload file to a group
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $group 
     * @return \Illuminate\Http\Response
     */
    public function upload(FileUploadRequest $request, Group $group, UploadFileService $fileService)
    {
        $file = $request->file('image');

        $filepath = $fileService->uploadFileToFolder($group->getStorageFolder(), $file);
        
        return response()->json(
            [
                'path' => route('groups.files', ['group' => $group, 'file' => $filepath]),
                'message' => 'File uploaded'
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GroupRequest $request, Group $group, UploadFileService $fileService)
    {
        $data = $request->validated();

        if ($request->hasfile('picture')){
            $group->picture = $fileService->uploadFileToFolder($group->getStorageFolder(), $request->file('picture'));
        }

        if ($request->has('user_id')) {
            $this->conca
        }

        $group->fill($data);
        $group->save();

        return response()->json($group);
    }

    /**
     * Delete the given member from the group
     */
    public function kickMember(Group $group, User $user){
        //verify : only the group leader can kick someone
        if(Auth::id() != $group->user_id){
            abort(403);
        }

        $this->deleteMember($group, $user->id);
        return redirect()->route('groups.members', $group);
    }

    /**
     * Quit a group (delete the own user from the group)
     */
    public function quitGroup(Group $group){
        $this->deleteMember($group, Auth::id());
        return redirect()->route('groups.index');
    }

    /**
     * Factorisation of the deletion of a member from a group
     * Check if the group is empty or without leader
     */
    private function deleteMember($group, $userId){
        //verify : User must be in the group
        if(!$group->users->find($userId)){
            return;
        }

        $group->usersApproved()->detach($userId);
        //if there are no members anymore, delete the group
        if($group->usersApproved()->count() == 0){
            $this->deleteGroup($group);
        } else if($userId == $group->user_id){
            //give leadership of the group to the older user
            $group->user_id = $group->usersApproved()->orderBy('pivot_updated_at','asc')->first()->id;
            $group->save();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        //verify : User must be the leader of the group
        if(Auth::id() != $group->user_id){
            abort(403);
        }

        $group->delete();

        return ["message" => "group has been deleted"];
    }

    /**
     * Accept or refuse a pending user
     */
    public function processPending(Group $group, User $user, $status){
        //verify : User has a pending request
        if($group->usersRequesting->find($user->id)){
            $processedStatus = $status ? Group::ACCEPTED : Group::REFUSED;
            //true in update means the updated_at is written with now(), usefull to know when the user was accepted/refused
            $group->usersRequesting()->updateExistingPivot($user, array('isApprouved' => $processedStatus), true);
        }
        return redirect()->back();
    }

    /**
     * Allow a user that was kicked back into the "pending" category ('un-ban')
     */
    public function allowBack(Group $group, User $user){
        //verify existence
        if($group->usersRefused->find($user->id)){
            //back into "pending" mode
            $group->usersRefused()->updateExistingPivot($user, array('isApprouved' => Group::PENDING), true);
        }
        return redirect()->back();
    }

    /**
     * Change the leader of the group
     */
    public function changeLeader(Group $group, User $user){
        //verify that the user is already in the group
        if($group->users->find($user->id)){
            $group->user_id = $user->id;
        }
    }

    public function join(Group $group){
        $userID = Auth::id();
        //verification of non existence (a refused/accepter/pending user can not ask again to join a group)
        if($group->users()->find($userID) == null){
            $group->users()->attach($userID, ['isApprouved' => Group::PENDING]);
            return response()->json(["done" => TRUE]);
        }
    }

    /**
     * @returns JSON containing groups
     */
    public function filtered(String $str){
        //fetch current user
        $userID = Auth::id();

        //get all groups corresponding to the requested string (regex) excluding the one already containing the user
        $groups = Group::where('name', 'LIKE', "%$str%")
            ->orderBy('created_at') //TODO : Add a good 'order by' relative to group activity maybe ?
            ->take(10);

        //loop over groups, builds result array
        $valid = !empty($str);
        $groupsData = array();
        foreach($groups->get() as $group){
            if(strcasecmp($group->name,$str) == 0){
                $valid = false;
            }

            //does this group have this user as requesting ?
            $user = $group->usersWithSubscription()->find($userID);
            $hasUserRequestedGroup = ($user!= null);
            //if so, get the code
            $status = $hasUserRequestedGroup ? $user->pivot->isApprouved : null;

            //stock the data in array for JSON
            $groupsData[] = [
                "id" => $group->id, 
                "name" => $group->name,
                "request" => [
                    "requesting" => $hasUserRequestedGroup,
                    "status" => $status,
                ]
            ];
        }
        return response()->json([
            "valid" => $valid,
            "groups"  => $groupsData,
        ]);
    }
}
