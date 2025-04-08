<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{config('app.name')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{__('download.text')}}">

    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('favicons/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('favicons/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('favicons/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('favicons/site.webmanifest')}}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @googlefonts()
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
