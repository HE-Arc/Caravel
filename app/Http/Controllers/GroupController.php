<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

        $errors = "no";
        if ($validator->fails()) {
            $errors = "bad name";
        } else {
            $name = $request->get('name');
            if(!empty($name)){
                $group->name = $name;
            }
        }

        return view('group.create', ["group" => $group, "errors" => $errors]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return redirect()->back()->withErrors($validator)->withInput();
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
    public function edit($id)
    {
        //
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
        //
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
