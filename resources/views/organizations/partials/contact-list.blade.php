@if(count($contacts) > 0)

    <table class="table mt-3">
        <thead>
        <tr>
            <th id="name-col">Nom</th>
            <th id="email-col">Email</th>
            <th id="phone-num-col">Numéro de téléphone</th>
            <th id="job-col">Fonction</th>
            <th id="actions-col"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($contacts as $contact)
            <tr>
                <td>{{ $contact->name }}</td>
                <td>{{ $contact->email }}</td>
                <td>{{ $contact->phone_number }}</td>
                <td>{{ $contact->job }}</td>
                <td>
                    <button class="btn btn-primary btn-sm edit-contact" data-contact-id="{{ $contact->id }}"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-danger btn-sm delete-contact" data-contact-id="{{ $contact->id }}"><i class="fas fa-trash-alt"></i></button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center" id="pagination-container">
        {{ $contacts->links() }}
    </div>

@else
    <p class="mt-3">Aucun contact n'a été trouvé</p>
@endif
