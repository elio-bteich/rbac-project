@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Modifier Utilisateur
                </div>
                <div class="card-body">
                    <form action="{{ route('users.update', $user->id) }}" id="user-form" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name">Nom</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" readonly>
                        </div>

                        <div class="form-group my-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" readonly>
                        </div>

                        <div class="form-group mb-4">
                            <label for="role">Role</label>
                            <select class="form-control" id="role" name="role_id">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ $user->role_id === $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary" id="submit-button">Modifier</button>
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
