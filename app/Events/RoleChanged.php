<?php

namespace App\Events;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoleChanged
{
    use Dispatchable, SerializesModels;

    public $user;
    public $newRole;

    /**
     * Create a new event instance.
     *
     * @param User $user The user whose role has changed
     * @param Role $newRole The new role assigned to the user
     */
    public function __construct(User $user, Role $newRole)
    {
        $this->user = $user;
        $this->newRole = $newRole;
    }
}
