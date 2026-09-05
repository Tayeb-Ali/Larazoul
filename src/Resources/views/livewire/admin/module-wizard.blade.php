<div class="space-y-8">
    {{-- =============================================================
         STEP 1: Module information
         ============================================================= --}}
    <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">1. Module Information</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label for="name" class="block text-sm font-medium text-gray-700">Module Name</label>
                <input type="text" id="name" wire:model="name" placeholder="e.g. Product, Post, Category"
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                <p class="mt-1 text-xs text-gray-400">Singular name. The table, model and controllers are generated automatically.</p>
            </div>

            <label class="inline-flex items-center cursor-pointer gap-3 rounded-lg border border-gray-200 p-4 hover:border-brand-500 transition">
                <input type="checkbox" wire:model="admin" class="h-4 w-4 text-brand-600 rounded">
                <span class="text-sm font-medium text-gray-700">Admin CRUD</span>
            </label>

            <label class="inline-flex items-center cursor-pointer gap-3 rounded-lg border border-gray-200 p-4 hover:border-brand-500 transition">
                <input type="checkbox" wire:model="api" class="h-4 w-4 text-brand-600 rounded">
                <span class="text-sm font-medium text-gray-700">API + Resources</span>
            </label>

            <label class="inline-flex items-center cursor-pointer gap-3 rounded-lg border border-gray-200 p-4 hover:border-brand-500 transition">
                <input type="checkbox" wire:model="front" class="h-4 w-4 text-brand-600 rounded">
                <span class="text-sm font-medium text-gray-700">Front-end views</span>
            </label>

            <label class="inline-flex items-center cursor-pointer gap-3 rounded-lg border border-gray-200 p-4 hover:border-brand-500 transition">
                <input type="checkbox" wire:model="seed" class="h-4 w-4 text-brand-600 rounded">
                <span class="text-sm font-medium text-gray-700">Generate Seeder</span>
            </label>

            <label class="inline-flex items-center cursor-pointer gap-3 rounded-lg border border-gray-200 p-4 hover:border-brand-500 transition">
                <input type="checkbox" wire:model="migrate" class="h-4 w-4 text-brand-600 rounded">
                <span class="text-sm font-medium text-gray-700">Run migration after build</span>
            </label>
        </div>
    </section>

    {{-- =============================================================
         STEP 2: Columns
         ============================================================= --}}
    <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">2. Columns</h2>
            <button type="button" wire:click="addColumn"
                    class="inline-flex items-center px-3 py-1.5 rounded-lg bg-brand-50 text-brand-700 text-sm font-medium hover:bg-brand-100">
                + Add column
            </button>
        </div>

        <div class="space-y-3">
            @foreach ($columns as $index => $column)
                <div class="flex items-end gap-3" wire:key="column-{{ $index }}">
                    <div class="flex-1">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Name</label>
                        <input type="text" wire:model="columns.{{ $index }}.name" placeholder="title"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm">
                        @error("columns.{$index}.name") <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="w-44">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Type</label>
                        <select wire:model="columns.{{ $index }}.type"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm">
                            @foreach ($types as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-44">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Input</label>
                        <select wire:model="columns.{{ $index }}.html_type"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm">
                            @foreach (['text','textarea','email','number','password','checkbox','select','date','time'] as $htmlType)
                                <option value="{{ $htmlType }}">{{ $htmlType }}</option>
                            @endforeach
                        </select>
                    </div>

                    <label class="inline-flex items-center gap-2 pb-2 cursor-pointer">
                        <input type="checkbox" wire:model="columns.{{ $index }}.nullable"
                               class="h-4 w-4 text-brand-600 rounded">
                        <span class="text-xs font-medium text-gray-600">Nullable</span>
                    </label>

                    <div class="flex items-center gap-1 pb-2">
                        <button type="button" wire:click="moveColumn({{ $index }}, -1)"
                                class="p-1.5 rounded text-gray-400 hover:text-gray-600 hover:bg-gray-100"
                                title="Move up">&uarr;</button>
                        <button type="button" wire:click="moveColumn({{ $index }}, 1)"
                                class="p-1.5 rounded text-gray-400 hover:text-gray-600 hover:bg-gray-100"
                                title="Move down">&darr;</button>
                        <button type="button" wire:click="removeColumn({{ $index }})"
                                class="p-1.5 rounded text-red-400 hover:text-red-600 hover:bg-red-50"
                                title="Remove">&times;</button>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- =============================================================
         ACTIONS
         ============================================================= --}}
    <div class="flex items-center justify-end gap-3">
        <button type="button" wire:click="saveAndGenerate"
                class="inline-flex items-center px-6 py-3 rounded-xl bg-brand-600 text-white text-sm font-semibold hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            Build Module
        </button>
    </div>
</div>