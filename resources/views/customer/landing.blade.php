<x-layouts.customer :title="($settings['store_name'] ?? 'CoffeeShop').' - Ordering'" :brand="($settings['store_name'] ?? 'CoffeeShop')" :settings="$settings">
    <div class="space-y-10">
        <section class="relative overflow-hidden rounded-3xl border border-gray-200 bg-gradient-to-br from-white via-white to-[#FFF4E8] shadow-sm dark:border-white/10 dark:from-slate-900/70 dark:via-slate-900/60 dark:to-slate-950/40">
            <div class="pointer-events-none absolute -left-24 -top-24 h-72 w-72 rounded-full bg-[radial-gradient(circle_at_center,rgba(139,94,60,0.22),transparent_70%)] blur-2xl"></div>
            <div class="pointer-events-none absolute -right-28 top-10 h-80 w-80 rounded-full bg-[radial-gradient(circle_at_center,rgba(14,165,233,0.14),transparent_70%)] blur-2xl dark:bg-[radial-gradient(circle_at_center,rgba(99,102,241,0.14),transparent_70%)]"></div>
            <div class="grid gap-6 p-6 md:p-10 lg:grid-cols-12">
                <div class="lg:col-span-7">
                    <div class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-semibold text-gray-600 shadow-sm dark:border-white/10 dark:bg-slate-950/40 dark:text-slate-300">
                        <x-heroicon-o-sparkles class="h-4 w-4 text-[#8B5E3C]" />
                        <span>Tokopedia vibes • Kopi Kenangan energy</span>
                    </div>
                    <h1 class="mt-4 text-3xl font-semibold tracking-tight text-gray-900 dark:text-slate-100 md:text-5xl">
                        {{ $settings['store_name'] ?? 'CoffeeShop' }}
                        <span class="text-[#8B5E3C]">.</span>
                        <span class="mt-2 block text-lg font-medium text-gray-600 dark:text-slate-300 md:text-2xl">Order dari meja, cepat & nyaman.</span>
                    </h1>
                    <p class="mt-4 max-w-xl text-sm leading-6 text-gray-600 dark:text-slate-300 md:text-base">
                        Scan QR di meja, pilih menu, checkout tanpa login. Status pesanan bisa dipantau realtime.
                    </p>

                    <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                        <a href="#cara" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#8B5E3C] px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#6F4A2D] active:scale-[0.99]">
                            <x-heroicon-o-bolt class="h-5 w-5" />
                            <span>Pesan Sekarang</span>
                        </a>
                        <a href="{{ route('table.menu', ['code' => 'A1']) }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-900 shadow-sm transition hover:bg-[#F8F7F4] active:scale-[0.99] dark:border-white/10 dark:bg-slate-950/40 dark:text-slate-100 dark:hover:bg-slate-950">
                            <x-heroicon-o-qr-code class="h-5 w-5 text-[#8B5E3C]" />
                            <span>Demo /table/A1</span>
                        </a>
                    </div>

                    <div class="mt-8 grid gap-3 sm:grid-cols-3">
                        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-slate-950/40">
                            <div class="text-xs text-gray-500 dark:text-slate-400">Metode</div>
                            <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-slate-100">Cash / QRIS</div>
                        </div>
                        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-slate-950/40">
                            <div class="text-xs text-gray-500 dark:text-slate-400">Status</div>
                            <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-slate-100">Realtime tracking</div>
                        </div>
                        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-slate-950/40">
                            <div class="text-xs text-gray-500 dark:text-slate-400">UI</div>
                            <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-slate-100">Mobile-first</div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="rounded-3xl border border-gray-200 bg-[#FFF4E8] p-6 shadow-sm dark:border-white/10 dark:bg-slate-950/30">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="text-sm font-semibold text-gray-900 dark:text-slate-100">Promo Hari Ini</div>
                                <div class="mt-1 text-sm text-gray-500 dark:text-slate-400">Diskon 10% untuk menu favorit.</div>
                            </div>
                            <div class="grid h-12 w-12 place-items-center rounded-2xl bg-[#8B5E3C]/10 text-[#8B5E3C] ring-1 ring-[#8B5E3C]/15">
                                <x-heroicon-o-tag class="h-6 w-6" />
                            </div>
                        </div>

                        <div class="mt-5 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-slate-900/60">
                            <div class="text-xs text-gray-500 dark:text-slate-400">Kode promo</div>
                            <div class="mt-1 flex items-center justify-between gap-3">
                                <div class="text-lg font-semibold tracking-wider text-[#8B5E3C]">COFFEE10</div>
                                <button type="button" class="rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-900 shadow-sm transition hover:bg-[#F8F7F4] active:scale-[0.99] dark:border-white/10 dark:bg-slate-950/40 dark:text-slate-100 dark:hover:bg-slate-950" @click="$store.toast.push('Kode promo disalin (demo).','info')">
                                    Copy
                                </button>
                            </div>
                        </div>

                        <div id="cara" class="mt-6">
                            <div class="text-sm font-semibold text-gray-900 dark:text-slate-100">Cara Order</div>
                            <ol class="mt-3 space-y-3 text-sm text-gray-600 dark:text-slate-300">
                                <li class="flex gap-3">
                                    <div class="mt-0.5 grid h-7 w-7 place-items-center rounded-full bg-[#8B5E3C]/10 text-xs font-semibold text-[#8B5E3C] ring-1 ring-[#8B5E3C]/15">1</div>
                                    <div>Scan QR di meja (contoh: <span class="font-semibold text-gray-900 dark:text-slate-100">/table/A1</span>)</div>
                                </li>
                                <li class="flex gap-3">
                                    <div class="mt-0.5 grid h-7 w-7 place-items-center rounded-full bg-[#8B5E3C]/10 text-xs font-semibold text-[#8B5E3C] ring-1 ring-[#8B5E3C]/15">2</div>
                                    <div>Pilih menu, masukin ke keranjang</div>
                                </li>
                                <li class="flex gap-3">
                                    <div class="mt-0.5 grid h-7 w-7 place-items-center rounded-full bg-[#8B5E3C]/10 text-xs font-semibold text-[#8B5E3C] ring-1 ring-[#8B5E3C]/15">3</div>
                                    <div>Checkout tanpa login, pilih cash/QRIS</div>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="flex items-end justify-between gap-4">
                <div>
                    <div class="text-sm text-gray-500 dark:text-slate-400">Menu Favorit</div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-slate-100">Best seller pilihan customer</h2>
                </div>
                <div class="hidden text-sm text-gray-500 dark:text-slate-400 md:block">Scan QR meja untuk mulai order</div>
            </div>

            <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($featured as $p)
                    <div class="group overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-white/10 dark:bg-slate-900/60">
                        <div class="h-1 bg-gradient-to-r from-[#8B5E3C] via-amber-400 to-sky-400"></div>
                        <div class="aspect-[4/3] bg-[#F8F7F4] dark:bg-slate-800">
                            @if($p->photo_path)
                                <img src="{{ asset('storage/'.$p->photo_path) }}" alt="{{ $p->name }}" class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full items-center justify-center">
                                    <x-heroicon-o-photo class="h-10 w-10 text-gray-300 dark:text-slate-600" />
                                </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="truncate text-sm font-semibold text-gray-900 dark:text-slate-100">{{ $p->name }}</div>
                                    <div class="mt-1 text-xs text-gray-500 dark:text-slate-400">{{ $p->category?->name }}</div>
                                </div>
                                <div class="shrink-0 rounded-xl bg-[#8B5E3C]/10 px-3 py-1 text-xs font-semibold text-[#8B5E3C] ring-1 ring-[#8B5E3C]/15">
                                    Rp {{ number_format($p->price, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="mt-3 line-clamp-2 text-sm text-gray-600 dark:text-slate-300">{{ $p->description }}</div>
                            <div class="mt-4 flex items-center justify-between">
                                <div class="text-xs text-gray-500 dark:text-slate-400">{{ $p->is_out_of_stock ? 'Stok habis' : 'Stok tersedia' }}</div>
                                <a href="{{ route('table.menu', ['code' => 'A1']) }}" class="inline-flex items-center gap-2 rounded-xl bg-[#8B5E3C] px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-[#6F4A2D] active:scale-[0.99]">
                                    <x-heroicon-o-plus class="h-4 w-4" />
                                    <span>Order</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 text-sm text-gray-600 shadow-sm dark:border-white/10 dark:bg-slate-900/60 dark:text-slate-300">Belum ada menu favorit. Tambahkan di admin.</div>
                @endforelse
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-3">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-slate-900/60">
                <div class="flex items-center gap-3">
                    <div class="grid h-10 w-10 place-items-center rounded-2xl bg-[#8B5E3C]/10 text-[#8B5E3C] ring-1 ring-[#8B5E3C]/15">
                        <x-heroicon-o-star class="h-6 w-6" />
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-gray-900 dark:text-slate-100">Testimoni</div>
                        <div class="text-sm text-gray-500 dark:text-slate-400">Customer happy, omzet naik</div>
                    </div>
                </div>
                <div class="mt-4 space-y-3 text-sm text-gray-600 dark:text-slate-300">
                    <div class="rounded-2xl border border-gray-200 bg-[#F8F7F4] p-4 dark:border-white/10 dark:bg-slate-950/30">“UI-nya nyaman, order jadi cepat.”</div>
                    <div class="rounded-2xl border border-gray-200 bg-[#F8F7F4] p-4 dark:border-white/10 dark:bg-slate-950/30">“QRIS + cash semua tercatat, laporan enak dipakai.”</div>
                </div>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-slate-900/60 lg:col-span-2">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <div class="text-sm font-semibold text-gray-900 dark:text-slate-100">Kategori Menu</div>
                        <div class="text-sm text-gray-500 dark:text-slate-400">Coffee • Non Coffee • Snack</div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach($categories as $c)
                            <span class="rounded-full border border-gray-200 bg-[#F8F7F4] px-3 py-1 text-xs font-semibold text-gray-700 dark:border-white/10 dark:bg-slate-950/30 dark:text-slate-200">{{ $c->name }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl border border-gray-200 bg-[#F8F7F4] p-5 dark:border-white/10 dark:bg-slate-950/30">
                        <div class="text-sm font-semibold text-gray-900 dark:text-slate-100">Admin Dashboard</div>
                        <div class="mt-2 text-sm text-gray-600 dark:text-slate-300">Statistik, chart, pesanan realtime, export Excel/PDF.</div>
                    </div>
                    <div class="rounded-2xl border border-gray-200 bg-[#F8F7F4] p-5 dark:border-white/10 dark:bg-slate-950/30">
                        <div class="text-sm font-semibold text-gray-900 dark:text-slate-100">Customer Flow</div>
                        <div class="mt-2 text-sm text-gray-600 dark:text-slate-300">Scan QR → pilih menu → checkout → tracking.</div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-layouts.customer>
