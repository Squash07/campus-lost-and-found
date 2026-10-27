{{-- Standalone error layout: no navbar or database queries, so it still renders when something is broken --}}
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | Campus Lost & Found</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @include('layouts.partials.head')
</head>
<body class="font-sans antialiased text-slate-800 min-h-full flex flex-col items-center justify-center px-4 py-16 text-center">
    <a href="{{ url('/') }}" class="flex items-center gap-2 mb-12">
        <x-application-logo class="w-9 h-9" />
        <span class="font-extrabold text-lg text-slate-900">Campus Lost &amp; Found</span>
    </a>
    <p class="text-sm font-semibold text-sky-700">Error @yield('code')</p>
    <h1 class="mt-2 text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900">@yield('heading')</h1>
    <p class="mt-3 max-w-md text-slate-600">@yield('message')</p>
    <div class="mt-8 flex flex-wrap justify-center gap-3">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : url('/') }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold border border-slate-300 text-slate-700 hover:bg-white transition">Go back</a>
        <a href="{{ url('/') }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold bg-sky-700 text-white hover:bg-sky-800 transition">Browse items</a>
    </div>
</body>
</html>
