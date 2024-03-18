@component('mail::message')

    # Bienvenue sur l'application de Elio Bteich

    Un compte a été créé pour vous avec les détails suivants :

    - Nom : {{ $user->name }}
    - Adresse e-mail : {{ $user->email }}
    - Mot de passe : {{ $password }}
    - Rôle : {{ $user->role->name }}

    Vous pouvez vous connecter en utilisant le mot de passe fourni.

    Merci de nous rejoindre !

@endcomponent
