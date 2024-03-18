<form id="permissionForm" method="POST" action="{{ route('roles.updatePermissions', ['role' => $role->id]) }}">
    @csrf
    @method('PUT')
    <div class="modal-body">
        @foreach ($permissions as $permission)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission{{ $permission->id }}"
                       @if($role->hasPermission($permission)) checked @endif>
                <label class="form-check-label" for="permission{{ $permission->id }}">
                    {{ ucfirst($permission->name) }} {{ $permission->typeable->folder->name }} {{ $permission->target_type }}
                </label>
            </div>
        @endforeach
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
        <button type="submit" class="btn btn-primary" id="savePermissionsButton">Enregister</button>
    </div>
</form>

