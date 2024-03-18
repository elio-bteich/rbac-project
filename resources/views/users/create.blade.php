@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Créer utilisateur
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('users.store') }}" id="user-form">
                        @csrf

                        <div class="form-group">
                            <label for="name">Nom</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')

                                @include('alerts.error-message')

                            @enderror
                        </div>

                        <div class="form-group my-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')

                                @include('alerts.error-message')

                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="role">Role</label>
                            <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="" selected disabled>Select a role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')

                                @include('alerts.error-message')

                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary" id="submit-button">Créer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

    <script>
        $(document).ready(function() {
            $('#user-form').on('submit', function() {
                $('#submit-button').prop('disabled', true);
            });
        });
    </script>

@endsection
