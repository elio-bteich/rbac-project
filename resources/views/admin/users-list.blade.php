@if(count($users) > 0)
    <table class="table table-bordered">
        <thead>
        <tr>
            <th style="width: 30%">Name</th>
            <th style="width: 40%">Email</th>
            <th style="width: 30%">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if(canEditUser($user))
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">Change Role</a>
                        <button class="btn btn-sm btn-info" id="toggle-status" data-user-id="{{ $user->id }}" data-current-status="{{ $user->status }}">
                            {{ $user->status ? 'Désactiver' : 'Activer' }}
                        </button>
                    @endif
                    @if(canDeleteUser($user))
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete User</button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <p>No users found for this role.</p>
@endif
