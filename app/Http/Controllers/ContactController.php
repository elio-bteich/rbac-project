<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Folder;
use App\Models\FolderPermission;
use App\Models\Organization;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ContactController extends Controller
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
     * Show the application dashboard.
     *
     * @return View
     */
    public function index()
    {
        $user = Auth::user();
        $folders = $user->getFoldersOfPermission('read', Contact::class);
        return view("contacts.index", compact("folders", "user"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return string
     */
    public function create()
    {
        $user = Auth::user();
        $folders = $user->getFoldersOfPermission('create', Contact::class);
        $organizations = Organization::all();
        return view('contacts.modals.create', compact('folders', 'organizations'))->render();
    }

    /**
     * Store a newly created Contact in storage
     *
     * @param Request $request: the contact creation request
     * @return JsonResponse: the json response to the request
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_number' => [
                'nullable',
                'regex:/^[0-9]+$/',
                'min:9',
                'max:10',
            ],
            'folder_ids' => [
                'required',
                'array',
                'exists:folders,id'
            ],
            'organization_id' => [
                'nullable',
                'exists:organizations,id'
            ],
            'belongsToStructure' => [
                'in:on'
            ],
            'job' => [
                'nullable',
                'string'
            ],
            'comments' => [
                'nullable',
                'string'
            ]
        ], [
            'name.required' => 'Le champ nom est obligatoire.',
            'name.max' => 'Le champ nom ne peut pas dépasser :max caractères.',
            'email.required' => 'Le champ email est obligatoire.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'phone_number.regex' => 'Le numéro de téléphone doit contenir uniquement des chiffres.',
            'phone_number.min' => 'Le numéro de téléphone doit comporter au moins :min chiffres.',
            'phone_number.max' => 'Le numéro de téléphone ne peut pas dépasser :max chiffres.',
            'folder_ids.required' => 'Veuillez sélectionner au moins un dossier.',
            'folder_ids.exists' => 'Les dossiers sélectionnés ne sont pas valides.',
            'organization_id.exists' => 'L\'organisation sélectionnée n\'est pas valide.',
            'belongsToStructure.in' => 'La valeur de l\'appartenance à une structure doit être "on".',
            'job.string' => 'Le champ emploi doit être une chaîne de caractères.',
            'comments.string' => 'Le champ commentaires doit être une chaîne de caractères.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'inputError' => true,
                'messages' => $validator->errors(),
            ]);
        }

        $requiredPermissions = FolderPermission::getPermissionsByAttributes(
            'create',
            Contact::class,
            $request->input('folder_ids')
        );

        $userHasPermission = true;
        foreach ($requiredPermissions as $permission) {
            if (!$request->user()->hasPermission($permission)) {
                $userHasPermission = false;
                break;
            }
        }

        if ($userHasPermission) {

            $contact = new Contact();
            $contact->name = $request->input('name');
            $contact->email = $request->input('email');
            $contact->phone_number = $request->input('phone_number');
            if ($request->input('belongsToStructure') === 'on') {
                $contact->organization_id = $request->input('organization_id');
            } else if (!$request->input('belongsToStructure')) {
                $contact->organization_id = null;
            }
            $contact->job = $request->input('job');
            $contact->comments = $request->input('comments');
            $contact->save();
            $contact->folders()->attach($request->input('folder_ids'));

            return response()->json([
                'success' => 'Le contact a été crée avec succès',
            ]);
        }
        return response()->json([
            'permissionError' => "Vous ne pouvez pas ajouter des contacts à ce(s) dossier(s)",
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Contact $contactController
     * @return Response
     */
    public function show(Contact $contactController)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Contact $contact
     * @return Application|Factory|View
     */
    public function edit(Contact $contact)
    {
        $user = Auth::user();
        $folders = $user->getFoldersOfPermission('edit', Contact::class);
        return view('contacts.edit', compact('contact', 'folders'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Contact $contact
     * @return JsonResponse
     */
    public function update(Request $request, Contact $contact): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_number' => [
                'nullable',
                'regex:/^[0-9\s]+$/',
                'min:9',
                'max:10',
            ],
            'folder_ids' => [
                'required',
                'array',
                'exists:folders,id'
            ],
            'organization_id' => [
                'nullable',
                'exists:organizations,id'
            ],
            'belongsToStructure' => [
                'in:on'
            ],
            'job' => [
                'nullable',
                'string'
            ],
            'comments' => [
                'nullable',
                'string'
            ]
        ], [
            'name.required' => 'Le champ nom est obligatoire.',
            'name.max' => 'Le champ nom ne peut pas dépasser :max caractères.',
            'email.required' => 'Le champ email est obligatoire.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'phone_number.regex' => 'Le numéro de téléphone ne peut contenir que des chiffres et des espaces.',
            'phone_number.min' => 'Le numéro de téléphone doit comporter au moins :min chiffres.',
            'phone_number.max' => 'Le numéro de téléphone ne peut pas dépasser :max chiffres.',
            'folder_ids.required' => 'Veuillez sélectionner au moins un dossier.',
            'folder_ids.exists' => 'Les dossiers sélectionnés ne sont pas valides.',
            'organization_id.exists' => 'L\'organisation sélectionnée n\'est pas valide.',
            'belongsToStructure.in' => 'La valeur de l\'appartenance à une structure doit être "on".',
            'job.string' => 'Le champ emploi doit être une chaîne de caractères.',
            'comments.string' => 'Le champ commentaires doit être une chaîne de caractères.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'inputError' => true,
                'messages' => $validator->errors(),
            ]);
        }

        $requiredPermissions = FolderPermission::getPermissionsByAttributes(
            'edit',
            Contact::class,
            $request->input('folder_ids')
        );

        $userHasPermission = true;
        foreach ($requiredPermissions as $permission) {
            if (!$request->user()->hasPermission($permission)) {
                $userHasPermission = false;
                break;
            }
        }

        if ($userHasPermission) {

            $contact->name = $request->input('name');
            $contact->email = $request->input('email');
            $contact->phone_number = $request->input('phone_number');
            if ($request->input('belongsToStructure') === 'on') {
                $contact->organization_id = $request->input('organization_id');
            } else if (!$request->input('belongsToStructure')) {
                $contact->organization_id = null;
            }
            $contact->job = $request->input('job');
            $contact->comments = $request->input('comments');
            $contact->save();
            $contact->folders()->sync($request->input('folder_ids'));

            return response()->json([
                'success' => 'Le contact a été mis à jour avec succès',
            ]);
        }

        return response()->json([
            'permissionError' => "Vous ne pouvez pas mettre à jour ce contact dans ce(s) dossier(s)",
        ]);
    }

    /**
     * Remove the specified contact from storage.
     *
     * @param  Contact  $contact
     * @return JsonResponse
     */
    public function destroy(Contact $contact): JsonResponse
    {
        $contact->delete();
        return response()->json([
            'message' => 'le contact a été supprimé avec succès',
        ]);
    }

    /**
     * Render the view modal for a specific contact.
     *
     * @param Contact $contact The contact to render the view modal for.
     * @return string The rendered HTML content of the view modal.
     */
    public function renderContactView(Contact $contact): string
    {
        return view('contacts.modals.show', compact('contact'))->render();
    }

    /**
     * Render the edit modal for a specific contact.
     *
     * @param Contact $contact The contact to render the edit modal for.
     * @return JsonResponse The rendered HTML content of the edit modal.
     */
    public function renderContactEdit(Contact $contact): JsonResponse
    {
        $user = Auth::user();
        $folders = $user->getFoldersOfPermission('edit', Contact::class);
        $organizations = Organization::all();

        // Create an associative array with the form field values
        $formData = [
            'name' => $contact->name,
            'email' => $contact->email,
            'phone_number' => $contact->phone_number,
            'folder_ids[]' => $contact->folders ? $contact->folders->pluck('id')->toArray() : null, // Assuming this is an array of folder IDs
            'belongsToStructure' => $contact->organization ? 'on' : 'off',
            'organization_id' => $contact->organization ?  $contact->organization->id : null,
            'job' => $contact->job,
            'comments' => $contact->comments
        ];

        return response()->json([
            'html' => view('contacts.modals.edit', compact('folders', 'organizations'))->render(),
            'formData' => $formData
        ]);
    }

    /**
     * Get the contacts of a specific location
     *
     * @param Folder $folder
     * @return string
     */
    public function getContactsByFolder(Folder $folder): string
    {
        $contacts = $folder->contacts()->paginate(12);
        return view('contacts.contacts-list', compact('contacts'))->render();
    }

    /**
     * Search for contacts based on the provided search term.
     *
     * @param Request $request The HTTP request containing the search term.
     * @return JsonResponse The rendered view of the contacts list with search results.
     */
    public function search(Request $request): JsonResponse
    {
        $searchTerm = $request->input('search');
        $folderId = $request->input('folder');
        $organizationId = $request->input('organizationId');
        $paginationLength = $request->input('paginationLength');

        $query = Contact::query();

        if (!is_null($folderId)) {
            $query->whereHas('folders', function ($q) use ($folderId) {
                $q->where('folder_id', $folderId);
            });
        }

        $query->where(function ($q) use ($searchTerm) {
            $q->where('name', 'LIKE', "%$searchTerm%")
                ->orWhere('email', 'LIKE', "%$searchTerm%")
                ->orWhere('phone_number', 'LIKE', "%$searchTerm%");
        });

        Log::info(is_null($organizationId));

        if (!is_null($organizationId)) {
            $query->where('organization_id', $organizationId);
        }

        $contacts = $query->paginate($paginationLength);

        $contactsHtml = view('contacts.partials.contact-list', compact('contacts'))->render();
        $paginationHtml = $contacts->links()->toHtml();

        return response()->json([
            'contactsHtml' => $contactsHtml,
            'paginationHtml' => $paginationHtml,
        ]);
    }
}
