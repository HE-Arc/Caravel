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
        return view('group.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //the "index" livesearch is the view used to create a new group
        return view('group.index');
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
            $group->users()->attach($userID, ['isApprouved' => true]);
        }

        //return the user to the "show" of this created group
        return redirect()->route('groups.edit', $group->id);
    }

    //this name is way too long, but "buildFilenameFromPicture" 
    private function FileNameAndSave($picture){
        //the filename is the hasName of this picture inside the public folder for pictures (defined in the config)
        $filename = config('caravel.groups.pictureFolder').$picture->hashName();
        $filenameSystem = public_path($filename);
        Image::make($picture)->resize(250,250)->save($filenameSystem);
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
            $folder = config('smartmd.files.root') . '/groups\/' . $group;
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
        $folder = config('smartmd.files.root') . '/groups\/' . $group . '/';
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
        return view('group.settings', ["group" => $group, 'users' => $group->usersApproved]);
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
                if(isset($group->picture) && File::exists(public_path($group->picture))){
                    File::delete(public_path($group->picture));
                }
                $filenamePicture = $this->FileNameAndSave($request->file('picture'));
                $group->picture = $filenamePicture;
            }
            //if user is authenticated, put him as the group's leader
            $group->save();
        }

        //return the user to the "show" of this created group
        return redirect()->back();
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
                $q->where("user_id", "<>", $userID)
                    ->orWhere("user_id", $userID)->where('isApprouved', null)
                    ->orWhere("user_id", $userID)->where('isApprouved', false);
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
                "requested" => $group->usersRequesting()->find($userID) != null
            ];
        }
        return response()->json([
            "valid" => $valid,
            "groups"  => $groupsData,
        ]);
    }
}
