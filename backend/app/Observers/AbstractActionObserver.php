<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * This abstract class is used to share common utilities between Observers
 */
abstract class AbstractActionObserver
{
    /** @var User $user */
    protected $user;


    public function __construct()
    {
        if (Auth::check()) {
            $this->user = Auth::user();
        }
    }
}
