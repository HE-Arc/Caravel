<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Task;
use App\Models\Group;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\MessageBag;

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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Group $group)
    {
        $subject = new Subject();
        $subject->group_id = $group->id;
        return $this->persistData($request, $group, $subject);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Group $group
     * @param Subject $subject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group, Subject $subject)
    {
        return $this->persistData($request, $group, $subject);
    }

        /**
     * Persist data to db the specified resource in storage.
     *
     * @param Request $request
     * @param Group $group
     * @param Subject $subject
     * @return \Illuminate\Http\Response
     */
    private function persistData(Request $request, Group $group, Subject $subject)
    {
        $rules = [
            'name' => [
                'required',
                'max:255',
                Rule::unique('subjects')->Where('group_id', $group->id)
            ],
            'color' => 'required|min:1|max:10'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        } else {
            // store
            $subject->name      = $request->get('name');
            $subject->color     = $request->get('color');
            $subject->save();

            // redirect
            $request->session()->flash('status', 'Action was made successfully!');
            return redirect()
                    ->route('groups.subjects.index', ['group' => $group->id]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Group $group
     * @param Subject $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Group $group, Subject $subject)
    {
        if (count($subject->tasks) > 0) {
            $errors = new MessageBag();
            $errors->add('errors', 'This subject cannot be delete, there task linked on it !');
            $request->session()->flash('errors', $errors);
        } else {
            $subject->delete();
            $request->session()->flash('status', 'this subject has been delete successfully');
        }

        return redirect()->route('groups.subjects.index', [$group->id]);
    }
}
