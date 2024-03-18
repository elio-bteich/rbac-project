<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Role;
use Illuminate\Mail\Mailable;

class RoleChanged extends Mailable
{
    public $user;
    public $newRole;

    /**
     * Create a new message instance.
     *
     * @param User $user The user whose role has changed
     * @param Role $newRole The new role assigned to the user
     */
    public function __construct(User $user, Role $newRole)
    {
        $this->user = $user;
        $this->newRole = $newRole;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.user.role-changed')
            ->subject('Votre rôle a été changé');
    }
}
