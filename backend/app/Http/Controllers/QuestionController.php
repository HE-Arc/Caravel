<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Models\Group;
use App\Models\Question;

class QuestionController extends Controller
{

    public function store(QuestionRequest $request, Group $group)
    {
        $question = new Question();
        $question->user_id = Auth()->id();
        return $this->persistData($request, $group, $question);
    }

    public function update(QuestionRequest $request, Group $group, Question $question)
    {
        return $this->persistData($request, $group, $question);
    }

    protected function persistData(QuestionRequest $request, Group $group, Question $question)
    {
        $question->fill($request->validated());
        $question->save();
        return $question;
    }
}
