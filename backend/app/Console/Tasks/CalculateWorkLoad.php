<?php

namespace App\Console\Tasks;

use App\Models\Group;
use App\Models\GroupStat;

/**
 * This class is made to be launch every saturday
 * Calculation of a week load
 */
class CalculateWorkLoad
{
    public function __invoke()
    {
        $groups = Group::all();

        foreach ($groups as $group) {
            $wes = $group->getCurrentWeekScore();

            if ($wes > 0) {
                $stat = new GroupStat();
                $stat->group_id = $group->id;
                $stat->wes = $wes;
                $stat->save();
            }
        }
    }
}
