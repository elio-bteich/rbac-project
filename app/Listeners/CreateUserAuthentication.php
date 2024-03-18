<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Mail\UserAccountCreated;
use App\Models\UserAuthentication;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CreateUserAuthentication
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param UserCreated $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        $user = $event->user;

        $authentication = new UserAuthentication();

        $password = Str::random(12);

        $authentication->user_id = $user->id;
        $authentication->email = $user->email;
        $authentication->password = Hash::make($password);
        $authentication->save();

        Mail::to($user->email)->send(new UserAccountCreated($user, $password));
    }
}
