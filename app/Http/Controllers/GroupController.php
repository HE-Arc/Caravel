<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\User;
use File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = Auth::user()->groupsAvailable;
        return view('group.index', ["groups" => $groups]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {        
        //the "index" livesearch is the view used to create a new group
        return view('group.search');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $group = new Group();

        $rules = [
            'name' => 'required|unique:groups|max:150',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        } else {
            $group->name = $request->get('name');
            //if user is authenticated, put him as the group's leader
            $userID = Auth::id();
            $group->user_id = $userID;
            $group->save();
            //attach the main user to this group - automatically approved
            $group->users()->attach($userID, ['isApprouved' => Group::ACCEPTED]);
        }

        //return the user to the "show" of this created group
        return redirect()->route('groups.edit', $group);
    }

    //this name is way too long, but "buildFilenameFromPicture" 
    private function FileNameAndSave($picture,$quality=90){
        //the filename is the hasName of this picture inside the public folder for pictures (defined in the config)
        $filename = config('caravel.groups.pictureFolder').$picture->hashName();
        $filenameSystem = public_path($filename);
        Image::make($picture)
            ->fit(250, 250)
            ->save($filenameSystem);
        return $filename;
    }

    /**
     * upload.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $group 
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request, $group)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|max:4096'
        ]);
        if ($validator->passes()) {
            $folder = config('smartmd.files.root') . '/groups/' . $group;
            $temp = $request->file('image');
            $fileName = '[' . $temp->getClientOriginalName() . ']';
            $name = $temp->hashName();
            if (substr($temp->getMimeType(), 0, 5) == 'image') {
                $fileName = '!' . $fileName;
                $im = Image::make($temp->getPathname());
                $width = $im->width();
                $height = $im->height();
                if ($width > 1200) {
                    $scale = 1200 / $width;
                    $width = ceil($width * $scale);
                    $height = ceil($height * $scale);
                    $im->resize($width, $height);
                    $temp = (string) $im->encode();
                    $folder .= '/'.$name;
                }
            } 

            Storage::put($folder, $temp);
            
            return response()->json(
                [
                    'path' => route('groups.files', ['group' => $group, 'file' => $name]),
                    'name' => $fileName,
                    'message' => 'File uploaded'
                ]
            );
        }
        return response()->json(['message' => $validator->errors()->first()],400);
    }

        /**
     * upload.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $group 
     * @return \Illuminate\Http\Response
     */
    public function getFile(Request $request, $group, $file) {
        $folder = config('smartmd.files.root') . '/groups/' . $group . '/';
        return response()->file(Storage::path($folder . $file));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('groups.tasks.index', $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        return view('group.settings', [
            'group' => $group, 
            'isLeader' => $group->user_id == Auth::id(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        $rules = [
            'name' => "required|unique:groups,name,{$group->id}|max:150",
            'description' => 'max:500',
            'picture' => 'image|max:4096'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        } else {
            $group->name = $request->get('name');
            $group->description = $request->get('description');
            //if the picture is submitted, do the treatment
            if($request->hasfile('picture')){
                $this->deleteIfPicture($group);
                if($request->file('picture')->getSize()>2048000){   //images > 2MB will be blurred 
                    $filenamePicture = $this->FileNameAndSave($request->file('picture'),75);
                } else{
                    $filenamePicture = $this->FileNameAndSave($request->file('picture'));
                }
                $group->picture = $filenamePicture;
            }
            //if user is authenticated, put him as the group's leader
            $group->save();
        }

        //return the user to the "show" of this created group
        return redirect()->back();
    }

    /**
     * Delete the given member from the group
     */
    public function kickMember(Group $group, User $user){
        //verify : only the group leader can kick someone
        if($user != $group->user_id){
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
        $this->deleteGroup($group);
        return redirect()->route('groups.index');
    }

    /**
     * Delete the group, including its picture and its eventual storage datas
     */
    private function deleteGroup($group){
        $this->deleteIfPicture($group);
        $this->deleteIfStorage($group);
        $group->delete();
    }

    /**
     * Delete the data (ex : files from comment) of the group in storage
     */
    private function deleteIfStorage($group){
        $folder = config('smartmd.files.root') . '/groups\/' . $group->id;
        //if the group has a storage, delete the entire directory (files)
        if(Storage::exists($folder)){
            Storage::deleteDirectory($folder);
        }
    }

    /**
     * Safely delete the picture of the group if it exists
     */
    private function deleteIfPicture($group){
        //verify existence both in group and in file before deleting
        if(isset($group->picture) && File::exists(public_path($group->picture))){ 
            File::delete(public_path($group->picture));
        }
    }

    /**
     * Return the view of the pending /refused users
     */
    public function pending(Group $group){
        return view('group.pending', [
            'group' => $group, 
            'pending' => $group->usersRequesting()->orderBy('pivot_updated_at','asc')->get(), 
            'refused' => $group->usersRefused()->orderBy('pivot_updated_at','asc')->get(),
            'isLeader' => $group->user_id == Auth::id(),
            ]);
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
     * Return the view of the members of the group
     */
    public function members(Group $group){
        return view('group.members', [
            'group' => $group, 
            'leaderID' => $group->user_id,
            'isLeader' => $group->user_id == Auth::id(),
            'users' => $group->usersApproved()->orderBy('pivot_updated_at','asc')->get(),
            ]);
    }

    /**
     * Change the leader of the group
     */
    public function changeLeader(Group $group, User $user){
        //verify that the user is already in the group
        if($group->users->find($user->id)){
            $group->user_id = $user->id;
            $group->save();
            return redirect()->back();
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
