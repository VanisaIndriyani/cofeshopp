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
    <body class="h-full bg-white text-gray-900 antialiased dark:bg-slate-950 dark:text-slate-100">
        @php($count = collect(data_get(session('cart', []), 'items', []))->sum('qty'))

        <div class="min-h-screen">
            <!-- Pure Clean Background -->
            <div class="fixed inset-0 -z-10 bg-[#FAFAFA] dark:bg-slate-950"></div>

            <div class="sticky top-0 z-40 border-b border-gray-100 bg-white/80 backdrop-blur-xl dark:border-white/5 dark:bg-slate-950/80">
                <div class="mx-auto flex max-w-6xl items-center justify-between gap-3 px-4 py-3">
                    <a href="{{ route('landing') }}" class="group flex min-w-0 items-center gap-3">
                        <div class="relative h-11 w-11 overflow-hidden rounded-2xl bg-gradient-to-br from-red-600 to-yellow-500 p-0.5 shadow-lg transition group-hover:scale-105">
                            <div class="h-full w-full overflow-hidden rounded-[0.85rem] bg-white">
                                <img src="{{ asset('img/logo.jpeg') }}" alt="Logo" class="h-full w-full object-cover">
                            </div>
                        </div>
                        <div class="min-w-0 leading-tight">
                            <div class="truncate text-sm font-extrabold tracking-tight text-gray-950 dark:text-white">{{ $brand ?? 'Way Hitam Coffee' }}</div>
                            <div class="truncate text-[10px] font-bold uppercase tracking-widest text-red-600">UMKM Ordering System</div>
                        </div>
                    </a>

                    <div class="flex items-center gap-2">
                        <a
                            href="{{ route('cart.show') }}"
                            class="hidden items-center gap-2 rounded-xl bg-red-600 px-4 py-2.5 text-xs font-bold text-white shadow-lg shadow-red-600/20 transition hover:bg-red-700 active:scale-95 dark:shadow-none md:inline-flex"
                        >
                            <x-heroicon-o-shopping-bag class="h-4 w-4" />
                            <span>Keranjang</span>
                            @if($count > 0)
                                <span class="ml-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-yellow-400 px-1.5 text-[10px] font-black text-red-950 ring-2 ring-red-600">{{ $count }}</span>
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

                        <div class="md:hidden">
                            <a href="{{ route('cart.show') }}" class="relative flex h-10 w-10 items-center justify-center rounded-xl bg-gray-50 text-gray-950 ring-1 ring-gray-200 dark:bg-slate-900 dark:text-white dark:ring-white/10">
                                <x-heroicon-o-shopping-bag class="h-5 w-5" />
                                @if($count > 0)
                                    <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-600 px-1.5 text-[10px] font-black text-white ring-2 ring-white">{{ $count }}</span>
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <main class="mx-auto max-w-6xl px-4 py-8">
                {{ $slot }}
            </main>

            <footer class="relative mt-48 bg-white border-t border-gray-100 dark:bg-slate-950 dark:border-white/5" style="font-family: 'Figtree', sans-serif;">
                <div class="mx-auto max-w-6xl px-5 sm:px-7 lg:px-10 pt-28 pb-20">
                    <div class="overflow-x-auto">
                        <div class="min-w-[920px] grid grid-cols-4 gap-16 lg:gap-24">
                        
                        <!-- Brand Column -->
                        <div class="space-y-8">
                            <div class="flex flex-col gap-6">
                                <a href="{{ route('landing') }}" class="inline-flex items-center gap-3">
                                    <div class="h-14 w-14 overflow-hidden rounded-2xl bg-white p-1 shadow-lg ring-1 ring-gray-100">
                                        <img src="{{ asset('img/logo.jpeg') }}" alt="Logo" class="h-full w-full object-cover rounded-xl">
                                    </div>
                                    <h2 class="text-2xl font-black tracking-tighter text-gray-950 dark:text-white uppercase leading-none">Way Hitam</h2>
                                </a>
                                <p class="text-sm leading-relaxed text-gray-500 dark:text-slate-400 font-medium">
                                    Menghadirkan pengalaman ngopi digital terbaik dengan cita rasa autentik di setiap cangkir.
                                </p>
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="https://www.instagram.com/coffee_way_hitam/" target="_blank" class="flex h-11 w-11 items-center justify-center rounded-xl bg-gray-50 text-gray-400 transition-all hover:bg-red-600 hover:text-white dark:bg-slate-900">
                                    <x-heroicon-s-camera class="h-5 w-5" />
                                </a>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['whatsapp'] ?? '') }}" target="_blank" class="flex h-11 w-11 items-center justify-center rounded-xl bg-gray-50 text-gray-400 transition-all hover:bg-green-600 hover:text-white dark:bg-slate-900">
                                    <x-heroicon-s-phone class="h-5 w-5" />
                                </a>
                            </div>
                        </div>

                        <!-- Navigation -->
                        <div class="space-y-8">
                            <h4 class="text-[11px] font-black uppercase tracking-[0.2em] text-red-600">Navigasi</h4>
                            <nav class="flex flex-col gap-4">
                                <a href="{{ route('landing') }}" class="text-sm font-bold text-gray-600 transition hover:text-red-600 dark:text-slate-400 dark:hover:text-white">Beranda</a>
                                <a href="{{ route('table.menu', ['code' => 'A1']) }}" class="text-sm font-bold text-gray-600 transition hover:text-red-600 dark:text-slate-400 dark:hover:text-white">Daftar Menu</a>
                                <a href="{{ route('cart.show') }}" class="text-sm font-bold text-gray-600 transition hover:text-red-600 dark:text-slate-400 dark:hover:text-white">Keranjang Belanja</a>
                            </nav>
                        </div>

                        <!-- Contact -->
                        <div class="space-y-8">
                            <h4 class="text-[11px] font-black uppercase tracking-[0.2em] text-red-600">Lokasi</h4>
                            <div class="space-y-4">
                                <div class="flex items-start gap-3">
                                    <x-heroicon-s-map-pin class="h-5 w-5 text-yellow-500 shrink-0" />
                                    <span class="text-sm font-bold text-gray-600 dark:text-slate-400 leading-relaxed">
                                        {{ ($settings['address'] ?? 'Kodam II Sriwijaya, Palembang') }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <x-heroicon-s-phone class="h-5 w-5 text-yellow-500 shrink-0" />
                                    <span class="text-sm font-bold text-gray-600 dark:text-slate-400">
                                        {{ ($settings['whatsapp'] ?? '08xxxxxxxxxx') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Operational -->
                        <div class="space-y-8">
                            <h4 class="text-[11px] font-black uppercase tracking-[0.2em] text-red-600">Jam Buka</h4>
                            <div class="rounded-3xl bg-gray-50 p-6 dark:bg-slate-900/50 ring-1 ring-gray-100 dark:ring-white/5">
                                <div class="flex items-center gap-3 mb-3">
                                    <x-heroicon-s-clock class="h-5 w-5 text-red-600" />
                                    <span class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-widest">Senin – Minggu</span>
                                </div>
                                <div class="text-xl font-black text-red-600">08:00 – 22:00</div>
                            </div>
                        </div>
                        </div>
                    </div>

                    <!-- Bottom Bar -->
                    <div class="mt-20 pt-10 border-t border-gray-100 dark:border-white/5">
                        <div class="overflow-x-auto">
                            <div class="min-w-[920px] flex items-center justify-between gap-6">
                                <p class="text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest">
                                    &copy; {{ date('Y') }} Way Hitam Coffee.
                                </p>
                                <div class="flex items-center gap-6">
                                    <a href="{{ route('login') }}" class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 hover:text-red-600 transition-colors">Login Admin</a>
                                    <div class="h-4 w-px bg-gray-100 dark:bg-white/5"></div>
                                    <div class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 flex items-center gap-2">
                                        Built for Palembang <x-heroicon-s-heart class="h-4 w-4 text-red-600 animate-pulse" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>

        <a
            href="{{ route('cart.show') }}"
            class="fixed bottom-5 right-5 z-40 inline-flex items-center gap-2 rounded-2xl bg-gray-950 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-black/10 transition hover:bg-black active:scale-[0.99] md:hidden dark:bg-white dark:text-gray-950"
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
