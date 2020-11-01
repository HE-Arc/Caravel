<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('group.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $group = new Group();

        $rules = [
            'name' => 'unique:groups|max:150',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()
            ->back()
            ->withErrors($validator)
            ->withInput();
        } else {
            $name = $request->get('name');
            if(!empty($name)){
                $group->name = $name;
            }
        }
        $group->save();

        return view('group.settings', ['group' => $group]);
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
            //$group->picture = $request->get('picture');
            //https://stackoverflow.com/questions/50086004/issue-updating-user-account-with-avatar-laravel TODO
            //if the picture is submitted, do the treatment
            if($request->hasfile('picture')){
                $filenamePicture = $this->FileNameAndSave($request->file('picture'));
                $group->picture = $filenamePicture;
            }
            //if user is authenticated, put him as the group's leader
            $userID = Auth::id();
            if($userID!=null){
                $group->user_id = $userID;
            }
            $group->save();
        }

        //return the user to the "show" of this created group
        return redirect()->route('groups.show', $group->id);
    }

    //this name is way too long, but "buildFilenameFromPicture" 
    private function FileNameAndSave($picture){
        //the filename is the hasName of this picture inside the public folder for pictures (defined in the config)
        $filename = public_path(config('caravel.groups.pictureFolder')) . "/" . $picture->hashName();
        Image::make($picture)->resize(250,250)->save($filename);
        return $filename;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        return view('group.settings', ["group" => $group, 'users' => $group->users()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @returns JSON containing groups
     */
    public function filtered(String $str){
        //fetch current user
        $userID = Auth::id();

        //get all groups corresponding to the requested string (regex) excluding the one already containing the user
        $groups = Group::where('name', 'LIKE', "%$str%")
            ->whereDoesntHave('users')
            ->orWhere('name', 'LIKE', "%$str%")
            ->WhereHas('users', function($q) use ($userID) {
                $q->where("user_id", "<>", $userID)->orWhere("user_id", $userID)->where('isApprouved', FALSE);
            })
            ->orderBy('created_at') //TODO : Add a good order by relative to group activity, DONT FORGET N+1 problem
            ->take(10);

        //loop over groups, builds result array
        $valid = !empty($str);
        $groupsData = array();
        foreach($groups->get() as $group){

            if(strcasecmp($group->name,$str) == 0){
                $valid = false;
            }

            //if user is in groups->users(), then it has already requested
            $groupsData[] = [
                "id" => $group->id, 
                "name" => $group->name,
                "requested" => $group->users()->find($userID) != null
            ];
        }
        return response()->json([
            "valid" => $valid,
            "groups"  => $groupsData,
        ]);
    }
}
