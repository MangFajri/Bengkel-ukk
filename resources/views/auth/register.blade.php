<x-guest-layout>
    <h2 class="text-2xl font-display font-bold text-white uppercase mb-6 text-center">Buat Akun Baru</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-slate-400 mb-1 uppercase tracking-wider">Nama Lengkap</label>
            <input id="name" class="block w-full bg-slate-950 border-slate-700 text-white rounded-none focus:border-orange-500 focus:ring-orange-500 py-3" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label for="email" class="block text-sm font-medium text-slate-400 mb-1 uppercase tracking-wider">Email</label>
            <input id="email" class="block w-full bg-slate-950 border-slate-700 text-white rounded-none focus:border-orange-500 focus:ring-orange-500 py-3" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-slate-400 mb-1 uppercase tracking-wider">Password</label>
            <input id="password" class="block w-full bg-slate-950 border-slate-700 text-white rounded-none focus:border-orange-500 focus:ring-orange-500 py-3"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label for="password_confirmation" class="block text-sm font-medium text-slate-400 mb-1 uppercase tracking-wider">Konfirmasi Password</label>
            <input id="password_confirmation" class="block w-full bg-slate-950 border-slate-700 text-white rounded-none focus:border-orange-500 focus:ring-orange-500 py-3"
                            type="password"
                            name="password_confirmation"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-8">
            <a class="underline text-sm text-slate-500 hover:text-orange-500 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                Sudah punya akun?
            </a>

            <button class="bg-orange-600 hover:bg-orange-700 text-slate-900 font-bold py-3 px-6 rounded-none transition uppercase tracking-widest text-xs border border-orange-600 hover:text-white">
                Daftar
            </button>
        </div>
    </form>
</x-guest-layout>