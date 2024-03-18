<?php

namespace App\Listeners;

use App\Events\RoleChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\RoleChanged as RoleChangedMail;

class SendRoleChangedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param RoleChanged $event
     * @return void
     */
    public function handle(RoleChanged $event)
    {
        $user = $event->user;
        $newRole = $event->newRole;

        Mail::to($user->email)->send(new RoleChangedMail($user, $newRole));
    }
}

