@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">{{ __('Create Role') }}</div>
                <form method="POST" action="{{ route('roles.store') }}">
                    @csrf
                    <div class="card-body"> <!-- Moved the opening tag here -->

                        <div class="form-group mb-2">
                            <label for="name">{{ __('Role Name') }}</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                            @error('name')
                                @include('alerts.error-message')
                            @enderror
                        </div>

                        <div class="form-group my-2">
                            <label for="parent_role">{{ __('Parent Role') }}</label>
                            <select id="parent_role" class="form-control @error('parent_role') is-invalid @enderror" name="parent_role" required>
                                <option value="" selected disabled>Choisissez un role parent</option>
                                <option value="root">Root</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>

                            @error('parent_role')
                                @include('alerts.error-message')
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-start">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Create') }}
                            </button>
                            <a href="{{ route('admin.home') }}" class="btn btn-secondary mx-2">
                                {{ __('Cancel') }}
                            </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
