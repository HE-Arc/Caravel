<?php

namespace App\Console\Tasks;

use App\Models\Group;
use App\Models\Tasktype;
use Carbon\CarbonImmutable;
use Illuminate\Support\Carbon;
use App\Models\GroupStat;
use Ramsey\Collection\Set;

/**
 * This class is made to be launch every saturday
 * Calculation of a week load
 */
class CalculateWorkLoad
{
    public function __invoke()
    {
        $now = CarbonImmutable::now();
        $start = $now->startOfWeek()->format("Y-m-d");
        $end = $now->endOfWeek()->format("Y-m-d");

        $groups = Group::all();

        foreach ($groups as $group) {
            $sum = 0;
            $tasks = $group->tasks()->whereBetween("due_at", [$start, $end])->get();
            $projects = $group->tasks()->where("tasktype_id", Tasktype::PROJECT)->whereDate("start_at", "<", $start)->whereDate("due_at", ">", $end)->get();
            $subjects = [];
            $div = 0;

            foreach ($tasks as $task) {
                $coef = $task->tasktype_id == Tasktype::PROJECT ? 1.5 : 1;
                $sum += $task->subject->ects * $coef;
                array_push($subjects, $task->subject);
            }

            foreach ($projects as $project) {
                $sum += $project->subject->ects;
                array_push($subjects, $project->subject);
            }

            $subjects = array_unique($subjects);

            $div = array_reduce($subjects, function ($carry, $subject) {
                $carry += $subject->ects;
                return $carry;
            }, 0);


            if ($sum > 0) {
                $stat = new GroupStat();
                $stat->group_id = $group->id;
                $stat->wes = intval(($sum * 10) / $div);
                $stat->save();
            }
        }
    }
}
