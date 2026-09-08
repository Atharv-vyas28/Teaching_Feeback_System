<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — {{ config('app.name', 'laraval') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    {{--
        daisyUI drawer: static sidebar from lg: up (lg:drawer-open), and a
        hamburger-toggled overlay drawer below lg:. This is the pre-baked
        responsive admin shell — generated apps re-emit this file with
        domain sidebar links but KEEP the drawer structure.
    --}}
    <div class="drawer lg:drawer-open min-h-screen bg-base-200">
        <input id="admin-drawer" type="checkbox" class="drawer-toggle" />

        <div class="drawer-content flex flex-col">
            {{-- Mobile top bar with hamburger (hidden on lg+) --}}
            <div class="navbar bg-base-100 border-b border-base-300 lg:hidden">
                <label for="admin-drawer" class="btn btn-square btn-ghost" aria-label="Open menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </label>
                <span class="font-semibold px-2">@yield('title', 'Admin')</span>
            </div>

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @hasSection('title')
                    <h1 class="text-2xl font-bold mb-6">@yield('title')</h1>
                @endif
                @yield('content')
            </main>
        </div>

        <div class="drawer-side z-40">
            <label for="admin-drawer" class="drawer-overlay" aria-label="Close menu"></label>
            <aside class="bg-neutral text-neutral-content min-h-full w-64 flex flex-col">
                <div class="px-5 py-4 text-lg font-bold border-b border-white/10">
                    {{ config('app.name', 'Admin') }}
                </div>
                <ul class="menu flex-1 px-2 py-3 gap-1">
                    {{-- Generated apps add one <li> per feature here. Keep the
                         pre-baked Dashboard link. Active state via request()->is(). --}}
                    <li>
                        <a href="/admin/dashboard" class="{{ request()->is('admin/dashboard') ? 'menu-active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                            Dashboard
                        </a>
                    </li>
                </ul>
                <form method="POST" action="/admin/logout" class="p-4 border-t border-white/10">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-block btn-ghost justify-start">Log out</button>
                </form>
            </aside>
        </div>
    </div>
</body>
</html>