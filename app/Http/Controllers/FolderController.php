<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Folder;
use App\Models\FolderPermission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FolderController extends Controller
{
    public function create(): string
    {
        return view('folders.modals.create')->render();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:folders',
        ], [
            'name.required' => 'Le champ nom est obligatoire.',
            'name.max' => 'Le champ nom ne peut pas dépasser :max caractères.',
            'name.string' => 'Le champ nom est doit être une chaine de caractères.',
            'name.unique' => 'Le champ nom doit être unique.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'inputError' => true,
                'messages' => $validator->errors(),
            ]);
        }

        $folder = Folder::create([
            'name' => $request->input('name'),
        ]);

        FolderPermission::createPermission([
            'name' => 'create',
            'target_type' => Contact::class,
            'folder_id' => $folder->id
        ]);

        FolderPermission::createPermission([
            'name' => 'read',
            'target_type' => Contact::class,
            'folder_id' => $folder->id
        ]);

        FolderPermission::createPermission([
            'name' => 'edit',
            'target_type' => Contact::class,
            'folder_id' => $folder->id
        ]);

        FolderPermission::createPermission([
            'name' => 'delete',
            'target_type' => Contact::class,
            'folder_id' => $folder->id
        ]);

        $role = Auth::user()->role;

        $parentRoles = $role->getParentsUntilRoot();

        foreach ($parentRoles as $role) {
            $role->givePermissionTo('create', Contact::class, $folder);
            $role->givePermissionTo('read', Contact::class, $folder);
            $role->givePermissionTo('edit', Contact::class, $folder);
            $role->givePermissionTo('delete', Contact::class, $folder);
        }

        return response()->json([
            'success' => 'Le dossier a été crée avec succès',
        ]);
    }

    public function show(Folder $folder)
    {
        return view('folders.show', compact('folder'));
    }

    public function edit(Folder $folder)
    {
        return view('folders.edit', compact('folder'));
    }

    public function update(Request $request, Folder $folder)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|max:255',
        ]);

        // Update the folder's attributes
        $folder->update([
            'name' => $request->input('name'),
            // Update other attributes if needed
        ]);

        return redirect()->route('folders.index')->with('success', 'Folder updated successfully.');
    }

    public function destroy(Folder $folder)
    {
        // Delete the folder
        $folder->delete();

        return redirect()->route('folders.index')->with('success', 'Folder deleted successfully.');
    }
}
