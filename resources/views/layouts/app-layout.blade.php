<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <script src="{{asset('js/color-modes.js')}}"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <script src="{{ asset('js/color-mode.js') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body {{ $attributes->merge(['class' => 'default']) }}>



    {{ $slot }}


    @stack('scripts')
</body>

</html>