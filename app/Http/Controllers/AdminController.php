<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the admin homepage
     *
     * @return Application|Factory|View
     */
    public function home() {

        $roles = Auth::user()->role->getAllChildren();

        $currentRole = Auth::user()->role;
        $currentRole->parent_role_id = null;

        $roles->add($currentRole);
        $rolesTree = RoleController::transformToJSTreeFormat($roles);

        $permissions = Permission::all();

        return view("admin.home", compact("rolesTree", "permissions"));
    }
}
