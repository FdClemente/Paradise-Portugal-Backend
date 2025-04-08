@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-white via-gray-100 to-gray-50 flex items-center justify-center px-6 py-16">
        <div class="max-w-5xl w-full text-center bg-white rounded-3xl shadow-2xl p-10">
            <img src="{{ asset('images/logo-small.png') }}" draggable="false" alt="Paradise Portugal" class="mx-auto mb-8 w-40">

            <h1 class="text-3xl sm:text-4xl font-extrabold text-[#454545] mb-4">{{ __('download.title') }}</h1>
            <p class="text-[#454545] text-lg mb-10">{{ __('download.text') }}</p>

            <div class="flex flex-col sm:flex-row justify-center items-center gap-6 mb-12">
                <a href="#"
                   class="w-full sm:w-60 bg-black text-white py-4 rounded-xl flex items-center justify-center gap-2 shadow hover:scale-105 transition-transform">
                    <x-grommet-apple-app-store class="w-6 h-6" />
                    <span class="text-lg font-medium">App Store</span>
                </a>

                <a href="#"
                   class="w-full sm:w-60 bg-green-600 text-white py-4 rounded-xl flex items-center justify-center gap-2 shadow hover:scale-105 transition-transform">
                    <x-grommet-google-play class="w-6 h-6" />
                    <span class="text-lg font-medium">Google Play</span>
                </a>
            </div>
        </div>
    </div>

@endsection
