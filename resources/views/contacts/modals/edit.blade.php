<form id="edit-contact-form">
    @csrf

    <div class="form-group mb-3">
        <label for="name">Nom</label>
        <input type="text" name="name" id="name" class="form-control" required>
    </div>

    <div class="form-group mb-3">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control" required>
    </div>

    <div class="form-group mb-3">
        <label for="phone_number">Numéro de téléphone perso.</label>
        <input type="tel" maxlength="10" name="phone_number" id="phone_number" class="form-control">
    </div>


    <div class="form-group mb-4">
        <p class="text-start">Dossiers:</p>

        <div class="row">
            @foreach($folders as $folder)
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="folder_ids[]" value="{{ $folder->id }}" id="folder_{{ $folder->id }}">
                        <label class="form-check-label" for="folder_{{ $folder->id }}">{{ $folder->name }}</label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="form-group mb-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="belongsToStructure" id="belongsToStructure">
            <label class="form-check-label" for="belongsToStructure">Le contact appartient à une structure</label>
        </div>
    </div>

    <div class="form-group mb-3" id="structureDropdown" style="display: none;">
        <label for="organization_id">Structure</label>
        <select name="organization_id" id="organization_id" class="form-control">
            <option value="" selected disabled>Choisissez une structure</option>
            @foreach($organizations as $organization)
                <option value="{{ $organization->id }}">
                    {{ $organization->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group mb-3">
        <label for="job">Job</label>
        <input type="text" name="job" id="job" class="form-control">
    </div>

    <div class="form-group mb-3">
        <label for="comments">Commentaires</label>
        <textarea name="comments" id="comments" class="form-control"></textarea>
    </div>

</form>
