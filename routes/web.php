<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth', 'prefix' => 'users'], function() {

    Route::post('/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    Route::get('/search', [UserController::class, 'search'])->name('users.search');

    Route::get('/', [UserController::class, 'index'])->name('users.index');

    Route::get('/create', [UserController::class, 'create'])->name('users.create');

    Route::post('', [UserController::class, 'store'])->name('users.store');

    Route::get('/{user}', [UserController::class, 'show'])->name('users.show');

    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');

    Route::patch('/{user}/update-role', [UserController::class, 'updateRole'])->name('users.update-role');

    Route::put('/{user}', [UserController::class, 'update'])->name('users.update');

    Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

Route::group(['middleware' => 'auth', 'prefix' => 'contacts'], function () {

    Route::get('/search', [ContactController::class, 'search'])->name('contacts.search');

    Route::get('/render-contact-view/{contact}', [ContactController::class, 'renderContactView'])->name('contacts.render-contact-view');

    Route::get('/render-contact-edit/{contact}', [ContactController::class, 'renderContactEdit'])->name('contacts.render-contact-edit');

    Route::get('/', [ContactController::class, 'index'])->name('contacts.index');

    Route::get('/create', [ContactController::class, 'create'])->name('contacts.create');

    Route::post('/', [ContactController::class, 'store'])->name('contacts.store');

    Route::get('/{contact}/edit', [ContactController::class, 'edit'])->name('contacts.edit');

    Route::put('/{contact}', [ContactController::class, 'update'])->name('contacts.update');

    Route::delete('/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');

    Route::get('/location/{location}', [ContactController::class, 'getContactsByFolder']);

});

Route::group(['middleware' => 'auth', 'prefix' => 'folders'], function () {

    Route::get('/create', [FolderController::class, 'create'])->name('folders.create');

    Route::post('/', [FolderController::class, 'store'])->name('folders.store');

    Route::get('/{folder}', [FolderController::class, 'show'])->name('folders.show');

    Route::get('/{folder}/edit', [FolderController::class, 'edit'])->name('folders.edit');

    Route::put('/{folder}', [FolderController::class, 'update'])->name('folders.update');

    Route::delete('/{folder}', [FolderController::class, 'destroy'])->name('folders.destroy');

});

Route::group(['middleware' => 'auth', 'prefix' => 'organizations'], function () {

    Route::get('/search', [OrganizationController::class, 'search'])->name('organizations.search');

    Route::get('/', [OrganizationController::class, 'index'])->name('organizations.index');

    Route::get('/create', [OrganizationController::class, 'create'])->name('organizations.create');

    Route::post('/', [OrganizationController::class, 'store'])->name('organizations.store');

    Route::get('/{organization}/search', [OrganizationController::class, 'searchContacts'])->name('organizations.contactSearch');

    Route::get('/{organization}', [OrganizationController::class, 'show'])->name('organizations.show');

    Route::get('/{organization}/edit', [OrganizationController::class, 'edit'])->name('organizations.edit');

    Route::put('/{organization}', [OrganizationController::class, 'update'])->name('organizations.update');

    Route::delete('/{organization}/{force}', [OrganizationController::class, 'destroy'])->name('organizations.destroy');

});


Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function () {

    Route::get('/', [AdminController::class, 'home'])->name('admin.home');

});

// Routes for managing roles
Route::group(['prefix' => 'roles'], function () {

    Route::get('/', [RoleController::class, 'index'])->name('roles.index');

    Route::get('/create', [RoleController::class, 'create'])->name('roles.create');

    Route::post('/', [RoleController::class, 'store'])->name('roles.store');

    Route::get('/{role}', [RoleController::class, 'edit'])->name('roles.edit');

    Route::put('/{role}', [RoleController::class, 'update'])->name('roles.update');

    Route::delete('/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    Route::get('/{role}/permissions', [RoleController::class, 'getPermissions']);

    Route::get('/{role}/modify-permissions', [RoleController::class, 'getPermissionsToModify']);

    Route::put('/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.updatePermissions');

    Route::get('/{role}/canAddSubRole', [RoleController::class, 'canAddSubRoleTo'])->name('roles.canAddSubRole');

    Route::get('/{role}/canModifyRolePermissions', [RoleController::class, 'canModifyRolePermissionsOf'])->name('roles.canModifyRolePermissions');

    Route::get('/{role}/canDeleteRole', [RoleController::class, 'canDeleteRole'])->name('roles.canDeleteRole');

    Route::get('/{role}/users', [RoleController::class, 'getUsersByRole'])->name('roles.getUsersByRole');


});

// Routes for managing permissions
Route::group(['prefix' => 'permissions'], function () {

    Route::get('/create', [PermissionController::class, 'create'])->name('permissions.create');

    Route::post('/', [PermissionController::class, 'store'])->name('permissions.store');

    Route::get('/{permission}', [PermissionController::class, 'edit'])->name('permissions.edit');

    Route::put('/{permission}', [PermissionController::class, 'update'])->name('permissions.update');

    Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

});

Auth::routes();

