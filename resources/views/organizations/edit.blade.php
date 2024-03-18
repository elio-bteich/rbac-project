@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Modifier Structure</h1>
        <form action="{{ route('organizations.update', ['organization' => $organization->id]) }}" method="post">
            @csrf
            @method('put')
            <div class="form-group">
                <label for="name">Nom</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $organization->name }}" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ $organization->email }}" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Numéro de téléphone</label>
                <input type="tel" name="phone_number" id="phone_number" class="form-control" value="{{ $organization->phone_number }}">
            </div>
            <!-- Edit address fields -->
            <div class="form-group">
                <label for="street">Rue</label>
                <input type="text" name="street" id="street" class="form-control" value="{{ optional($organization->address)->street }}">
            </div>
            <div class="form-group">
                <label for="city">Ville</label>
                <input type="text" name="city" id="city" class="form-control" value="{{ optional($organization->address)->city }}">
            </div>
            <div class="form-group">
                <label for="postal_code">Code Postal</label>
                <input type="text" name="postal_code" id="postal_code" class="form-control" value="{{ optional($organization->address)->postal_code }}">
            </div>
            <button type="submit" class="btn btn-primary">Modifier</button>
        </form>
    </div>
@endsection
