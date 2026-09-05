@extends('larazoul::layouts.app')

@section('title', __('larazoul::larazoul.modules_index'))

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('larazoul::larazoul.modules_index') }}</h1>
            <p class="mt-1 text-sm text-gray-500">{{ __('larazoul::larazoul.tagline') }}</p>
        </div>
        <a href="{{ route('larazoul.modules.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand-600 text-white text-sm font-medium hover:bg-brand-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('larazoul::larazoul.create_module') }}
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('larazoul::larazoul.modules') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Columns</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Options</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($modules as $module)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $module->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $module->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $module->columns_count }}</td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex flex-wrap gap-1">
                                @if ($module->admin)   <span class="px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 text-xs font-medium">Admin</span> @endif
                                @if ($module->api)     <span class="px-2 py-0.5 rounded-full bg-green-50 text-green-700 text-xs font-medium">API</span> @endif
                                @if ($module->website) <span class="px-2 py-0.5 rounded-full bg-purple-50 text-purple-700 text-xs font-medium">Front</span> @endif
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $module->created_at?->diffForHumans() }}</td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                            <a href="{{ route('larazoul.modules.create', $module) }}" class="text-brand-600 hover:text-brand-700 font-medium">Edit</a>

                            <form action="{{ route('larazoul.modules.build', $module) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-700 font-medium ml-2">Build</button>
                            </form>

                            <form action="{{ route('larazoul.modules.destroy', $module) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Delete this module?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700 font-medium ml-2">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">
                            {{ __('larazoul::larazoul.modules_index') }} — no modules yet.
                            <a href="{{ route('larazoul.modules.create') }}" class="text-brand-600 font-medium">Create one</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $modules->links() }}
    </div>
@endsection