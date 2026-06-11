<x-mail::message>
# Bienvenue {{ $user->name }}

Merci de vous être inscrit. Veuillez cliquer sur le bouton ci-dessous pour vérifier votre compte.

<x-mail::button :url="$url" color="success">
Vérifier mon compte
</x-mail::button>

Merci,<br>
{{ config('app.name') }}
</x-mail::message>