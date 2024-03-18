<?php

namespace App\Http\Controllers;

use App\Events\RoleChanged;
use App\Events\UserCreated;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('users.index');
    }

    /**
     * Show the form for creating a new user.
     *
     * @return Application|Factory|View|RedirectResponse
     */
    public function create()
    {
        if (hasAnySubRole()){
            $roles = Auth::user()->role->childRoles;
            return view('users.create', compact('roles'));
        } else {
            return redirect()->route('users.index')->with('error', "You don't have any sub role, thus you can't create a user!");
        }
    }

    /**
     * Store a newly created user in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|exists:roles,id',
        ]);

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role_id = $request->input('role');
        $user->save();

        event(new UserCreated($user));

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return Application|Factory|View|RedirectResponse
     */
    public function edit(User $user)
    {
        Session::put('previous_url', url()->previous());

        // Check if the authenticated user has permission to edit the user
        if (!canEditUser($user)) {
            return back()->with('error', 'You do not have permission to edit this user.');
        }

        $roles = Auth::user()->role->getAllChildren();

        return view('users.edit', compact('user', 'roles'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        // Check if the authenticated user has permission to update the user
        if (!canEditUser($user)) {
            return back()->with('error', 'You do not have permission to update this user.');
        }

        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $newRole = Role::findOrFail($request->input('role_id'));
        $user->update(['role_id' => $newRole->id]);

        event(new RoleChanged($user, $newRole));
        return redirect()->to(Session::pull('previous_url'))->with('success', 'User role updated successfully.');
    }

    /**
     * Update the user's role via AJAX.
     *
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function updateRole(Request $request, User $user)
    {
        $role_id = $request->input('role_id');
        $role = Role::find($role_id);

        if (!$role) {
            return response()->json(['error' => 'Role not found'], 400);
        }

        $user->role_id = $role->id;
        $user->save();

        return response()->json(['role_name' => $role->name], 200);
    }

    /**
     * Remove the specified user from storage.
     *
     * @param User $user
     * @return RedirectResponse
     */
    public function destroy(User $user)
    {
        // Check if the authenticated user has permission to delete the user
        if (!canDeleteUser($user)) {
            return back()->with('error', 'You do not have permission to delete this user.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }

    /**
     * Search for users based on the provided search term.
     *
     * @param Request $request
     * @return string
     */
    public function search(Request $request)
    {
        $searchTerm = $request->input('search');
        $roles = Auth::user()->role->getAllChildren(true);
        $filteredUsers = collect();

        foreach ($roles as $role) {
            if (count($role->users) > 0) {
                $query = $role->users()->where('name', 'LIKE', "%$searchTerm%")
                    ->where('email', 'LIKE', "%$searchTerm%");

                $filteredUsers = $filteredUsers->merge($query->get());
            }
        }

        $users = $this->paginate($filteredUsers, 10);

        $roles = $roles->reject(function ($role) {
            return $role->id === Auth::user()->role->id;
        });

        return view('users.users-list', compact('users', 'roles'))->render();
    }

    private function paginate($items, $perPage)
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $items->slice(($currentPage - 1) * $perPage, $perPage);

        $paginatedItems = new LengthAwarePaginator($currentItems, $items->count(), $perPage, $currentPage);

        return $paginatedItems->withPath(route('users.index'))->appends(request()->query());
    }


    /**
     * Toggle the status (activate/deactivate) of a user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function toggleStatus(Request $request)
    {
        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }

        $user->status = $user->status === 0 ? 1 : 0;
        $user->save();

        return response()->json(['status' => 'success', 'new_status' => $user->status]);
    }

}
