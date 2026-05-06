<x-guest-layout>
    <div class="flex items-start justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 text-xs font-semibold text-gray-600">
                <span class="rounded-full bg-white px-3 py-1 ring-1 ring-black/10">Way Hitam Coffee</span>
                <span class="text-gray-300">•</span>
                <span class="text-gray-500">Admin Portal</span>
            </div>
            <h1 class="mt-3 text-3xl font-semibold tracking-tight text-gray-950 sm:text-4xl">Masuk Admin</h1>
            <div class="mt-2 text-sm leading-6 text-gray-600">Login untuk mengelola pesanan, stok, kasir, dan laporan.</div>
        </div>
        <div class="hidden rounded-2xl border border-black/10 bg-white p-3 shadow-sm sm:block">
            <x-heroicon-o-shield-check class="h-6 w-6 text-gray-900" />
        </div>
    </div>

    <div class="mt-6">
        <x-auth-session-status class="mb-4" :status="session('status')" />
    </div>

    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4" x-data="{ showPassword: false, loading: false }" @submit="loading = true">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-gray-700" />
            <div class="relative mt-2">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                    <x-heroicon-o-envelope class="h-5 w-5 text-gray-400" />
                </div>
                <x-text-input
                    id="email"
                    class="block w-full rounded-2xl border-gray-200 bg-white/90 pl-11 pr-4 text-gray-950 placeholder:text-gray-400 shadow-sm transition focus:border-red-600 focus:outline-none focus:ring-0 hover:border-gray-300"
                    type="email"
                    name="email"
                    :value="old('email')"
                    placeholder="admin@coffee.com"
                    required
                    autofocus
                    autocomplete="username"
                />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" class="text-gray-700" />
            <div class="relative mt-2">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                    <x-heroicon-o-lock-closed class="h-5 w-5 text-gray-400" />
                </div>
                <x-text-input
                    id="password"
                    class="block w-full rounded-2xl border-gray-200 bg-white/90 pl-11 pr-14 text-gray-950 placeholder:text-gray-400 shadow-sm transition focus:border-red-600 focus:outline-none focus:ring-0 hover:border-gray-300"
                    name="password"
                    placeholder="••••••••"
                    required
                    autocomplete="current-password"
                    x-bind:type="showPassword ? 'text' : 'password'"
                />
                <button
                    type="button"
                    class="absolute inset-y-0 right-0 flex items-center rounded-r-2xl px-4 text-gray-500 transition hover:text-gray-900"
                    @click="showPassword = !showPassword"
                    aria-label="Toggle password"
                >
                    <x-heroicon-o-eye class="h-5 w-5" x-show="!showPassword" />
                    <x-heroicon-o-eye-slash class="h-5 w-5" x-show="showPassword" />
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-red-600 to-red-700 px-5 py-4 text-sm font-black uppercase tracking-widest text-white shadow-lg shadow-red-600/25 transition hover:from-red-700 hover:to-red-800 active:scale-[0.99] disabled:cursor-not-allowed disabled:opacity-70" x-bind:disabled="loading">
            <x-heroicon-o-arrow-path class="h-5 w-5 animate-spin" x-show="loading" />
            <x-heroicon-o-arrow-right-on-rectangle class="h-5 w-5" x-show="!loading" />
            <span x-text="loading ? 'Memproses...' : 'Masuk'"></span>
        </button>

        <details class="rounded-2xl border border-black/10 bg-white p-4 text-xs text-gray-600 shadow-sm">
            <summary class="cursor-pointer select-none font-semibold text-gray-700">Akun demo</summary>
            <div class="mt-2">
                <span class="text-gray-600">Default admin:</span>
                <span class="font-semibold text-gray-900">admin@coffee.com</span>
                <span class="text-gray-300">/</span>
                <span class="font-semibold text-gray-900">password</span>
            </div>
        </details>
    </form>
</x-guest-layout>
