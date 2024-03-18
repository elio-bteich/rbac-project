@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Contact
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('contacts.update', $contact->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="name">Nom</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $contact->name }}" required>

                                @error('name')

                                    @include('alerts.error-message')

                                @enderror
                            </div>

                            <div class="form-group my-3">
                                <label for="email">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ $contact->email }}" required>

                                @error('email')

                                    @include('alerts.error-message')

                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="phone_number">Numéro de téléphone</label>
                                <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ $contact->phone_number }}">

                                @error('phone_number')

                                    @include('alerts.error-message')

                                @enderror
                            </div>



                            <button type="submit" class="btn btn-primary">Modifier Contact</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
