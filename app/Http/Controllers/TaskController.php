<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskType;
use App\Models\Subject;
use App\Models\Task;
use App\Models\Group;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($group)
    {
        return view('group.task.create', ['group' => $group,
                                          'types' => TaskType::all(),
                                          'subjects' => Subject::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $group)
    {
        $rules = [
            'title' => 'required|unique:tasks|max:255',
            'subject_id' => 'required|integer',
            'tasktype_id' => 'required|integer',
            'due_at' => 'required|date|after_or_equal:today',
            'description' => 'required',
            'isPrivate' => 'boolean'
        ];
        $validator = Validator::make($request->all(), $rules);
        // process the login
        if ($validator->fails()) {
            return redirect()
                    ->route('groups.tasks.create', ['group' => $group])
                    ->withErrors($validator)
                    ->withInput();
        } else {
            // store
            $task = new Task();
            $task->title           = $request->get('title');
            $task->subject_id      = $request->get('subject_id');
            $task->tasktype_id     = $request->get('tasktype_id');
            $task->due_at          = $request->get('due_at');
            $task->description     = $request->get('description');
            $task->isPrivate       = $request->get('isPrivate');
            $task->save();

            // redirect
            $request->session()->flash('status', 'Task was created successfully!');
            return redirect()
                    ->route('groups.tasks.show', ['group' => $group,
                                                 'task' => $task->id]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group, Task $task)
    {
        return view('group.task.show', ['group' => $group,
                                        'task' => $task
        ]);
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
}
