<ul class="list-group">
    @foreach ($permissions as $permission)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            {{ ucfirst($permission->name) }} {{ $permission->typeable->folder->name }} {{ $permission->target_type }}
        </li>
    @endforeach
</ul>