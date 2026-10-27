<x-guest-layout>
    <x-slot name="title">Log in</x-slot>

    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Log in</h1>
    <p class="mt-1 text-sm text-slate-500">
        New here? <a href="{{ route('register') }}" class="font-semibold text-sky-700 hover:underline">Create an account</a>
    </p>

    <x-auth-session-status class="mt-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5" x-data>
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Password')" />
                @if (Route::has('password.request'))
                    <a class="text-sm text-slate-500 hover:text-slate-900" href="{{ route('password.request') }}">Forgot password?</a>
                @endif
            </div>
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <label for="remember_me" class="inline-flex items-center gap-2">
            <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-sky-700 focus:ring-sky-500" name="remember">
            <span class="text-sm text-slate-600">Keep me logged in</span>
        </label>

        <x-primary-button class="w-full justify-center py-3" data-loading="Logging in...">Log in</x-primary-button>
    </form>

    @if (app()->isLocal() || config('app.show_demo_accounts'))
        <!-- Demo accounts: one click fills the form (handy for graders and testing) -->
        <div class="mt-8 p-4 rounded-xl bg-sky-50 ring-1 ring-sky-100" x-data>
            <p class="text-sm font-semibold text-sky-900">Demo accounts</p>
            <p class="text-xs text-sky-800">All use the password <span class="font-semibold">password</span>. Click one to fill the form.</p>
            <div class="mt-3 grid grid-cols-2 gap-2">
                @foreach([
                    ['admin@lostfound.test', 'Campus Admin', 'Admin'],
                    ['budi@lostfound.test', 'Budi Santoso', 'User'],
                    ['siti@lostfound.test', 'Siti Rahma', 'User'],
                    ['andi@lostfound.test', 'Andi Pratama', 'User'],
                ] as [$email, $name, $role])
                    <button type="button"
                            @click="document.getElementById('email').value = @js($email); document.getElementById('password').value = 'password'; document.getElementById('password').focus()"
                            class="text-left px-3 py-2 rounded-lg bg-white ring-1 ring-sky-100 hover:ring-sky-300 transition">
                        <span class="block text-sm font-semibold text-slate-900">{{ $name }}</span>
                        <span class="block text-xs text-slate-500">{{ $role }}</span>
                    </button>
                @endforeach
            </div>
        </div>
    @endif
</x-guest-layout>
