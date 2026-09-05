@extends('larazoul::layouts.app')

@section('title', 'Menus')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Menus</h1>
            <p class="mt-1 text-sm text-gray-500">Create navigation menus with multi-language items.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Create menu --}}
        <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 lg:col-span-1">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">New Menu</h2>

            <form action="{{ route('larazoul.menus.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" required
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Slug (optional)</label>
                    <input type="text" name="slug" placeholder="auto-generated"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm">
                </div>

                <button type="submit"
                        class="inline-flex items-center px-4 py-2 rounded-lg bg-brand-600 text-white text-sm font-medium hover:bg-brand-700">
                    Create Menu
                </button>
            </form>
        </section>

        {{-- Menus list --}}
        <section class="lg:col-span-2 space-y-6">
            @forelse ($menus as $menu)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 flex items-center justify-between">
                        <div>
                            <div class="font-semibold text-gray-900">{{ $menu->name }}</div>
                            <div class="text-xs text-gray-500">/{{ $menu->slug }}</div>
                        </div>

                        <form action="{{ route('larazoul.menus.destroy', $menu) }}" method="POST"
                              onsubmit="return confirm('Delete this menu?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-700 text-sm font-medium">Delete</button>
                        </form>
                    </div>

                    <div class="p-6">
                        @forelse ($menu->items as $item)
                            <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                                <div>
                                    <span class="font-medium text-gray-900">{{ $item->display_name }}</span>
                                    @if ($item->link)
                                        <span class="text-xs text-gray-400 ml-2">{{ $item->link }}</span>
                                    @endif
                                </div>
                                <form action="{{ route('larazoul.menus.items.destroy', $item) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs">Remove</button>
                                </form>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 mb-4">No items yet.</p>
                        @endforelse

                        <form action="{{ route('larazoul.menus.items.store', $menu) }}" method="POST"
                              class="mt-4 grid grid-cols-2 gap-3">
                            @csrf

                            <input type="text" name="name_en" placeholder="Name (EN)" required
                                   class="rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm">
                            <input type="text" name="name_ar" placeholder="Name (AR)"
                                   class="rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm">
                            <input type="text" name="link" placeholder="/admin/posts"
                                   class="rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm">
                            <input type="text" name="icon" placeholder="icon (optional)"
                                   class="rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm">

                            <button type="submit"
                                    class="col-span-2 inline-flex items-center px-4 py-2 rounded-lg bg-brand-600 text-white text-sm font-medium hover:bg-brand-700">
                                + Add item
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center text-sm text-gray-500">
                    No menus yet. Create one to get started.
                </div>
            @endforelse
        </section>
    </div>
@endsection