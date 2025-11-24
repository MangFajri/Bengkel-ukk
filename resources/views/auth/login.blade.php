<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-display font-bold text-white uppercase tracking-wide">
            Login Area
        </h2>
        <p class="text-slate-500 text-sm">Silakan masuk untuk melanjutkan</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-slate-400 mb-1 uppercase tracking-wider text-xs">Email</label>
            <input id="email" class="block w-full bg-slate-950 border-slate-700 text-white rounded-none focus:border-orange-500 focus:ring-orange-500 py-3 px-4" 
                   type="email" name="email" :value="old('email')" required autofocus autocomplete="username" 
                   placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-slate-400 mb-1 uppercase tracking-wider text-xs">Password</label>
            <input id="password" class="block w-full bg-slate-950 border-slate-700 text-white rounded-none focus:border-orange-500 focus:ring-orange-500 py-3 px-4"
                   type="password" name="password" required autocomplete="current-password" 
                   placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded bg-slate-950 border-slate-700 text-orange-600 shadow-sm focus:ring-orange-500" name="remember">
                <span class="ms-2 text-sm text-slate-400 hover:text-slate-300 cursor-pointer transition">{{ __('Ingat saya') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-8">
            @if (Route::has('password.request'))
                <a class="underline text-xs text-slate-500 hover:text-orange-500 transition" href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif

            <button class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-8 transition uppercase tracking-widest text-xs border border-orange-600 shadow-lg hover:shadow-orange-900/50">
                {{ __('Log in') }}
            </button>
        </div>
    </form>
</x-guest-layout>