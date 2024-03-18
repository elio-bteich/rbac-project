@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Modifier Role</div>
                <form method="POST" action="{{ route('roles.update', $role->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="card-body">

                        <div class="form-group">
                            <label for="name">Nom du role</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $role->name) }}" required autocomplete="name" autofocus>

                            @error('name')
                                @include('alerts.error-message')
                            @enderror
                        </div>

                        <div class="form-group my-2">
                            <label for="parent_role">Role parent</label>
                            <select id="parent_role" class="form-control @error('parent_role') is-invalid @enderror" name="parent_role" required>
                                <option value="root" @if($role->parent_role_id === null) selected @endif>Root</option>
                                @foreach($roles as $r)
                                    <option value="{{ $r->id }}" @if($role->parent_role_id === $r->id) selected @endif>{{ $r->name }}</option>
                                @endforeach
                            </select>

                            @error('parent_role')
                                @include('alerts.error-message')
                            @enderror
                        </div>

                    </div>
                    <div class="card-footer d-flex justify-content-start" style="background-color: #f0f0f0;">
                        <button type="submit" class="btn btn-primary">
                            Modifier
                        </button>
                        <a href="{{ route('admin.home') }}" class="btn btn-secondary mx-2">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
