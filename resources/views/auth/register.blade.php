<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-display font-bold text-white uppercase tracking-wide">
            Daftar Member
        </h2>
        <p class="text-slate-500 text-sm">Buat akun untuk memantau service Anda</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-slate-400 mb-1 uppercase tracking-wider text-xs">Nama Lengkap</label>
            <input id="name" class="block w-full bg-slate-950 border-slate-700 text-white rounded-none focus:border-orange-500 focus:ring-orange-500 py-3 px-4" 
                   type="text" name="name" :value="old('name')" required autofocus autocomplete="name" 
                   placeholder="Nama Anda" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label for="email" class="block text-sm font-medium text-slate-400 mb-1 uppercase tracking-wider text-xs">Email</label>
            <input id="email" class="block w-full bg-slate-950 border-slate-700 text-white rounded-none focus:border-orange-500 focus:ring-orange-500 py-3 px-4" 
                   type="email" name="email" :value="old('email')" required autocomplete="username" 
                   placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-slate-400 mb-1 uppercase tracking-wider text-xs">Password</label>
            <input id="password" class="block w-full bg-slate-950 border-slate-700 text-white rounded-none focus:border-orange-500 focus:ring-orange-500 py-3 px-4"
                   type="password" name="password" required autocomplete="new-password" 
                   placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label for="password_confirmation" class="block text-sm font-medium text-slate-400 mb-1 uppercase tracking-wider text-xs">Konfirmasi Password</label>
            <input id="password_confirmation" class="block w-full bg-slate-950 border-slate-700 text-white rounded-none focus:border-orange-500 focus:ring-orange-500 py-3 px-4"
                   type="password" name="password_confirmation" required autocomplete="new-password" 
                   placeholder="Ulangi password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-8">
            <a class="underline text-xs text-slate-500 hover:text-orange-500 transition" href="{{ route('login') }}">
                {{ __('Sudah punya akun?') }}
            </a>

            <button class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-8 transition uppercase tracking-widest text-xs border border-orange-600 shadow-lg hover:shadow-orange-900/50">
                {{ __('Daftar') }}
            </button>
        </div>
    </form>
</x-guest-layout>