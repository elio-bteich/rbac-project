@if(count($contacts) > 0)
    <table class="table">
        <thead>
        <tr>
            <th class="custom-width-name">Nom</th>
            <th class="custom-width-email">Email</th>
            <th class="custom-width-organization-name">Nom de la struct.</th>
            <th class="custom-width-job">Fonction/Role</th>
            <th class="custom-width-pers-number">Numéro de téléphone perso.</th>
        </tr>
        </thead>
        <tbody>
        @foreach($contacts as $contact)
            <tr class="contact-row" data-contact-id="{{ $contact->id }}">
                <td>{{ $contact->name }}</td>
                <td>{{ $contact->email }}</td>
                <td>{{ $contact->organization ? $contact->organization->name : '' }}</td>
                <td>{{ $contact->job ?? 'pas défini' }}</td>
                <td>{{ $contact->phone_number }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center" id="pagination-container">
        {{ $contacts->links() }}
    </div>
@else
    <p>Aucun contact n'a été trouvé</p>
@endif

