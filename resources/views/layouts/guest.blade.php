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
<body class="font-sans text-slate-800 antialiased min-h-screen grid lg:grid-cols-2">
    <!-- Brand panel (large screens) -->
    <aside class="hidden lg:flex flex-col justify-between bg-sky-900 text-white p-12">
        <a href="{{ route('items.index') }}" class="flex items-center gap-3">
            <x-application-logo class="w-10 h-10" />
            <span class="font-extrabold text-xl">Campus Lost &amp; Found</span>
        </a>
        <div class="max-w-md">
            <p class="text-3xl font-extrabold leading-tight">Every day, something gets left behind in a lecture hall.</p>
            <p class="mt-4 text-sky-100">Post what you lost or found, check claims with a simple proof, and hand items back to their owners.</p>
        </div>
        <p class="text-sm text-sky-200">Web Programming (EF234301) midterm project</p>
    </aside>

    <main class="flex flex-col justify-center items-center px-4 py-12 sm:px-8">
        <a href="{{ route('items.index') }}" class="lg:hidden flex items-center gap-2 mb-8">
            <x-application-logo class="w-9 h-9" />
            <span class="font-extrabold text-lg text-slate-900">Campus Lost &amp; Found</span>
        </a>

        <div class="w-full max-w-md">
            {{ $slot }}
        </div>

        <a href="{{ route('items.index') }}" class="mt-8 text-sm font-semibold text-slate-500 hover:text-slate-900">Browse items without an account</a>
    </main>

    @include('layouts.partials.toasts')
</body>
</html>
