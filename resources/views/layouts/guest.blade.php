<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CoffeeShop UMKM Ordering System') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-[#0b0b0c] text-gray-100">
            <div class="fixed inset-0 -z-10 bg-[radial-gradient(1200px_600px_at_10%_-10%,rgba(200,151,99,0.35),transparent),radial-gradient(900px_500px_at_90%_0%,rgba(255,232,194,0.22),transparent),radial-gradient(900px_500px_at_40%_110%,rgba(61,43,28,0.35),transparent)]"></div>

            <div class="mx-auto flex min-h-screen max-w-6xl items-stretch px-4 py-8">
                <div class="hidden w-full max-w-md flex-col justify-between rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl shadow-black/30 backdrop-blur lg:flex">
                    <div class="flex items-center gap-3">
                        <div class="grid h-12 w-12 place-items-center rounded-2xl bg-coffee-600/80 text-white ring-1 ring-white/10">
                            <x-heroicon-o-fire class="h-7 w-7" />
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-white">CoffeeShop Admin</div>
                            <div class="text-sm text-white/60">UMKM Ordering System</div>
                        </div>
                    </div>

                    <div class="mt-10">
                        <div class="text-3xl font-semibold tracking-tight text-white">Kelola pesanan & stok dengan cepat.</div>
                        <div class="mt-4 text-sm leading-6 text-white/70">
                            Dashboard premium untuk admin: pesanan realtime, POS kasir, QR meja, pembayaran, export Excel/PDF.
                        </div>
                        <div class="mt-6 grid gap-3">
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-white/70">Pesanan → update status → print struk</div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-white/70">Stok masuk/keluar + histori</div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-white/70">Laporan omzet + export</div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-xs text-white/50">
                        <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 rounded-xl bg-white/5 px-3 py-2 text-white/70 ring-1 ring-white/10 transition hover:bg-white/10 hover:text-white">
                            <x-heroicon-o-arrow-left class="h-4 w-4" />
                            <span>Kembali ke landing</span>
                        </a>
                    </div>
                </div>

                <div class="flex w-full items-center justify-center lg:justify-end">
                    <div class="w-full max-w-md">
                        <div class="flex items-center justify-between lg:hidden">
                            <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 rounded-xl bg-white/5 px-3 py-2 text-sm font-semibold text-white ring-1 ring-white/10 transition hover:bg-white/10">
                                <x-heroicon-o-arrow-left class="h-5 w-5" />
                                <span>Landing</span>
                            </a>
                        </div>

                        <div class="mt-6 overflow-hidden rounded-3xl border border-white/10 bg-white/5 p-6 shadow-2xl shadow-black/30 backdrop-blur lg:mt-0">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <x-toast-stack />
        @if(session('toast'))
            <script>
                window.addEventListener('load', () => {
                    const toast = @json(session('toast'));
                    window.Alpine?.store('toast')?.push(toast.message, toast.type);
                });
            </script>
        @endif
    </body>
</html>
