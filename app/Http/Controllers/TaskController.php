<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskType;
use App\Models\Subject;
use App\Models\Task;
use App\Models\Group;
use App\Models\Attachement;
use App\Models\Comment;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

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
    public function create(Group $group)
    {
        return view('group.task.createOrUpdate', ['group' => $group,
                                          'types' => TaskType::all(),
                                          'subjects' => Subject::all(),
                                          'task' => new Task(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Group $group)
    {
        return $this->persistData($request, $group, new Task());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function comment(Request $request, Group $group, Task $task)
    {
        $rules = [
            'message' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()
                    ->route('groups.tasks.show', [$group->id, $task->id])
                    ->withErrors($validator)
                    ->withInput();
        } else {
            $comment = new Comment();
            $comment->user_id = auth()->user()->id;
            $comment->task_id = $task->id;
            $comment->message = $request->get('message');
            $comment->save();
        }

        return redirect()->route('groups.tasks.show', [$group->id, $task->id]);
    }

      /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Group $group
     * @param Task $task
     * @param Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function delComment(Request $request, Group $group, Task $task, Comment $comment)
    {
        if (auth()->user()->id==$comment->user->id) {
            $comment->delete();
            $request->session()->flash('status', 'Comment has been delete successfully!');
        }

        return redirect()->route('groups.tasks.show', [$group->id, $task->id]);
    }

   /**
     * Delete file linked to a task
     *
     * @param Request $request
     * @param Group $group
     * @param Task $task
     * @param Attachement $file
     * @return \Illuminate\Http\Response
     */
    public function delAttachement(Request $request, Group $group, Task $task, Attachement $file)
    {
        if (auth()->user()->id==$file->user->id) {
            $file->delete();
            return response()->json([
                'status' => 'ok',
                'message' => 'File has been delete successfully!',
            ]);
        } else {
            return response()->json([
                'status' => 'nok',
                'message' => 'File has not been delete !',
            ]);
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
    public function edit(Group $group, Task $task)
    {
        return view('group.task.createOrUpdate', ['group' => $group,
                                                'types' => TaskType::all(),
                                                'subjects' => Subject::all(),
                                                'task' => $task]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Group $group
     * @param Task $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group, Task $task)
    {
        $request['isPrivate'] = $task->isPrivate ? 'on' : 'off';
        return $this->persistData($request, $group, $task);
    }

    /**
     * Persist data to db the specified resource in storage.
     *
     * @param Request $request
     * @param Group $group
     * @param Task $task
     * @return \Illuminate\Http\Response
     */
    private function persistData(Request $request, Group $group, Task $task)
    {
        $rules = [
            'title' => 'required|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'tasktype_id' => 'required|exists:tasktypes,id',
            'due_at' => 'required|date|after_or_equal:today',
            'description' => 'required',
            'filenames' => 'file|max:13312',
        ];
        $validator = Validator::make($request->all(), $rules);

        
        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        } else {
            // store
            $task->title           = $request->get('title');
            $task->subject_id      = $request->get('subject_id');
            $task->tasktype_id     = $request->get('tasktype_id');
            $task->due_at          = $request->get('due_at');
            $task->user_id         = auth()->user()->id;
            $task->description     = $request->get('description');
            $task->isPrivate       = $request->get('isPrivate')=='on';
            $task->save();

            //process attachement
            if($request->hasfile('attachement'))
            {
                $folder = config('smartmd.files.root') . '/groups\/' . $group->id;
                foreach($request->file('attachement') as $upload)
                {
                    $file = new Attachement();
                    $file->path = $upload->hashName();
                    $file->name =  $upload->getClientOriginalName();
                    $file->mimeType = $upload->getMimeType();
                    $file->task_id = $task->id;
                    $file->user_id = auth()->user()->id;
                    $file->save();
                    Storage::put($folder, $upload);
                }
            }

            // redirect
            $request->session()->flash('status', 'Action was made successfully!');
            return redirect()
                    ->route('groups.tasks.show', ['group' => $group->id,
                                                 'task' => $task->id]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Group $group, Task $task)
    {
        if (auth()->user()->id==$task->user->id) {
            $task->delete();
            $request->session()->flash('status', 'Task has been delete successfully!');
        }

        return redirect()->route('groups.show', [$group->id]);
    }
}
