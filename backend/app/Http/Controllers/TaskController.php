<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Requests\TaskRequest;
use Illuminate\Http\Request;
use App\Models\Tasktype;
use App\Models\Task;
use App\Models\Group;
use App\Models\Attachement;
use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{

    // todo : let user decide the number of tasks to display in one page
    public const PAGINATION_LIMIT = 30;

    /**
     * List of tasks.
     *
     * @param Request $request
     * @param Group $group 
     * @return \Illuminate\Http\Response
     */
    public function index(Group $group)
    {
        $userid =  auth()->user()->id;
        $tasks = $group->tasks()->orderBy('due_at', 'asc')
                                ->where(function($query) use($userid) {
                                    $query->where('isPrivate', '=', 0)
                                    ->orWhere('isPrivate', '=', 1)->where('user_id', '=', $userid);
                                })->whereDate('due_at', '>=', Carbon::now())->paginate(TaskController::PAGINATION_LIMIT);
                                
        $projects = $group->tasks()->orderBy('due_at', 'asc')
                        ->where('tasktype_id', '=', TaskType::PROJECT)
                        ->where(function($query) use($userid) {
                            $query->where('isPrivate', '=', 0)
                            ->orWhere('isPrivate', '=', 1)->where('user_id', '=', $userid);
                        })->whereDate('due_at', '>=', Carbon::now())->get()->paginate(TaskController::PAGINATION_LIMIT);

        return response()->json([
            'tasks' => $tasks,
            'projects' => $projects,
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
        $task = new Task();
        $task->user_id = auth()->user()->id;
        $updatedInstance = $this->persistData($request, $group, $task);
        return response()->json($updatedInstance);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function comment(CommentRequest $request, Group $group, Task $task)
    {   
        $comment = Comment::create($request->validated());

        $comment->user_id = $this->user->id;
        $comment->save();

        return response()->json($comment);
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
        if ($this->user->id==$comment->user->id) {
            $comment->delete();
            return response()->json(__('api.comments.delete'));
        }

        return response()->json(__('api.comments.delete_failed'), 400);
    }

    /**
     * Display the specified resource.
     *
     * @param Group $group
     * @param Task $task
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group, Task $task)
    {
        return response()->json($task);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group, Task $task)
    {
        return response()->json( ['group' => $group,
                                'types' => TaskType::TYPES_KEY,
                                'subjects' => $group->subjects,
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
    public function update(TaskRequest $request, Group $group, Task $task)
    {
        $updatedInstance = $this->persistData($request, $group, $task);
        return response()->json($updatedInstance);
    }

    /**
     * Persist data to db the specified resource in storage.
     *
     * @param Request $request
     * @param Group $group
     * @param Task $task
     * @return \Illuminate\Http\Response
     */
    private function persistData(TaskRequest $request, Group $group, Task $task): Task
    {
        $task->fill($request->validated());
        $task->save();
        
        return $task;
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
            return response()->json(__('api.tasks.deleted'));
        }

        return response()->json(__('api.tasks.not_permitted'), 403);
    }
}
