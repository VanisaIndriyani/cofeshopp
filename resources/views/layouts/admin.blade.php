<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? 'Admin - Way Hitam Coffee' }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full overflow-x-hidden bg-gray-50 text-gray-900 antialiased dark:bg-gray-950 dark:text-gray-100">
        @php
            $adminNav = [
                [
                    'label' => 'Dashboard',
                    'route' => 'admin.dashboard',
                    'active' => 'admin.dashboard',
                    'icon' => 'heroicon-o-squares-2x2',
                ],
                [
                    'label' => 'Pesanan',
                    'route' => 'admin.orders.index',
                    'active' => 'admin.orders.*',
                    'icon' => 'heroicon-o-receipt-percent',
                    'badge' => 'pendingOrders',
                ],
                [
                    'label' => 'Kasir POS',
                    'route' => 'admin.pos',
                    'active' => 'admin.pos*',
                    'icon' => 'heroicon-o-computer-desktop',
                ],
                [
                    'label' => 'Menu',
                    'route' => 'admin.products.index',
                    'active' => 'admin.products.*',
                    'icon' => 'heroicon-o-cube',
                ],
                [
                    'label' => 'Stok',
                    'route' => 'admin.stocks.index',
                    'active' => 'admin.stocks.*',
                    'icon' => 'heroicon-o-arrow-path',
                ],
                [
                    'label' => 'Laporan',
                    'route' => 'admin.reports.index',
                    'active' => 'admin.reports.*',
                    'icon' => 'heroicon-o-chart-bar',
                ],
                [
                    'label' => 'Meja',
                    'route' => 'admin.tables.index',
                    'active' => 'admin.tables.*',
                    'icon' => 'heroicon-o-qr-code',
                ],
                [
                    'label' => 'Pengaturan',
                    'route' => 'admin.settings.edit',
                    'active' => 'admin.settings.*',
                    'icon' => 'heroicon-o-cog-6-tooth',
                ],
            ];
        @endphp
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
                    class="fixed left-0 top-0 z-50 h-screen w-80 -translate-x-full transform border-r border-black/5 bg-white/90 shadow-2xl shadow-black/15 backdrop-blur transition dark:border-white/10 dark:bg-gray-950/70"
                    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                    x-cloak
                >
                    <div class="flex h-full flex-col p-4">
                        <div class="flex items-center justify-between gap-3">
                            <a href="{{ route('admin.dashboard') }}" class="relative flex flex-1 items-center gap-3 overflow-hidden rounded-2xl px-3 py-3 transition hover:bg-black/5 active:scale-[0.99] dark:hover:bg-white/5">
                                <div class="absolute left-0 right-0 top-0 h-1 bg-gradient-to-r from-red-600 via-yellow-500 to-red-600"></div>
                                <div class="h-10 w-10 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/5">
                                    <img src="{{ asset('img/logo.jpeg') }}" alt="Logo" class="h-full w-full object-cover">
                                </div>
                                <div class="leading-tight">
                                    <div class="text-sm font-semibold text-gray-950 dark:text-gray-100">Way Hitam Coffee</div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400">Admin System</div>
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

                        <div class="mt-5 flex items-center justify-between px-2">
                            <div class="text-xs font-semibold uppercase tracking-widest text-gray-500 dark:text-gray-400">Menu</div>
                        </div>
                        <nav class="mt-2 flex-1 space-y-1 overflow-y-auto pr-1 text-sm">
                            @foreach($adminNav as $item)
                                @php($isActive = request()->routeIs($item['active']))
                                <a
                                    href="{{ route($item['route']) }}"
                                    class="group relative flex items-center gap-3 rounded-2xl px-4 py-3 font-semibold transition before:absolute before:left-2 before:top-1/2 before:h-6 before:w-1 before:-translate-y-1/2 before:rounded-full before:bg-red-600 before:transition before:content-[''] {{ $isActive ? 'bg-gradient-to-r from-red-600/10 via-yellow-500/5 to-transparent text-gray-950 before:opacity-100 dark:bg-white/10 dark:text-white' : 'text-gray-700 before:opacity-0 hover:bg-black/5 hover:text-gray-950 dark:text-gray-200 dark:hover:bg-white/5' }}"
                                    @click="sidebarOpen = false"
                                >
                                    <x-dynamic-component :component="$item['icon']" class="h-5 w-5 {{ $isActive ? 'text-red-600' : 'text-gray-500 group-hover:text-gray-950 dark:text-gray-400 dark:group-hover:text-white' }}" />
                                    <span class="truncate">{{ $item['label'] }}</span>
                                    @if(isset($item['badge']))
                                        <span
                                            x-show="pendingOrders > 0"
                                            x-cloak
                                            class="ml-auto inline-flex min-w-6 items-center justify-center rounded-full bg-rose-600 px-2 py-0.5 text-xs font-semibold text-white shadow-sm shadow-rose-600/20"
                                            x-text="pendingOrders"
                                        ></span>
                                    @endif
                                </a>
                            @endforeach
                        </nav>

                        <div class="mt-4 rounded-2xl border border-black/5 bg-white/70 p-3 text-xs text-gray-600 shadow-sm dark:border-white/10 dark:bg-gray-950/40 dark:text-gray-300">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-red-600 to-yellow-500 text-sm font-black text-white">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="truncate font-semibold text-gray-950 dark:text-white">{{ auth()->user()->name ?? 'Admin' }}</div>
                                    <div class="truncate text-gray-500 dark:text-gray-400">{{ auth()->user()->email ?? '' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>

            <aside class="fixed left-0 top-0 z-50 hidden h-screen w-80 border-r border-black/5 bg-white/90 shadow-xl shadow-black/10 backdrop-blur dark:border-white/10 dark:bg-gray-950/70 lg:flex lg:flex-col">
                <div class="flex h-full flex-col p-4">
                    <a href="{{ route('admin.dashboard') }}" class="relative flex items-center gap-3 overflow-hidden rounded-2xl px-3 py-3 transition hover:bg-black/5 active:scale-[0.99] dark:hover:bg-white/5">
                        <div class="absolute left-0 right-0 top-0 h-1 bg-gradient-to-r from-red-600 via-yellow-500 to-red-600"></div>
                        <div class="h-10 w-10 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/5">
                            <img src="{{ asset('img/logo.jpeg') }}" alt="Logo" class="h-full w-full object-cover">
                        </div>
                        <div class="leading-tight">
                            <div class="text-sm font-semibold text-gray-950 dark:text-gray-100">Way Hitam Coffee</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400">Admin System</div>
                        </div>
                    </a>

                    <div class="mt-5 flex items-center justify-between px-2">
                        <div class="text-xs font-semibold uppercase tracking-widest text-gray-500 dark:text-gray-400">Menu</div>
                    </div>
                    <nav class="mt-2 flex-1 space-y-1 overflow-y-auto pr-1 text-sm">
                        @foreach($adminNav as $item)
                            @php($isActive = request()->routeIs($item['active']))
                            <a
                                href="{{ route($item['route']) }}"
                                class="group relative flex items-center gap-3 rounded-2xl px-4 py-3 font-semibold transition before:absolute before:left-2 before:top-1/2 before:h-6 before:w-1 before:-translate-y-1/2 before:rounded-full before:bg-red-600 before:transition before:content-[''] {{ $isActive ? 'bg-gradient-to-r from-red-600/10 via-yellow-500/5 to-transparent text-gray-950 before:opacity-100 dark:bg-white/10 dark:text-white' : 'text-gray-700 before:opacity-0 hover:bg-black/5 hover:text-gray-950 dark:text-gray-200 dark:hover:bg-white/5' }}"
                            >
                                <x-dynamic-component :component="$item['icon']" class="h-5 w-5 {{ $isActive ? 'text-red-600' : 'text-gray-500 group-hover:text-gray-950 dark:text-gray-400 dark:group-hover:text-white' }}" />
                                <span class="truncate">{{ $item['label'] }}</span>
                                @if(isset($item['badge']))
                                    <span
                                        x-show="pendingOrders > 0"
                                        x-cloak
                                        class="ml-auto inline-flex min-w-6 items-center justify-center rounded-full bg-rose-600 px-2 py-0.5 text-xs font-semibold text-white shadow-sm shadow-rose-600/20"
                                        x-text="pendingOrders"
                                    ></span>
                                @endif
                            </a>
                        @endforeach
                    </nav>

                    <div class="mt-4 rounded-2xl border border-black/5 bg-white/70 p-3 text-xs text-gray-600 shadow-sm dark:border-white/10 dark:bg-gray-950/40 dark:text-gray-300">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-red-600 to-yellow-500 text-sm font-black text-white">
                                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="truncate font-semibold text-gray-950 dark:text-white">{{ auth()->user()->name ?? 'Admin' }}</div>
                                <div class="truncate text-gray-500 dark:text-gray-400">{{ auth()->user()->email ?? '' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <div class="lg:ml-80 min-h-screen flex flex-1 flex-col min-w-0">
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
