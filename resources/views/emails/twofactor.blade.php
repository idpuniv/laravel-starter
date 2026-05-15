@component('mail::message')
# Code de vérification

Bonjour {{ $user->name }},  

Votre code de vérification pour accéder à votre compte est :

# {{ $code }}

Il est valide pendant 5 minutes.

Merci,<br>
{{ config('app.name') }}
@endcomponent