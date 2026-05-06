<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Way Hitam Coffee Ordering System') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full bg-[#FAFAFA] text-gray-900 antialiased">
        <div class="min-h-screen">
            <div class="fixed inset-0 -z-10 bg-[radial-gradient(1200px_700px_at_10%_-10%,rgba(225,29,72,0.12),transparent),radial-gradient(1000px_650px_at_90%_0%,rgba(234,179,8,0.12),transparent),linear-gradient(to_bottom,rgba(255,255,255,0.9),rgba(250,250,250,1))]"></div>

            <div class="mx-auto grid min-h-screen max-w-6xl items-center gap-8 px-4 py-10 lg:grid-cols-12 lg:gap-10">
                <div class="hidden lg:col-span-6 lg:block">
                    <div class="relative overflow-hidden rounded-[2.75rem] border border-black/5 bg-gradient-to-br from-gray-950 to-gray-900 p-10 shadow-2xl shadow-black/15">
                        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(700px_500px_at_10%_10%,rgba(225,29,72,0.22),transparent),radial-gradient(700px_500px_at_90%_30%,rgba(234,179,8,0.18),transparent)]"></div>
                        <div class="relative flex items-center gap-3">
                            <div class="h-12 w-12 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-white/10">
                                <img src="{{ asset('img/logo.jpeg') }}" alt="Logo" class="h-full w-full object-cover">
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-white">Way Hitam Coffee</div>
                                <div class="text-sm text-white/60">Admin System</div>
                            </div>
                        </div>

                        <div class="relative mt-12">
                            <div class="text-4xl font-semibold tracking-tight text-white">
                                Admin dashboard yang rapi & cepat.
                            </div>
                            <div class="mt-4 text-sm leading-6 text-white/70">
                                Pantau pesanan realtime, kelola stok, kasir POS, dan laporan dalam satu tempat.
                            </div>

                            <div class="mt-8 grid gap-3">
                                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-white/80 backdrop-blur">
                                    <div class="flex items-start gap-3">
                                        <div class="mt-0.5 rounded-xl bg-white/10 p-2 ring-1 ring-white/10">
                                            <x-heroicon-o-bolt class="h-5 w-5 text-white" />
                                        </div>
                                        <div>
                                            <div class="font-semibold text-white">Pesanan realtime</div>
                                            <div class="mt-1 text-xs text-white/60">Update status dan proses cepat dari satu layar.</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-white/80 backdrop-blur">
                                    <div class="flex items-start gap-3">
                                        <div class="mt-0.5 rounded-xl bg-white/10 p-2 ring-1 ring-white/10">
                                            <x-heroicon-o-archive-box class="h-5 w-5 text-white" />
                                        </div>
                                        <div>
                                            <div class="font-semibold text-white">Stok & produk</div>
                                            <div class="mt-1 text-xs text-white/60">Kelola harga, foto, ketersediaan, dan kategori.</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-white/80 backdrop-blur">
                                    <div class="flex items-start gap-3">
                                        <div class="mt-0.5 rounded-xl bg-white/10 p-2 ring-1 ring-white/10">
                                            <x-heroicon-o-chart-bar-square class="h-5 w-5 text-white" />
                                        </div>
                                        <div>
                                            <div class="font-semibold text-white">Laporan</div>
                                            <div class="mt-1 text-xs text-white/60">Ringkas omzet dan pantau performa harian.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="relative mt-10">
                            <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 rounded-2xl bg-white/10 px-4 py-3 text-sm font-semibold text-white ring-1 ring-white/10 transition hover:bg-white/15 active:scale-[0.99]">
                                <x-heroicon-o-arrow-left class="h-5 w-5" />
                                <span>Kembali ke landing</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-6">
                    <div class="flex items-center justify-between lg:hidden">
                        <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm font-semibold text-gray-900 shadow-sm transition hover:bg-white active:scale-[0.99]">
                            <x-heroicon-o-arrow-left class="h-5 w-5" />
                            <span>Landing</span>
                        </a>
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/10">
                                <img src="{{ asset('img/logo.jpeg') }}" alt="Logo" class="h-full w-full object-cover">
                            </div>
                            <div class="text-sm font-semibold text-gray-900">Way Hitam Coffee</div>
                        </div>
                    </div>

                    <div class="relative mt-6 overflow-hidden rounded-[2.75rem] border border-black/5 bg-white/70 p-7 shadow-2xl shadow-black/10 backdrop-blur sm:p-10 lg:mt-0">
                        <div class="absolute left-0 right-0 top-0 h-1 bg-gradient-to-r from-red-600 via-yellow-500 to-red-600"></div>
                        {{ $slot }}
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
