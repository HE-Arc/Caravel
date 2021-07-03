<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Requests\ReactionRequest;
use App\Http\Requests\TaskFinishRequest;
use App\Http\Requests\TaskRequest;
use Illuminate\Http\Request;
use App\Models\Tasktype;
use App\Models\Task;
use App\Models\Reaction;
use App\Models\Group;
use App\Models\Comment;
use App\Http\Search\SearchEngine;

class TaskController extends Controller
{
    /**
     * List of tasks.
     *
     * @param Request $request
     * @param Group $group 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Group $group)
    {
        $filters = $request->all();
        //$filters['base'] = $group->id;

        $query = SearchEngine::applyFilters($group->tasks()->getQuery(), $filters, "Task");

        $tasks = $query->get();

        return $tasks;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskRequest $request, Group $group)
    {
        $max = $group->tasks->max('task_group_id');
        $task = new Task();
        $task->user_id = auth()->user()->id;
        $task->task_group_id = $max + 1;
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
        if ($this->user->id == $comment->user->id) {
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
        return response()->json($task->load('questions.comments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group, Task $task)
    {
        return response()->json([
            'group' => $group,
            'types' => TaskType::TYPES_KEY,
            'subjects' => $group->subjects,
            'task' => $task
        ]);
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
     * Remove the specified task.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group, Task $task)
    {
        if ($this->user->id == $task->user_id) {
            $task->delete();
            return response()->json(__('api.tasks.deleted'));
        }

        return response()->json(__('api.tasks.not_permitted'), 403);
    }

    /**
     * Update on/off on reaction
     */
    public function updateReaction(ReactionRequest $request, Group $group)
    {
        $data = $request->validated();
        $type = intval($data['type']);
        $reaction = Reaction::where('task_id', $data['task_id'])->where('type', $type)->where('user_id', $this->user->id)->first();

        if ($reaction) {
            $reaction->delete();
        } else {
            $reaction = (new Reaction())->fill($data);
            $reaction->user_id = $this->user->id;
            $reaction->save();
        }

        $task = Task::with('reactions')->find($data['task_id']);

        return $task;
    }

    public function setFinished(TaskFinishRequest $request, Group $group)
    {
        /** @var Task */
        $task = Task::find($request->task_id);
        if (!empty($task)) $task->updateFinish($this->user->id, $request->hasFinished);

        return $task;
    }
}
