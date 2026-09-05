@extends('larazoul::layouts.app')

@section('title', __('larazoul::larazoul.create_module'))

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('larazoul::larazoul.create_module') }}</h1>
            <p class="mt-1 text-sm text-gray-500">
                {{ $module ? 'Editing: ' . $module->name : 'Describe your module and build it in one click.' }}
            </p>
        </div>
        <a href="{{ route('larazoul.modules.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">
            &larr; Back
        </a>
    </div>

    @livewire('admin.module-wizard', ['moduleId' => $module?->id])
@endsection