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
        'operation_failed' => "L'opération a échouée.",
    ],
    'subjects' => [
        'linked' => 'Ce sujet ne peut être supprimé car il y a encore des tâches liées',
        'success_delete' => 'Le sujet a été supprimé avec succès'
    ],
    'comments' => [
        'delete' => 'Le commentaire a été supprimé avec succès',
        'delete_failed' => 'Ce commentaire ne peut être supprimé'
    ],
    'tasks' => [
        'deleted' => 'La tâche a été créée avec succès',
        'not_permitted' => 'Seul l\'auteur de la tâche peut effectuer cette action',
    ],
    'groups' => [
        'status_invalid' => 'Le status est invalide',
        'admin_operation' => 'Cette opération ne peut être effectuée uniquement par l\'administrateur du groupe',
        'delete' => 'Ce groupe a été supprimé',
        'resource_restricted' => 'Cette opération ne peut être effectuée sur cette ressource',
        'member_updated' => 'Le status du membre a été mise à jour'
    ],
    'users' => [
        'remove_group' => "Le group a été enlevé avec succès",
    ],
];
