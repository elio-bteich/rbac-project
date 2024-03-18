<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Contact;
use App\Models\Organization;
use http\Env\Response;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the organizations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $organizations = Organization::all();
        return view('organizations.index', compact('organizations'));
    }

    /**
     * Show the form for creating a new organization.
     *
     * @return Application|Factory|View|string
     */
    public function create()
    {
        $addresses = Address::all();

        if (request()->ajax()) {
            return view('organizations.modals.create', compact('addresses'))->render();
        }

        return view('organizations.create', compact('addresses'));
    }

    /**
     * Store a newly created organization in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:organizations',
            'email' => 'nullable|email|unique:organizations',
            'phone_number' => [
                'nullable',
                'regex:/^[0-9]+$/',
                'min:9',
                'max:10',
            ],
            'street' => [
                'nullable',
                'regex:/^[0-9]*[\p{L}\s\'-]+$/',
                'max:255'
            ],
            'city' => [
                'nullable',
                'regex:/^[a-zA-Z]+(?:-[a-zA-Z]+)*$/',
                'max:255'
            ],
            'postal_code' => [
                'nullable',
                'regex:/^[0-9\s]+$/',
                'min:5',
                'max:5'
            ]
        ], [
            'name.required' => 'Le champ nom est obligatoire.',
            'name.unique' => 'Ce nom est déjà utilisé par une autre organisation.',
            'name.max' => 'Le champ nom ne peut pas dépasser :max caractères.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée par une autre organisation.',
            'phone_number.regex' => 'Le numéro de téléphone doit contenir uniquement des chiffres.',
            'phone_number.min' => 'Le numéro de téléphone doit comporter au moins :min chiffres.',
            'phone_number.max' => 'Le numéro de téléphone ne peut pas dépasser :max chiffres.',
            'street.regex' => 'Le champ rue contient des caractères invalides.',
            'street.max' => 'Le champ rue ne peut pas dépasser :max caractères.',
            'city.regex' => 'Le champ ville contient des caractères invalides.',
            'city.max' => 'Le champ ville ne peut pas dépasser :max caractères.',
            'postal_code.regex' => 'Le code postal ne peut contenir que des chiffres et des espaces.',
            'postal_code.min' => 'Le code postal doit comporter au moins :min chiffres.',
            'postal_code.max' => 'Le code postal doit comporter exactement :max caractères.',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'inputError' => true,
                'messages' => $validator->errors(),
            ]);
        }

        $organization = new Organization();
        $organization->name = $request->input('name');
        $organization->email = $request->input('email');
        $organization->phone_number = $request->input('phone_number');

        $organization->save();

        $address = Address::firstOrCreate(
            [
                'street' => $request->input('street'),
                'city' => $request->input('city'),
                'postal_code' => $request->input('postal_code'),
            ]
        );

        $organization->address_id = $address->id;
        $organization->save();

        return response()->json([
            'success' => 'Structure crée successfully.',
        ]);
    }


    /**
     * Display the specified organization.
     *
     * @param Organization $organization
     * @return Application|Factory|View|JsonResponse
     */
    public function show(Organization $organization)
    {
        if (request()->ajax()) {
            return response()->json([
                'html' => view('organizations.modals.show', compact('organization'))->render()
            ]);
        }

        return view('organizations.show', compact('organization'));
    }

    /**
     * Show the form for editing the specified organization.
     *
     * @param Organization $organization
     * @return Application|Factory|View|JsonResponse
     */
    public function edit(Organization $organization)
    {
        if (request()->ajax()) {
            $formData = [
                'name' => $organization->name,
                'email' => $organization->email,
                'phone_number' => $organization->phone_number,
                'city' => $organization->address->city,
                'street' => $organization->address->street,
                'postal_code' => $organization->address->postal_code
            ];

            return response()->json([
                'html' => view('organizations.modals.edit')->render(),
                'formData' => $formData
            ]);
        }

        return view('organizations.edit', compact('organization'));
    }

    /**
     * Update the specified organization in storage.
     *
     * @param Request $request
     * @param Organization $organization
     * @return JsonResponse
     */
    public function update(Request $request, Organization $organization): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:organizations,email,' . $organization->id,
            'phone_number' => [
                'nullable',
                'regex:/^[0-9\s]+$/',
                'min:9',
                'max:10',
            ],
            'street' => [
                'nullable',
                'regex:/^[0-9]*[\p{L}\s\'-]+$/',
                'max:255'
            ],
            'city' => [
                'nullable',
                'regex:/^[a-zA-Z]+(?:-[a-zA-Z]+)*$/',
                'max:255'
            ],
            'postal_code' => [
                'nullable',
                'regex:/^[0-9\s]+$/',
                'min:5',
                'max:5'
            ]
        ], [
            'name.required' => 'Le champ nom est obligatoire.',
            'name.max' => 'Le champ nom ne peut pas dépasser :max caractères.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée par une autre organisation.',
            'phone_number.regex' => 'Le numéro de téléphone ne peut contenir que des chiffres et des espaces.',
            'phone_number.min' => 'Le numéro de téléphone doit comporter au moins :min chiffres.',
            'phone_number.max' => 'Le numéro de téléphone ne peut pas dépasser :max chiffres.',
            'street.regex' => 'Le champ rue contient des caractères invalides.',
            'street.max' => 'Le champ rue ne peut pas dépasser :max caractères.',
            'city.regex' => 'Le champ ville contient des caractères invalides.',
            'city.max' => 'Le champ ville ne peut pas dépasser :max caractères.',
            'postal_code.regex' => 'Le code postal ne peut contenir que des chiffres',
            'postal_code.min' => 'Le code postal doit comporter au moins :min chiffres.',
            'postal_code.max' => 'Le code postal doit comporter exactement :max caractères.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'inputError' => true,
                'messages' => $validator->errors(),
            ]);
        }

        $organization->update($request->all());

        return response()->json([
            'success' => 'La structure a été mis à jour avec succès',
        ]);
    }


    /**
     * Remove the specified organization from storage.
     *
     * @param Organization $organization
     * @return JsonResponse
     */
    public function destroy(Organization $organization, $force=false): JsonResponse
    {
        if (count($organization->contacts)>0 and !$force) {
            return response()->json([
                'constraintError' => 'La structure possède des contacts',
            ]);
        }

        $organization->delete();

        return response()->json([
            'success' => 'La structure a été supprimé avec succès',
        ]);

    }

    public function searchContacts(Organization $organization, Request $request)
    {
        $searchTerm = $request->input('search');

        $contacts = Contact::where('organization_id', $organization->id)
            ->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $searchTerm . '%')
                    ->orWhere('phone_number', 'like', '%' . $searchTerm . '%');
            })
            ->paginate(8);

        if ($request->ajax()) {
            $contactsHtml = view('contacts.partials.contact-list', compact('contacts'))->render();
            $paginationHtml = $contacts->links()->toHtml();

            return response()->json([
                'contactsHtml' => $contactsHtml,
                'paginationHtml' => $paginationHtml,
            ]);
        }
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('search');

        $organizations = Organization::where('name', 'like', '%' . $searchTerm . '%')
            ->orWhere('email', 'like', '%' . $searchTerm . '%')
            ->orWhere('phone_number', 'like', '%' . $searchTerm . '%')
            ->with('address')
            ->paginate(11);

        if ($request->ajax()) {
            $organizationsHtml = view('organizations.partials.organizations-list', compact('organizations'))->render();
            $paginationHtml = $organizations->links()->toHtml();

            return response()->json([
                'organizationsHtml' => $organizationsHtml,
                'paginationHtml' => $paginationHtml,
            ]);
        }

        return view('organizations.index', compact('organizations'));
    }

    public function renderOrganizationView(Organization $organization)
    {
        // Implement the logic to render organization details in a modal
        // Return the rendered view as HTML response
    }
}
