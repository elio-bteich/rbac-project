@component('mail::message')
    # Notification de Changement de Rôle

    Bonjour {{ $user->name }},

    Votre rôle a été changé en "{{ $newRole->name }}".

    Cordialement,
    L'Équipe de Elio Bteich
@endcomponent
