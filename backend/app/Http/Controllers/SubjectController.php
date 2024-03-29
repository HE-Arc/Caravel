<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubjectRequest;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Group;

/**
 * This class manage subjects
 */
class SubjectController extends Controller
{
    /**
     * Show subjects of a group.
     *
     * @param   Group   $group
     * @return \Illuminate\View\View
     */
    public function index(Group $group)
    {
        $subjects = $group->subjects()->with('tasks')->get();
        return response()->json($subjects);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param   SubjectRequest  $request
     * @param   Group   $group
     * @return  \Illuminate\Http\Response
     */
    public function store(SubjectRequest $request, Group $group)
    {
        $subject = new Subject();
        $subject->group_id = $group->id;

        $updatedInstance = $this->persistData($request, $subject);
        return response()->json($updatedInstance);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param   SubjectRequest  $request
     * @param   Group   $group
     * @param   Subject $subject
     * @return \Illuminate\Http\Response
     */
    public function update(SubjectRequest $request, Group $group, Subject $subject)
    {
        $updatedInstance = $this->persistData($request, $subject);
        return response()->json($updatedInstance);
    }

    /**
     * Persist data to db the specified resource in storage.
     *
     * @param SubjectRequest $request
     * @param Group $group
     * @param Subject $subject
     * @return \Illuminate\Http\Response
     */
    private function persistData(SubjectRequest $request, Subject $subject)
    {
        $subject->fill($request->validated());
        $subject->save();

        return $subject;
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
            return response()->json(__('api.subjects.linked'), 409);
        } else {
            $subject->delete();
            return response()->json(__('api.subjects.delete_success'));
        }
    }
}
