@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-white via-gray-100 to-gray-50 px-4 py-16">
        <div class="w-full max-w-xl bg-white rounded-3xl shadow-2xl p-10">
            <h2 class="text-3xl font-bold text-center text-[#454545] mb-6">Reset Password</h2>

            <form action="{{ route('password.reset.store') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="text-left">
                    <label for="email_address" class="block text-sm font-medium text-gray-700 mb-1">E-Mail Address</label>
                    <input type="text" id="email_address" class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-100 text-gray-700" name="email_label" readonly disabled value="{{ $email }}" required>
                    @if($errors->has('email'))
                        <p class="text-red-600 text-sm mt-1">{{ $errors->first('email') }}</p>
                    @endif
                </div>

                <div class="text-left">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input type="password" id="password" class="w-full border border-gray-300 rounded-lg px-4 py-2" name="password" required autofocus>
                    @if($errors->has('password'))
                        <p class="text-red-600 text-sm mt-1">{{ $errors->first('password') }}</p>
                    @endif
                </div>

                <div class="text-left">
                    <label for="password-confirm" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" id="password-confirm" class="w-full border border-gray-300 rounded-lg px-4 py-2" name="password_confirmation" required>
                    @if ($errors->has('password_confirmation'))
                        <p class="text-red-600 text-sm mt-1">{{ $errors->first('password_confirmation') }}</p>
                    @endif
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-[#42B7BA] text-white py-3 rounded-xl font-semibold hover:opacity-90 transition">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
