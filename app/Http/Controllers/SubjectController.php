<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Task;
use App\Models\Group;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    /**
     * Show subjects of a group.
     *
     * @return \Illuminate\View\View
     */
    public function index(Group $group)
    {
        $subjects = $group->subjects()->with('tasks')->get();
        return view('group.subject.index', ['group' => $group,
                                            'subjects' => $subjects]);
    }
}
