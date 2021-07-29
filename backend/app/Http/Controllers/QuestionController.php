<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Models\Group;
use App\Models\Question;

/**
 * This class is used to manage questions
 */
class QuestionController extends Controller
{

    /**
     * This function manage newly created question
     * @param   QuestionRequest $request
     * @param   Group   $group
     * @return  \Illuminate\Http\Response
     */
    public function store(QuestionRequest $request, Group $group)
    {
        $max = Question::where('task_id', $request->task_id)->max('question_task_id');
        $question = new Question();
        $question->question_task_id = $max + 1;
        $question->user_id = Auth()->id();
        return $this->persistData($request, $group, $question);
    }

    /**
     * This function manage to update question
     * @param   QuestionRequest $request
     * @param   Group   $group
     * @return  \Illuminate\Http\Response
     */
    public function update(QuestionRequest $request, Group $group, Question $question)
    {
        if ($this->user->id == $question->user_id) {
            return $this->persistData($request, $group, $question);
        }
        return response()->json(__('api.global.access_denied'), 403);
    }

    /**
     * This upsert function allow to update or create a question
     * @param   QuestionRequest $request
     * @param   Group   $group
     * @return  \Illuminate\Http\Response
     */
    protected function persistData(QuestionRequest $request, Group $group, Question $question)
    {
        $question->fill($request->validated());
        $question->save();
        $question->load('comments', 'solvedBy');
        return $question;
    }

    /**
     * Remove the specified question.
     *
     * @param   Group   $group
     * @param   Question    $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group, Question $question)
    {
        if ($this->user->id == $question->user_id) {
            $question->delete();
            return response()->json(__('api.questions.deleted'));
        }

        return response()->json(__('api.global.access_denied'), 403);
    }
}
