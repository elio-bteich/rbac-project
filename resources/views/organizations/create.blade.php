@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Ajouter une nouvelle structure</h1>
        <form action="{{ route('organizations.store') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="name">Nom</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Numéro de téléphone</label>
                <input type="tel" name="phone_number" id="phone_number" class="form-control">
            </div>
            <!-- Add address fields -->
            <div class="form-group">
                <label for="street">Rue</label>
                <input type="text" name="street" id="street" class="form-control">
            </div>
            <div class="form-group">
                <label for="city">Ville</label>
                <input type="text" name="city" id="city" class="form-control">
            </div>
            <div class="form-group">
                <label for="postal_code">Code Postal</label>
                <input type="text" name="postal_code" id="postal_code" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Ajoute</button>
        </form>
    </div>
@endsection
