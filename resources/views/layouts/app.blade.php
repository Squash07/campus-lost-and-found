<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Campus Lost & Found') }} - Student Community Portal</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0284c7',
                            600: '#0369a1',
                            700: '#075985',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50 min-h-full flex flex-col">
    @include('layouts.navigation')

    <!-- Flash Notifications -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pt-4">
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" class="mb-4 flex items-center justify-between p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-lg shadow-sm">
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm font-medium text-emerald-900">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="text-emerald-600 hover:text-emerald-800 text-sm font-bold">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" class="mb-4 flex items-center justify-between p-4 bg-rose-50 border-l-4 border-rose-500 rounded-r-lg shadow-sm">
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm font-medium text-rose-900">{{ session('error') }}</p>
                </div>
                <button @click="show = false" class="text-rose-600 hover:text-rose-800 text-sm font-bold">&times;</button>
            </div>
        @endif
    </div>

    <!-- Page Heading -->
    @isset($header)
        <header class="bg-white border-b border-slate-200">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    <!-- Main Content -->
    <main class="flex-1">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-16 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-slate-500">
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-sky-600 text-white font-bold text-xs">LF</span>
                <span class="font-semibold text-slate-700">Campus Lost & Found</span>
                <span>— ETS PWeb EF234301</span>
            </div>
            <div class="flex items-center space-x-4">
                <span>Laravel 12 (MVC)</span>
                <span>•</span>
                <span>4 Entities & 4 Foreign Keys</span>
                <span>•</span>
                <a href="{{ route('items.index') }}" class="hover:text-sky-600 transition">Browse Items</a>
            </div>
        </div>
    </footer>
</body>
</html>
