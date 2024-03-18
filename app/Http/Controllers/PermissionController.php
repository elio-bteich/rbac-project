<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\FolderPermission;
use App\Models\Permission;
use App\Models\PermissionTarget;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PermissionController extends Controller
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

}
