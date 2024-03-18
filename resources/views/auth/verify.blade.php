@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Verifier adresse mail</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            Un lien de vérification a été envoyé par mail
                        </div>
                    @endif

                    Avant de continuer, veuillez vérifier vos mail.
                    Si vous n'aviez rien reçu
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">Appuyer ici pour envoyer un autre mail.</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
