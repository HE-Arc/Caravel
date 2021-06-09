<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileUploadRequest;
use App\Http\Requests\GroupRequest;
use App\Http\Requests\GroupEditRequest;
use App\Http\Requests\MemberGroupRequest;
use App\Http\Requests\MemberStatusRequest;
use App\Models\Group;
use App\Models\User;
use App\Services\UploadFileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GroupController extends Controller
{

    public const PAGINATION_LIMIT = 15;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('text')) {
            $text = $request->input('text') ?? "";
            return $this->filtered($text);
        }
        $groups = $this->user->groups()->state(Group::ACCEPTED)->with('user')->get();
        return response()->json($groups);
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
        $data = $request->validated();

        $file = $data['file'];

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
    public function update(GroupEditRequest $request, Group $group, UploadFileService $fileService)
    {
        $data = $request->validated();

        if ($request->hasfile('picture')) {
            $group->picture = $fileService->uploadFileToFolder($group->getStorageFolder(), $request->file('picture'));
        }

        if ($request->has("user_id")) {
            $this->changeLeader($group, $data['user_id']);
        }

        $group->fill($data);
        $group->save();

        return response()->json($group);
    }

    /**
     * Delete the given member from the group
     */
    public function removeMember(MemberGroupRequest $request, Group $group)
    {
        $data = $request->validated();

        //verify : only the group leader can kick someone
        if (Auth::id() != $group->user_id) {
            return response()->json(["message" => __('api.groups.admin_operation')], 403);
        }

        if ($data['user_id'] == $group->user_id)
            return response()->json(['message' => __('api.groups.resource_restricted')], 403);

        $group->users()->detach($data['user_id']);

        return response()->json(['message' => __('api.groups.member_updated')]);
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
        if (!$this->user->can('delete', $group)) {
            return response()->json(["message" => __('api.groups.admin_operation')], 403);
        }

        $group->delete();

        return ["message" => __('api.groups.delete')];
    }

    /**
     * Change status of user in a group
     * status can be found under Group::REQUESTSTATUS
     */
    public function updateMemberStatus(MemberStatusRequest $request, Group $group)
    {
        $data = $request->validated();

        if ($group->author == $data['user_id'])
            return response()->json(['message' => __('api.groups.resource_restricted')], 403);

        $group->users()->updateExistingPivot($data['user_id'], array('isApprouved' => $data['status_id']), true);

        return response()->json(['message' => __('api.groups.member_updated')]);
    }

    /**
     * Change the leader of the group
     */
    public function changeLeader(Group $group, User $user)
    {
        //verify that the user is already in the group
        if ($group->users->find($user->id)) {
            $group->user_id = $user->id;
        }
    }

    public function join(Group $group)
    {
        $userId = Auth::id();
        //verification of non existence (a refused/accepter/pending user can not ask again to join a group)
        if ($group->users()->find($userId) == null) {
            $group->users()->attach($userId, ['isApprouved' => Group::PENDING]);
            return response()->json(["message" => TRUE]);
        }

        return response()->json(['message' => __('api.global.operation_failed')], 400);
    }

    /**
     * @returns JSON containing groups
     */
    public function filtered(String $str)
    {
        //fetch current user
        $userId = Auth::id();

        //get all groups corresponding to the requested string (regex) excluding the one already containing the user
        $groups = (!empty($str)) ? Group::getFilteredGroupsForUser($userId, $str)->paginate(GroupController::PAGINATION_LIMIT) : [];

        return response()->json($groups);
    }

    /**
     * Retrieve uploaded file
     *
     * @param int $group 
     * @return \Illuminate\Http\Response
     */
    public function getFile(Group $group, $file)
    {
        return response()->file(Storage::path($group->getStorageFolder() . "/" . $file));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        return response()->json($group);
    }
}
