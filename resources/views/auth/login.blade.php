<x-guest-layout>
    <div class="flex items-start justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="grid h-11 w-11 place-items-center rounded-2xl bg-coffee-600/80 text-white ring-1 ring-white/10">
                <x-heroicon-o-fire class="h-6 w-6" />
            </div>
            <div>
                <div class="inline-flex items-center gap-2 text-xs font-semibold text-white/70">
                    <span class="rounded-full bg-white/5 px-3 py-1 ring-1 ring-white/10">CoffeeShop</span>
                    <span class="text-white/40">•</span>
                    <span>Admin Portal</span>
                </div>
                <h1 class="mt-2 text-3xl font-semibold tracking-tight text-white">Masuk Admin</h1>
                <div class="mt-2 text-sm leading-6 text-white/60">Kelola pesanan, stok, kasir POS, dan laporan dalam satu dashboard.</div>
            </div>
        </div>
        <div class="hidden rounded-2xl bg-white/5 p-3 ring-1 ring-white/10 sm:block">
            <x-heroicon-o-shield-check class="h-6 w-6 text-cream-200" />
        </div>
    </div>

    <div class="mt-6">
        <x-auth-session-status class="mb-4" :status="session('status')" />
    </div>

    <form method="POST" action="{{ route('login') }}" class="mt-2 space-y-4" x-data="{ showPassword: false }">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-white/70" />
            <div class="relative mt-2">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                    <x-heroicon-o-envelope class="h-5 w-5 text-white/40" />
                </div>
                <x-text-input
                    id="email"
                    class="block w-full rounded-2xl border-white/10 bg-white/5 pl-11 pr-4 text-white placeholder:text-white/30 focus:border-cream-200/40 focus:ring-0"
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
            <x-input-label for="password" :value="__('Password')" class="text-white/70" />
            <div class="relative mt-2">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                    <x-heroicon-o-lock-closed class="h-5 w-5 text-white/40" />
                </div>
                <x-text-input
                    id="password"
                    class="block w-full rounded-2xl border-white/10 bg-white/5 pl-11 pr-12 text-white placeholder:text-white/30 focus:border-cream-200/40 focus:ring-0"
                    name="password"
                    placeholder="••••••••"
                    required
                    autocomplete="current-password"
                    x-bind:type="showPassword ? 'text' : 'password'"
                />
                <button
                    type="button"
                    class="absolute inset-y-0 right-0 flex items-center rounded-r-2xl px-4 text-xs font-semibold text-white/60 transition hover:text-white"
                    @click="showPassword = !showPassword"
                    aria-label="Toggle password"
                >
                   
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

       

        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-cream-200 px-5 py-3 text-sm font-semibold text-gray-950 shadow-sm shadow-black/20 transition hover:bg-cream-100">
            <x-heroicon-o-arrow-right-on-rectangle class="h-5 w-5" />
            <span>{{ __('Log in') }}</span>
        </button>

        <details class="rounded-2xl border border-white/10 bg-white/5 p-4 text-xs text-white/60">
            <summary class="cursor-pointer select-none font-semibold text-white/70">Akun demo</summary>
            <div class="mt-2">
                <span class="text-white/60">Default admin:</span>
                <span class="font-semibold text-white">admin@coffee.com</span>
                <span class="text-white/40">/</span>
                <span class="font-semibold text-white">password</span>
            </div>
        </details>
    </form>
</x-guest-layout>
