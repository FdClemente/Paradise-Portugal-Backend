@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-white via-gray-100 to-gray-50 flex items-center justify-center px-6 py-16">
        <div class="max-w-5xl w-full text-center bg-white rounded-3xl shadow-2xl p-10">
            <img src="{{ asset('images/logo-small.png') }}" draggable="false" alt="Paradise Portugal" class="mx-auto mb-8 w-40">
            <div class="justify-start">
                {!! $privacy['en'] !!}
            </div>
        </div>
    </div>

@endsection
