@extends('larazoul::layouts.app')

@section('title', __('larazoul::larazoul.dashboard'))

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('larazoul::larazoul.dashboard') }}</h1>
            <p class="mt-1 text-sm text-gray-500">{{ __('larazoul::larazoul.welcome') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="text-sm font-medium text-gray-500">{{ __('larazoul::larazoul.modules') }}</div>
            <div class="mt-2 text-3xl font-bold text-gray-900">{{ \Larazoul\Larazoul\Models\Module::count() }}</div>
            <a href="{{ route('larazoul.modules.index') }}" class="mt-3 inline-block text-sm font-medium text-brand-600 hover:text-brand-700">
                {{ __('larazoul::larazoul.modules_index') }} &rarr;
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="text-sm font-medium text-gray-500">{{ __('larazoul::larazoul.create_module') }}</div>
            <div class="mt-2 text-3xl font-bold text-gray-900">+</div>
            <a href="{{ route('larazoul.modules.create') }}" class="mt-3 inline-block text-sm font-medium text-brand-600 hover:text-brand-700">
                {{ __('larazoul::larazoul.create_module') }} &rarr;
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="text-sm font-medium text-gray-500">Tailwind</div>
            <div class="mt-2 text-3xl font-bold text-gray-900">Blade</div>
            <div class="mt-3 inline-block text-sm font-medium text-brand-600">Livewire</div>
        </div>
    </div>
@endsection