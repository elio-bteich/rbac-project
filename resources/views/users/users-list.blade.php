@if (count($users) > 0)
    <table class="table table-bordered">
        <thead>
        <tr>
            <th style="width: 20%">Nom</th>
            <th style="width: 25%">Email</th>
            <th style="width: 20%">Role</th>
            <th style="width: 10%">Status</th>
            <th style="width: 25%">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if (canEditUser($user))
                        <div class="dropdown">
                            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="roleDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ $user->role ? $user->role->name : 'Selectionner Role' }}
                            </button>
                            <div class="dropdown-menu custom-dropdown-menu" aria-labelledby="roleDropdown">
                                @foreach ($roles as $role)
                                    <button class="dropdown-item change-role" data-user-id="{{ $user->id }}" data-role-id="{{ $role->id }}" data-role-name="{{ $role->name }}">
                                        {{ $role->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @else
                        {{ $user->role ? $user->role->name : null }}
                    @endif
                </td>
                <td class="user-status">{{ $user->status ? 'Active' : 'Inactive' }}</td>
                <td>
                    @if(canEditUser($user))
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">Update</a>
                        <button class="btn btn-sm btn-info toggle-status" data-user-id="{{ $user->id }}" data-current-status="{{ $user->status }}">
                            {{ $user->status ? 'Désactiver' : 'Activer' }}
                        </button>
                    @endif
                    @if(canDeleteUser($user))
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Etes vous sure de vouloir supprimer cet utilisateur?')">Supprimer</button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif

@if ($users->isEmpty())
    <p>Pas d'utilisateurs trouvés.</p>
@endif

<div id="pagination-container">
    <div class="d-flex justify-content-center">
        {{ $users->links() }}
    </div>
</div>
