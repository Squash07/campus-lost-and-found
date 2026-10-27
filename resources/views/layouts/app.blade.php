<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- $title is a slot, already escaped where it was written --}}
    <title>@isset($title){!! $title !!} | @endisset{{ "Campus Lost & Found" }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    @include('layouts.partials.head')
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50 min-h-full flex flex-col">
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-[100] focus:px-4 focus:py-2 focus:rounded-lg focus:bg-white focus:shadow-lg focus:text-sky-700 font-semibold">Skip to content</a>

    @include('layouts.navigation')

    <!-- Page Heading -->
    @isset($header)
        <header class="bg-white border-b border-slate-200">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    <!-- Main Content -->
    <main id="main" class="flex-1">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-16 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-slate-500">
            <div class="flex items-center gap-2">
                <x-application-logo class="w-7 h-7" />
                <span class="font-semibold text-slate-700">Campus Lost & Found</span>
            </div>
            <p>Midterm project for Web Programming (EF234301), built with Laravel 12.</p>
            <a href="{{ route('items.index') }}" class="font-semibold hover:text-sky-700 transition">Browse items</a>
        </div>
    </footer>

    @include('layouts.partials.toasts')
    @include('layouts.partials.confirm-modal')
</body>
</html>
