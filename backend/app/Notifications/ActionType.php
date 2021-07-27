<?php

namespace App\Notifications;

/**
 * Define all action types
 */
abstract class ActionType
{
    const CREATE = 1;
    const UPDATE = 2;
    const DELETE = 3;
}
