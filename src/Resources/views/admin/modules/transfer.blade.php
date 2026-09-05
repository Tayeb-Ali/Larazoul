@extends('larazoul::layouts.app')

@section('title', 'Import / Export Modules')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Import / Export Modules</h1>
            <p class="mt-1 text-sm text-gray-500">Export any module as a portable .larazoul archive, or import one back.</p>
        </div>
        <a href="{{ route('larazoul.modules.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">
            &larr; Back
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Export --}}
        <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Export</h2>

            @forelse ($modules as $module)
                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                    <div>
                        <div class="font-medium text-gray-900">{{ $module->name }}</div>
                        <div class="text-xs text-gray-500">{{ $module->columns_count ?? $module->columns()->count() }} columns</div>
                    </div>
                    <a href="{{ route('larazoul.modules.export', $module) }}"
                       class="inline-flex items-center px-3 py-1.5 rounded-lg bg-brand-50 text-brand-700 text-sm font-medium hover:bg-brand-100">
                        Download
                    </a>
                </div>
            @empty
                <p class="text-gray-500 text-sm">No modules to export.</p>
            @endforelse
        </section>

        {{-- Import --}}
        <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Import</h2>

            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('larazoul.modules.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label for="file" class="block text-sm font-medium text-gray-700">.larazoul archive</label>
                    <input type="file" id="file" name="file" accept=".zip,.larazoul"
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-brand-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-brand-700 hover:file:bg-brand-100">
                </div>

                <button type="submit"
                        class="inline-flex items-center px-4 py-2 rounded-lg bg-brand-600 text-white text-sm font-medium hover:bg-brand-700">
                    Import Module
                </button>
            </form>
        </section>
    </div>
@endsection