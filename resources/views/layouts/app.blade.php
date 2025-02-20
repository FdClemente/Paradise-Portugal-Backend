<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{config('app.name')}}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="The Science of Happiness">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="text-[#454545] bg-[#F6F6F6] h-[100dvh] w-[100vw]">
@yield('content')

@isset($slot)
    {{ $slot }}
@endisset

@livewireScripts
@stack('scripts')
</body>

</html>
