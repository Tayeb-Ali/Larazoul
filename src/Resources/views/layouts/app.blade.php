<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ larazoul_is_rtl() ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('larazoul::larazoul.title'))</title>

    {{-- Tailwind via CDN is fine for a starter panel; production apps can compile it. --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 text-gray-800 antialiased">
    <div class="min-h-screen flex flex-col">

        <nav class="bg-brand-900 text-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center space-x-4 {{ larazoul_is_rtl() ? 'space-x-reverse' : '' }}">
                        <a href="{{ route('larazoul.dashboard') }}" class="flex items-center space-x-2 {{ larazoul_is_rtl() ? 'space-x-reverse' : '' }}">
                            <span class="text-xl font-black tracking-tight">Lara<span class="text-amber-400">Zoul</span></span>
                        </a>

                        <nav class="hidden md:flex items-center space-x-1 {{ larazoul_is_rtl() ? 'space-x-reverse' : '' }}">
                            <a href="{{ route('larazoul.dashboard') }}"
                               class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('larazoul.dashboard') ? 'bg-brand-700 text-white' : 'text-brand-100 hover:bg-brand-700 hover:text-white' }}">
                                {{ __('larazoul::larazoul.dashboard') }}
                            </a>
                            <a href="{{ route('larazoul.modules.index') }}"
                               class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('larazoul.modules.*') ? 'bg-brand-700 text-white' : 'text-brand-100 hover:bg-brand-700 hover:text-white' }}">
                                {{ __('larazoul::larazoul.modules') }}
                            </a>
                            <a href="{{ route('larazoul.modules.transfer') }}"
                               class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('larazoul.modules.transfer') ? 'bg-brand-700 text-white' : 'text-brand-100 hover:bg-brand-700 hover:text-white' }}">
                                Transfer
                            </a>
                            <a href="{{ route('larazoul.menus.index') }}"
                               class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('larazoul.menus.*') ? 'bg-brand-700 text-white' : 'text-brand-100 hover:bg-brand-700 hover:text-white' }}">
                                Menus
                            </a>
                        </nav>
                    </div>

                    <div class="flex items-center space-x-3 {{ larazoul_is_rtl() ? 'space-x-reverse' : '' }}">
                        <div class="relative inline-flex rounded-md shadow-sm">
                            <a href="{{ route('larazoul.locale', 'en') }}"
                               class="px-3 py-1.5 rounded-l-md bg-brand-700 text-xs font-medium text-white hover:bg-brand-600 {{ app()->getLocale() === 'en' ? 'ring-2 ring-amber-400' : '' }}">EN</a>
                            <a href="{{ route('larazoul.locale', 'ar') }}"
                               class="px-3 py-1.5 rounded-r-md bg-brand-700 text-xs font-medium text-white hover:bg-brand-600 {{ app()->getLocale() === 'ar' ? 'ring-2 ring-amber-400' : '' }}">ع</a>
                        </div>

                        <form method="POST" action="{{ route('larazoul.logout') }}">
                            @csrf
                            <button type="submit" class="px-3 py-2 rounded-md text-sm font-medium text-brand-100 hover:bg-brand-700 hover:text-white">
                                {{ __('larazoul::larazoul.logout') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-1">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                @if (session('success'))
                    <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <footer class="bg-white border-t border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 text-sm text-gray-500">
                {{ __('larazoul::larazoul.tagline') }}
            </div>
        </footer>
    </div>

    @livewireScripts
</body>
</html>