<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'global' => [
        'operation_failed' => "The operation failed.",
    ],
    'subjects' => [
        'linked' => 'This subject cannot be delete, there task linked on it!',
        'success_delete' => 'This subject has been delete successfully.'
    ],
    'comments' => [
        'delete' => 'Comment has been delete successfully!',
        'delete_failed' => 'Comment cannot be delete.'
    ],
    'tasks' => [
        'deleted' => 'Task has been successfully created!',
        'not_permitted' => 'You cannot delete a task if you are not the author.'
    ],
    'groups' => [
        'status_invalid' => 'The request status is incorrect',
        'admin_operation' => 'This operation is restricted to the leader.',
        'delete' => 'group has been deleted',
        'resource_restricted' => 'This operation can\'t be done on that resource.',
        'member_updated' => 'Member\'s status successfully updated'
    ],
    'users' => [
        'remove_group' => "The group has been removed successfully",
    ]

];
