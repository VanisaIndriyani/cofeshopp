<x-layouts.customer :title="'Menu - Meja '.$table->code" :brand="($settings['store_name'] ?? 'CoffeeShop')" :settings="$settings">
    <div class="flex flex-col gap-6">
        <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-gradient-to-br from-white via-white to-[#FFF4E8] p-6 shadow-sm dark:border-white/10 dark:from-slate-900/70 dark:via-slate-900/60 dark:to-slate-950/40">
            <div class="pointer-events-none absolute -left-24 -top-24 h-72 w-72 rounded-full bg-[radial-gradient(circle_at_center,rgba(139,94,60,0.20),transparent_70%)] blur-2xl"></div>
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <div class="text-sm text-gray-500 dark:text-slate-400">Anda sedang memesan di</div>
                    <div class="mt-2 inline-flex items-center gap-2 rounded-2xl bg-[#8B5E3C]/10 px-4 py-2 text-sm font-semibold text-[#8B5E3C] ring-1 ring-[#8B5E3C]/15">
                        <x-heroicon-o-qr-code class="h-5 w-5" />
                        <span>Meja {{ $table->code }}</span>
                    </div>
                </div>
                <div class="text-sm text-gray-500 dark:text-slate-400">Pilih menu favorit kamu</div>
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-12">
            <div class="lg:col-span-4">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-slate-900/60">
                    <div class="h-1 rounded-full bg-gradient-to-r from-[#8B5E3C] via-amber-400 to-sky-400"></div>
                    <div class="text-sm font-semibold text-gray-900 dark:text-slate-100">Cari & Filter</div>
                    <form class="mt-4 space-y-3" method="GET" action="{{ route('table.menu', ['code' => $table->code]) }}" x-data="{ q: @js($q), t: null }">
                        <div>
                            <label class="text-xs font-semibold text-gray-600 dark:text-slate-300">Search menu</label>
                            <input
                                name="q"
                                x-model="q"
                                @input.debounce.350ms="$nextTick(() => $el.form.requestSubmit())"
                                class="mt-2 w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 shadow-sm outline-none transition focus:border-[#8B5E3C]/50 focus:ring-4 focus:ring-[#8B5E3C]/10 dark:border-white/10 dark:bg-slate-950/40 dark:text-slate-100 dark:placeholder:text-slate-500"
                                placeholder="Cari nama menu..."
                            />
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-gray-600 dark:text-slate-300">Kategori</div>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <a
                                    href="{{ route('table.menu', ['code' => $table->code, 'q' => $q]) }}"
                                    class="{{ $selectedCategory === '' ? 'bg-[#8B5E3C]/10 text-[#8B5E3C] ring-[#8B5E3C]/15' : 'bg-[#F8F7F4] text-gray-700 ring-gray-200 dark:bg-slate-950/30 dark:text-slate-200 dark:ring-white/10' }} inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 transition hover:bg-white active:scale-[0.99] dark:hover:bg-slate-950"
                                >Semua</a>
                                @foreach($categories as $c)
                                    <a
                                        href="{{ route('table.menu', ['code' => $table->code, 'category' => $c->slug, 'q' => $q]) }}"
                                        class="{{ $selectedCategory === $c->slug ? 'bg-[#8B5E3C]/10 text-[#8B5E3C] ring-[#8B5E3C]/15' : 'bg-[#F8F7F4] text-gray-700 ring-gray-200 dark:bg-slate-950/30 dark:text-slate-200 dark:ring-white/10' }} inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 transition hover:bg-white active:scale-[0.99] dark:hover:bg-slate-950"
                                    >{{ $c->name }}</a>
                                @endforeach
                            </div>
                        </div>
                        <div class="rounded-2xl border border-gray-200 bg-[#F8F7F4] p-4 text-xs text-gray-600 dark:border-white/10 dark:bg-slate-950/30 dark:text-slate-300">
                            Tips: klik menu untuk tambah ke keranjang. Stok habis akan otomatis terkunci.
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-8">
                <div class="grid gap-4 sm:grid-cols-2" x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 220)">
                    <template x-if="loading">
                        <div class="grid gap-4 sm:grid-cols-2 sm:col-span-2">
                            @for($i = 0; $i < 6; $i++)
                                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-slate-900/60">
                                    <div class="aspect-[16/10] animate-pulse bg-slate-100 dark:bg-slate-800"></div>
                                    <div class="space-y-3 p-5">
                                        <div class="h-4 w-2/3 animate-pulse rounded-lg bg-slate-100 dark:bg-slate-800"></div>
                                        <div class="h-3 w-1/3 animate-pulse rounded-lg bg-slate-100 dark:bg-slate-800"></div>
                                        <div class="h-3 w-full animate-pulse rounded-lg bg-slate-100 dark:bg-slate-800"></div>
                                        <div class="h-3 w-5/6 animate-pulse rounded-lg bg-slate-100 dark:bg-slate-800"></div>
                                        <div class="mt-2 flex items-center justify-between">
                                            <div class="h-3 w-24 animate-pulse rounded-lg bg-slate-100 dark:bg-slate-800"></div>
                                            <div class="h-9 w-24 animate-pulse rounded-2xl bg-slate-100 dark:bg-slate-800"></div>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </template>
                    @forelse($products as $p)
                        <div x-show="!loading" x-cloak class="group overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-white/10 dark:bg-slate-900/60">
                            <div class="h-1 bg-gradient-to-r from-[#8B5E3C] via-amber-400 to-sky-400"></div>
                            <div class="aspect-[16/10] bg-[#F8F7F4] dark:bg-slate-800">
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
                                    <div class="text-xs {{ $p->is_out_of_stock ? 'text-rose-600 dark:text-rose-300' : ($p->is_low_stock ? 'text-amber-600 dark:text-amber-300' : 'text-emerald-600 dark:text-emerald-300') }}">
                                        @if($p->is_out_of_stock)
                                            Stok habis
                                        @elseif($p->is_low_stock)
                                            Stok menipis ({{ $p->stock }})
                                        @else
                                            Stok tersedia
                                        @endif
                                    </div>

                                    <form method="POST" action="{{ route('cart.add') }}">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $p->id }}">
                                        <input type="hidden" name="qty" value="1">
                                        <button
                                            type="submit"
                                            class="inline-flex items-center gap-2 rounded-xl px-3 py-2 text-xs font-semibold shadow-sm transition active:scale-[0.99] {{ $p->is_out_of_stock ? 'cursor-not-allowed border border-gray-200 bg-[#F8F7F4] text-gray-400 dark:border-white/10 dark:bg-slate-950/30 dark:text-slate-500' : 'bg-[#8B5E3C] text-white hover:bg-[#6F4A2D]' }}"
                                            {{ $p->is_out_of_stock ? 'disabled' : '' }}
                                        >
                                            <x-heroicon-o-plus class="h-4 w-4" />
                                            <span>Tambah</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-gray-200 bg-white p-6 text-sm text-gray-600 shadow-sm dark:border-white/10 dark:bg-slate-900/60 dark:text-slate-300 sm:col-span-2">Menu belum tersedia.</div>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.customer>
