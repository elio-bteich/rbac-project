@if(count($organizations) > 0)

    <table class="table mt-3">
        <thead>
        <tr>
            <th id="name-col">Nom</th>
            <th id="email-col">Email</th>
            <th id="phone-num-col">Numéro de téléphone</th>
            <th id="address-col">Adresse</th>
            <th id="actions-col"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($organizations as $organization)
            <tr>
                <td>{{ $organization->name }}</td>
                <td>{{ $organization->email }}</td>
                <td>{{ $organization->phone_number }}</td>
                <td>
                    {{ $organization->address ? $organization->address->street . ', ' . $organization->address->postal_code . ' ' . $organization->address->city : 'N/A' }}
                </td>
                <td>
                    <a href="{{ route('organizations.show', ['organization' => $organization->id]) }}" class="btn btn-secondary btn-sm view-organization" data-org-id="{{ $organization->id }}"><i class="fas fa-eye"></i></a>
                    <button class="btn btn-primary btn-sm edit-organization" data-org-id="{{ $organization->id }}"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-danger btn-sm delete-organization" data-org-id="{{ $organization->id }}"><i class="fas fa-trash-alt"></i></button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center" id="pagination-container">
        {{ $organizations->links() }}
    </div>

@else
    <p class="mt-3">Aucune structure n'a été trouvée</p>
@endif


