<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? config('app.name') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full bg-[#F8F7F4] text-gray-900 antialiased dark:bg-slate-950 dark:text-slate-100">
        @php($count = collect(data_get(session('cart', []), 'items', []))->sum('qty'))

        <div class="min-h-screen">
            <div class="fixed inset-0 -z-10">
                <div class="absolute inset-0 bg-[#F8F7F4] dark:bg-slate-950"></div>
                <div class="absolute -top-32 left-[-160px] h-[520px] w-[520px] rounded-full bg-[radial-gradient(circle_at_center,rgba(139,94,60,0.20),transparent_70%)] blur-2xl"></div>
                <div class="absolute -top-40 right-[-220px] h-[640px] w-[640px] rounded-full bg-[radial-gradient(circle_at_center,rgba(255,232,194,0.55),transparent_70%)] blur-2xl dark:bg-[radial-gradient(circle_at_center,rgba(139,94,60,0.18),transparent_70%)]"></div>
                <div class="absolute bottom-[-220px] left-[20%] h-[640px] w-[640px] rounded-full bg-[radial-gradient(circle_at_center,rgba(14,165,233,0.10),transparent_70%)] blur-2xl dark:bg-[radial-gradient(circle_at_center,rgba(99,102,241,0.10),transparent_70%)]"></div>
            </div>

            <div class="sticky top-0 z-40 border-b border-gray-200 bg-white/85 backdrop-blur dark:border-white/10 dark:bg-slate-950/70">
                <div class="mx-auto flex max-w-6xl items-center justify-between gap-3 px-4 py-3">
                    <a href="{{ route('landing') }}" class="group flex min-w-0 items-center gap-3">
                        <div class="grid h-10 w-10 place-items-center rounded-2xl bg-[#8B5E3C] text-white shadow-sm transition group-hover:shadow">
                            <x-heroicon-o-fire class="h-5 w-5" />
                        </div>
                        <div class="min-w-0 leading-tight">
                            <div class="truncate text-sm font-semibold tracking-wide text-gray-900 dark:text-slate-100">{{ $brand ?? 'CoffeeShop' }}</div>
                            <div class="truncate text-xs text-gray-500 dark:text-slate-400">UMKM Ordering System</div>
                        </div>
                    </a>

                    <div class="flex items-center gap-2">
                        <a
                            href="{{ route('cart.show') }}"
                            class="hidden items-center gap-2 rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm transition hover:bg-[#F8F7F4] active:scale-[0.99] dark:border-white/10 dark:bg-slate-900/60 dark:text-slate-100 dark:hover:bg-slate-900 md:inline-flex"
                        >
                            <x-heroicon-o-shopping-bag class="h-5 w-5 text-[#8B5E3C]" />
                            @if($count > 0)
                                <span class="ml-1 rounded-full bg-[#8B5E3C]/10 px-2 py-0.5 text-xs font-semibold text-[#8B5E3C] ring-1 ring-[#8B5E3C]/20">{{ $count }}</span>
                            @endif
                        </a>
                        <button
                            type="button"
                            class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white p-2 text-gray-700 shadow-sm transition hover:bg-[#F8F7F4] active:scale-[0.99] dark:border-white/10 dark:bg-slate-900/60 dark:text-slate-200 dark:hover:bg-slate-900"
                            @click="$store.ui.toggleDark()"
                        >
                            <x-heroicon-o-moon class="h-5 w-5" x-show="!$store.ui.dark" x-cloak />
                            <x-heroicon-o-sun class="h-5 w-5" x-show="$store.ui.dark" x-cloak />
                        </button>
                    </div>
                </div>
            </div>

            <main class="mx-auto max-w-6xl px-4 py-8">
                {{ $slot }}
            </main>

            <footer class="border-t border-gray-200 bg-white dark:border-white/10 dark:bg-slate-950">
                <div class="mx-auto max-w-6xl px-4 py-10">
                    <div class="grid gap-8 md:grid-cols-3">
                        <div>
                            <div class="text-sm font-semibold text-gray-900 dark:text-slate-100">{{ $settings['store_name'] ?? 'CoffeeShop UMKM' }}</div>
                            <div class="mt-2 text-sm text-gray-500 dark:text-slate-400">Pesan cepat dari meja. Bayar cash atau QRIS. Nikmati kopi premium.</div>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-slate-300">
                            <div class="font-semibold text-gray-900 dark:text-slate-100">Kontak</div>
                            <div class="mt-2 space-y-1">
                                <div>{{ ($settings['address'] ?? 'Alamat Toko') }}</div>
                                <div>WA: {{ ($settings['whatsapp'] ?? '08xxxxxxxxxx') }}</div>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-slate-300">
                            <div class="font-semibold text-gray-900 dark:text-slate-100">Jam Operasional</div>
                            <div class="mt-2 space-y-1 text-gray-500 dark:text-slate-400">
                                <div>Senin–Minggu</div>
                                <div>08:00 – 22:00</div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-10 flex flex-col gap-2 border-t border-gray-200 pt-6 text-xs text-gray-500 dark:border-white/10 dark:text-slate-400 md:flex-row md:items-center md:justify-between">
                        <div>&copy; {{ date('Y') }} CoffeeShop UMKM Ordering System</div>
                        <div class="flex items-center gap-4">
                            <a href="{{ route('login') }}" class="font-semibold text-gray-700 dark:text-slate-200">Admin Login</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>

        <a
            href="{{ route('cart.show') }}"
            class="fixed bottom-5 right-5 z-40 inline-flex items-center gap-2 rounded-2xl bg-[#8B5E3C] px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-black/10 transition hover:bg-[#6F4A2D] active:scale-[0.99] md:hidden"
        >
            <x-heroicon-o-shopping-bag class="h-5 w-5" />
            @if($count > 0)
                <span class="rounded-full bg-white/20 px-2 py-0.5 text-xs">{{ $count }}</span>
            @endif
        </a>

        <x-toast-stack />
        @if(session('toast'))
            <script>
                window.addEventListener('load', () => {
                    const toast = @json(session('toast'));
                    window.Alpine?.store('toast')?.push(toast.message, toast.type);
                });
            </script>
        @endif
        @stack('scripts')
    </body>
</html>
