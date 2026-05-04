<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? 'Admin - CoffeeShop' }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full overflow-x-hidden bg-gray-50 text-gray-900 antialiased dark:bg-gray-950 dark:text-gray-100">
        <div
            class="flex min-h-screen bg-gray-50 dark:bg-gray-950"
            x-data="{
                sidebarOpen: false,
                pendingOrders: 0,
                async refreshPending() {
                    try {
                        const res = await fetch(@js(route('admin.orders.pending-count')));
                        if (!res.ok) return;
                        const json = await res.json();
                        const next = Number(json.count ?? 0);
                        const prev = Number(this.pendingOrders ?? 0);
                        if (next > prev) {
                            window.Alpine?.store('toast')?.push(`Ada pesanan baru masuk (+${next - prev})`, 'info');
                        }
                        this.pendingOrders = next;
                    } catch (e) {}
                }
            }"
            x-init="refreshPending(); setInterval(() => refreshPending(), 5000)"
        >
            <div class="fixed inset-0 -z-10 bg-[radial-gradient(1200px_600px_at_10%_-10%,rgba(200,151,99,0.18),transparent),radial-gradient(900px_500px_at_90%_0%,rgba(255,232,194,0.12),transparent)]"></div>

            <div class="lg:hidden">
                <div
                    class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm transition"
                    x-show="sidebarOpen"
                    x-transition.opacity
                    x-cloak
                    @click="sidebarOpen = false"
                ></div>
                <aside
                    class="fixed left-0 top-0 z-50 h-screen w-72 -translate-x-full transform border-r border-black/5 bg-white shadow-2xl shadow-black/15 transition dark:border-white/10 dark:bg-gray-900"
                    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                    x-cloak
                >
                    <div class="flex h-full flex-col p-4">
                        <div class="flex items-center justify-between gap-3">
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-2xl px-3 py-3 transition hover:bg-black/5 active:scale-[0.99] dark:hover:bg-white/5">
                                <div class="grid h-10 w-10 place-items-center rounded-2xl bg-coffee-600 text-white shadow-sm">
                                    <x-heroicon-o-fire class="h-6 w-6" />
                                </div>
                                <div class="leading-tight">
                                    <div class="text-sm font-semibold">CoffeeShop Admin</div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400">UMKM Ordering System</div>
                                </div>
                            </a>
                            <button
                                type="button"
                                class="inline-flex items-center justify-center rounded-2xl border border-black/10 bg-white/70 p-2 shadow-sm transition hover:bg-white active:scale-[0.99] dark:border-white/10 dark:bg-gray-950/40 dark:hover:bg-gray-950"
                                @click="sidebarOpen = false"
                            >
                                <x-heroicon-o-x-mark class="h-5 w-5" />
                            </button>
                        </div>

                        <nav class="mt-4 flex-1 space-y-1 overflow-y-auto pr-1 text-sm">
                            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'bg-black/5 dark:bg-white/10' : '' }} group flex items-center gap-3 rounded-2xl px-3 py-2 font-semibold transition hover:bg-black/5 active:scale-[0.99] dark:hover:bg-white/5" @click="sidebarOpen = false">
                                <x-heroicon-o-squares-2x2 class="h-5 w-5 text-coffee-700 dark:text-cream-200" />
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'bg-black/5 dark:bg-white/10' : '' }} group flex items-center gap-3 rounded-2xl px-3 py-2 font-semibold transition hover:bg-black/5 active:scale-[0.99] dark:hover:bg-white/5" @click="sidebarOpen = false">
                                <x-heroicon-o-receipt-percent class="h-5 w-5 text-coffee-700 dark:text-cream-200" />
                                <span>Pesanan</span>
                                <span
                                    x-show="pendingOrders > 0"
                                    x-cloak
                                    class="ml-auto inline-flex min-w-6 items-center justify-center rounded-full bg-rose-600 px-2 py-0.5 text-xs font-semibold text-white"
                                    x-text="pendingOrders"
                                ></span>
                            </a>
                            <a href="{{ route('admin.pos') }}" class="{{ request()->routeIs('admin.pos*') ? 'bg-black/5 dark:bg-white/10' : '' }} group flex items-center gap-3 rounded-2xl px-3 py-2 font-semibold transition hover:bg-black/5 active:scale-[0.99] dark:hover:bg-white/5" @click="sidebarOpen = false">
                                <x-heroicon-o-computer-desktop class="h-5 w-5 text-coffee-700 dark:text-cream-200" />
                                <span>Kasir POS</span>
                            </a>
                            <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'bg-black/5 dark:bg-white/10' : '' }} group flex items-center gap-3 rounded-2xl px-3 py-2 font-semibold transition hover:bg-black/5 active:scale-[0.99] dark:hover:bg-white/5" @click="sidebarOpen = false">
                                <x-heroicon-o-cube class="h-5 w-5 text-coffee-700 dark:text-cream-200" />
                                <span>Menu</span>
                            </a>
                            <a href="{{ route('admin.stocks.index') }}" class="{{ request()->routeIs('admin.stocks.*') ? 'bg-black/5 dark:bg-white/10' : '' }} group flex items-center gap-3 rounded-2xl px-3 py-2 font-semibold transition hover:bg-black/5 active:scale-[0.99] dark:hover:bg-white/5" @click="sidebarOpen = false">
                                <x-heroicon-o-arrow-path class="h-5 w-5 text-coffee-700 dark:text-cream-200" />
                                <span>Stok</span>
                            </a>
                            <a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.*') ? 'bg-black/5 dark:bg-white/10' : '' }} group flex items-center gap-3 rounded-2xl px-3 py-2 font-semibold transition hover:bg-black/5 active:scale-[0.99] dark:hover:bg-white/5" @click="sidebarOpen = false">
                                <x-heroicon-o-chart-bar class="h-5 w-5 text-coffee-700 dark:text-cream-200" />
                                <span>Laporan</span>
                            </a>
                            <a href="{{ route('admin.tables.index') }}" class="{{ request()->routeIs('admin.tables.*') ? 'bg-black/5 dark:bg-white/10' : '' }} group flex items-center gap-3 rounded-2xl px-3 py-2 font-semibold transition hover:bg-black/5 active:scale-[0.99] dark:hover:bg-white/5" @click="sidebarOpen = false">
                                <x-heroicon-o-qr-code class="h-5 w-5 text-coffee-700 dark:text-cream-200" />
                                <span>Meja</span>
                            </a>
                            <a href="{{ route('admin.settings.edit') }}" class="{{ request()->routeIs('admin.settings.*') ? 'bg-black/5 dark:bg-white/10' : '' }} group flex items-center gap-3 rounded-2xl px-3 py-2 font-semibold transition hover:bg-black/5 active:scale-[0.99] dark:hover:bg-white/5" @click="sidebarOpen = false">
                                <x-heroicon-o-cog-6-tooth class="h-5 w-5 text-coffee-700 dark:text-cream-200" />
                                <span>Pengaturan</span>
                            </a>
                        </nav>

                        <div class="mt-4 rounded-2xl border border-black/5 bg-black/5 p-3 text-xs text-gray-600 dark:border-white/10 dark:bg-white/5 dark:text-gray-400">
                            <div class="font-semibold text-gray-900 dark:text-gray-100">{{ auth()->user()->name ?? 'Admin' }}</div>
                            <div class="mt-1 truncate">{{ auth()->user()->email ?? '' }}</div>
                        </div>
                    </div>
                </aside>
            </div>

            <aside class="fixed left-0 top-0 z-50 hidden h-screen w-72 border-r border-black/5 bg-white shadow-xl shadow-black/10 dark:border-white/10 dark:bg-gray-900 lg:flex lg:flex-col">
                <div class="flex h-full flex-col p-4">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-2xl px-3 py-3 transition hover:bg-black/5 active:scale-[0.99] dark:hover:bg-white/5">
                        <div class="grid h-10 w-10 place-items-center rounded-2xl bg-coffee-600 text-white shadow-sm">
                            <x-heroicon-o-fire class="h-6 w-6" />
                        </div>
                        <div class="leading-tight">
                            <div class="text-sm font-semibold">CoffeeShop Admin</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400">UMKM Ordering System</div>
                        </div>
                    </a>

                    <nav class="mt-4 flex-1 space-y-1 overflow-y-auto pr-1 text-sm">
                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'bg-black/5 dark:bg-white/10' : '' }} group flex items-center gap-3 rounded-2xl px-3 py-2 font-semibold transition hover:bg-black/5 active:scale-[0.99] dark:hover:bg-white/5">
                            <x-heroicon-o-squares-2x2 class="h-5 w-5 text-coffee-700 dark:text-cream-200" />
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'bg-black/5 dark:bg-white/10' : '' }} group flex items-center gap-3 rounded-2xl px-3 py-2 font-semibold transition hover:bg-black/5 active:scale-[0.99] dark:hover:bg-white/5">
                            <x-heroicon-o-receipt-percent class="h-5 w-5 text-coffee-700 dark:text-cream-200" />
                            <span>Pesanan</span>
                            <span
                                x-show="pendingOrders > 0"
                                x-cloak
                                class="ml-auto inline-flex min-w-6 items-center justify-center rounded-full bg-rose-600 px-2 py-0.5 text-xs font-semibold text-white"
                                x-text="pendingOrders"
                            ></span>
                        </a>
                        <a href="{{ route('admin.pos') }}" class="{{ request()->routeIs('admin.pos*') ? 'bg-black/5 dark:bg-white/10' : '' }} group flex items-center gap-3 rounded-2xl px-3 py-2 font-semibold transition hover:bg-black/5 active:scale-[0.99] dark:hover:bg-white/5">
                            <x-heroicon-o-computer-desktop class="h-5 w-5 text-coffee-700 dark:text-cream-200" />
                            <span>Kasir POS</span>
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'bg-black/5 dark:bg-white/10' : '' }} group flex items-center gap-3 rounded-2xl px-3 py-2 font-semibold transition hover:bg-black/5 active:scale-[0.99] dark:hover:bg-white/5">
                            <x-heroicon-o-cube class="h-5 w-5 text-coffee-700 dark:text-cream-200" />
                            <span>Menu</span>
                        </a>
                        <a href="{{ route('admin.stocks.index') }}" class="{{ request()->routeIs('admin.stocks.*') ? 'bg-black/5 dark:bg-white/10' : '' }} group flex items-center gap-3 rounded-2xl px-3 py-2 font-semibold transition hover:bg-black/5 active:scale-[0.99] dark:hover:bg-white/5">
                            <x-heroicon-o-arrow-path class="h-5 w-5 text-coffee-700 dark:text-cream-200" />
                            <span>Stok</span>
                        </a>
                        <a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.*') ? 'bg-black/5 dark:bg-white/10' : '' }} group flex items-center gap-3 rounded-2xl px-3 py-2 font-semibold transition hover:bg-black/5 active:scale-[0.99] dark:hover:bg-white/5">
                            <x-heroicon-o-chart-bar class="h-5 w-5 text-coffee-700 dark:text-cream-200" />
                            <span>Laporan</span>
                        </a>
                        <a href="{{ route('admin.tables.index') }}" class="{{ request()->routeIs('admin.tables.*') ? 'bg-black/5 dark:bg-white/10' : '' }} group flex items-center gap-3 rounded-2xl px-3 py-2 font-semibold transition hover:bg-black/5 active:scale-[0.99] dark:hover:bg-white/5">
                            <x-heroicon-o-qr-code class="h-5 w-5 text-coffee-700 dark:text-cream-200" />
                            <span>Meja</span>
                        </a>
                        <a href="{{ route('admin.settings.edit') }}" class="{{ request()->routeIs('admin.settings.*') ? 'bg-black/5 dark:bg-white/10' : '' }} group flex items-center gap-3 rounded-2xl px-3 py-2 font-semibold transition hover:bg-black/5 active:scale-[0.99] dark:hover:bg-white/5">
                            <x-heroicon-o-cog-6-tooth class="h-5 w-5 text-coffee-700 dark:text-cream-200" />
                            <span>Pengaturan</span>
                        </a>
                    </nav>

                    <div class="mt-4 rounded-2xl border border-black/5 bg-black/5 p-3 text-xs text-gray-600 dark:border-white/10 dark:bg-white/5 dark:text-gray-400">
                        <div class="font-semibold text-gray-900 dark:text-gray-100">{{ auth()->user()->name ?? 'Admin' }}</div>
                        <div class="mt-1 truncate">{{ auth()->user()->email ?? '' }}</div>
                    </div>
                </div>
            </aside>

            <div class="lg:ml-72 min-h-screen flex flex-1 flex-col min-w-0">
                <header class="sticky top-0 z-30 border-b border-gray-200 bg-white/90 backdrop-blur dark:border-white/10 dark:bg-gray-950/70">
                    <div class="flex items-center justify-between gap-4 px-6 py-4">
                        <div class="flex min-w-0 items-center gap-3">
                            <button
                                type="button"
                                class="inline-flex items-center justify-center rounded-2xl border border-gray-200 bg-white px-3 py-2 text-sm font-semibold shadow-sm transition hover:bg-gray-50 active:scale-[0.99] dark:border-white/10 dark:bg-gray-900 dark:hover:bg-gray-800 lg:hidden"
                                @click="sidebarOpen = true"
                            >
                                <x-heroicon-o-bars-3 class="h-5 w-5" />
                            </button>
                            <div class="min-w-0">
                                <div class="truncate text-sm text-gray-600 dark:text-gray-400">{{ $subtitle ?? 'Ringkasan bisnis hari ini' }}</div>
                                <h1 class="truncate text-xl font-semibold tracking-tight md:text-2xl">{{ $header ?? 'Dashboard' }}</h1>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="inline-flex items-center gap-2 rounded-2xl bg-gray-950 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-black active:scale-[0.99] dark:bg-white dark:text-gray-950"
                                >
                                    <x-heroicon-o-arrow-right-on-rectangle class="h-5 w-5" />
                                    <span class="hidden sm:inline">Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </header>

                <main class="flex-1 p-6">
                    <div class="mx-auto w-full max-w-7xl min-w-0">
                        {{ $slot ?? '' }}
                    </div>
                </main>
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
        @stack('scripts')
    </body>
</html>
