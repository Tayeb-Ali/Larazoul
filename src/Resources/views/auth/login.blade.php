@extends('larazoul::layouts.guest')

@section('title', __('larazoul::larazoul.login'))

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
        <div class="w-full max-w-md">
            <div class="text-center mb-6">
                <span class="text-3xl font-black text-gray-900">Lara<span class="text-amber-500">Zoul</span></span>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <h1 class="text-xl font-bold text-gray-900">{{ __('larazoul::larazoul.login') }}</h1>

                @if ($errors->any())
                    <div class="mt-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('larazoul.login.post') }}" class="mt-6 space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">{{ __('larazoul::larazoul.email') }}</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">{{ __('larazoul::larazoul.password') }}</label>
                        <input id="password" type="password" name="password" required
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm">
                    </div>

                    <button type="submit"
                            class="w-full inline-flex justify-center items-center px-4 py-2 rounded-lg bg-brand-600 text-white text-sm font-medium hover:bg-brand-700">
                        {{ __('larazoul::larazoul.login') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection