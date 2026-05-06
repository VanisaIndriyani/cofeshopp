<x-layouts.customer :title="'Menu - Meja '.$table->code" :brand="($settings['store_name'] ?? 'CoffeeShop')" :settings="$settings">
    <div class="flex flex-col gap-8">
        <div class="relative overflow-hidden rounded-[2.5rem] border border-gray-200 bg-white p-8 shadow-xl shadow-gray-200/50 dark:border-white/10 dark:bg-slate-900/40">
            <div class="pointer-events-none absolute -left-24 -top-24 h-72 w-72 rounded-full bg-rose-500/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -right-24 -bottom-24 h-72 w-72 rounded-full bg-blue-500/10 blur-3xl"></div>
            
            <div class="relative flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <div>
                    <div class="text-xs font-black uppercase tracking-[0.2em] text-gray-400">Lokasi Pesanan</div>
                    <div class="mt-3 inline-flex items-center gap-3 rounded-[1.25rem] bg-red-600 px-6 py-3 text-sm font-black text-white shadow-2xl shadow-red-600/40 dark:bg-red-600 dark:text-white">
                        <x-heroicon-s-qr-code class="h-5 w-5 text-yellow-400" />
                        <span>Meja {{ $table->code }}</span>
                    </div>
                </div>
                <div class="max-w-xs text-right md:block hidden">
                    <div class="text-sm font-bold text-gray-950 dark:text-white">Pilih Menu Favorit</div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">Silakan pilih menu dan tambahkan ke keranjang untuk memesan.</p>
                </div>
            </div>
        </div>

        <form id="add-to-cart-form" action="{{ route('cart.add') }}" method="POST" class="hidden">
            @csrf
            <input type="hidden" name="product_id" id="cart-product-id">
            <input type="hidden" name="qty" value="1">
        </form>

        <div class="grid gap-8 lg:grid-cols-12" x-data="{ 
            handleAddToCart(id) {
                document.getElementById('cart-product-id').value = id;
                document.getElementById('add-to-cart-form').submit();
            }
        }" @cart-add.window="handleAddToCart($event.detail.id)">
            <div class="lg:col-span-4">
                <div class="sticky top-24 space-y-6">
                    <div class="rounded-[2rem] border border-gray-200 bg-white p-6 shadow-xl shadow-gray-200/50 dark:border-white/10 dark:bg-slate-900/40">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-1 bg-red-600 rounded-full"></div>
                            <h3 class="text-sm font-black uppercase tracking-widest text-gray-950 dark:text-white">Cari & Filter</h3>
                        </div>
                        
                        <form class="mt-6 space-y-6" method="GET" action="{{ route('table.menu', ['code' => $table->code]) }}" x-data="{ q: @js($q), t: null }">
                            <div class="relative">
                                <x-heroicon-o-magnifying-glass class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" />
                                <input
                                    name="q"
                                    x-model="q"
                                    @input.debounce.350ms="$nextTick(() => $el.form.requestSubmit())"
                                    class="w-full rounded-2xl border-2 border-gray-100 bg-gray-50 py-4 pl-12 pr-4 text-sm font-bold text-gray-950 outline-none transition focus:border-red-600 focus:bg-white dark:border-white/5 dark:bg-slate-950/40 dark:text-white dark:focus:border-red-500"
                                    placeholder="Cari menu..."
                                />
                            </div>

                            <div>
                                <div class="text-[10px] font-black uppercase tracking-widest text-gray-400">Kategori</div>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <a
                                        href="{{ route('table.menu', ['code' => $table->code, 'q' => $q]) }}"
                                        class="{{ $selectedCategory === '' ? 'bg-red-600 text-white shadow-lg shadow-red-600/20' : 'bg-gray-100 text-gray-500 hover:bg-gray-200 dark:bg-slate-800/50 dark:text-slate-400' }} rounded-xl px-4 py-2 text-xs font-bold transition active:scale-95"
                                    >Semua</a>
                                    @php
                                        $colors = [
                                            'hot-coffee' => 'bg-red-600',
                                            'ice-coffee' => 'bg-amber-500',
                                            'non-coffee' => 'bg-orange-500',
                                            'jus' => 'bg-yellow-500',
                                            'wedang' => 'bg-red-500',
                                            'makanan' => 'bg-red-700'
                                        ];
                                    @endphp
                                    @foreach($categories as $c)
                                        <a
                                            href="{{ route('table.menu', ['code' => $table->code, 'category' => $c->slug, 'q' => $q]) }}"
                                            class="{{ $selectedCategory === $c->slug ? ($colors[$c->slug] ?? 'bg-red-600').' text-white shadow-lg shadow-black/10' : 'bg-gray-100 text-gray-500 hover:bg-gray-200 dark:bg-slate-800/50 dark:text-slate-400' }} rounded-xl px-4 py-2 text-xs font-bold transition active:scale-95"
                                        >{{ $c->name }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="rounded-2xl bg-gradient-to-br from-yellow-400 to-yellow-500 p-6 text-red-900 shadow-xl shadow-yellow-500/20">
                        <div class="flex items-center gap-3">
                            <x-heroicon-s-light-bulb class="h-6 w-6 text-red-600" />
                            <div class="text-sm font-black uppercase tracking-widest">Tips Order</div>
                        </div>
                        <p class="mt-3 text-xs font-bold leading-relaxed text-red-800">
                            Klik tombol "Order" untuk menambah ke keranjang. Kamu bisa menambah catatan khusus di halaman keranjang.
                        </p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8">
                <div class="grid gap-6 sm:grid-cols-2" x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 220)">
                    <template x-if="loading">
                        <div class="grid gap-6 sm:grid-cols-2 sm:col-span-2">
                            @for($i = 0; $i < 6; $i++)
                                <div class="rounded-[2rem] border border-gray-100 bg-white p-2 shadow-lg dark:border-white/5 dark:bg-slate-900/60">
                                    <div class="aspect-[16/10] animate-pulse rounded-[1.5rem] bg-slate-100 dark:bg-slate-800"></div>
                                    <div class="space-y-4 p-5">
                                        <div class="h-5 w-2/3 animate-pulse rounded-lg bg-slate-100 dark:bg-slate-800"></div>
                                        <div class="h-3 w-1/3 animate-pulse rounded-lg bg-slate-100 dark:bg-slate-800"></div>
                                        <div class="space-y-2">
                                            <div class="h-3 w-full animate-pulse rounded-lg bg-slate-100 dark:bg-slate-800"></div>
                                            <div class="h-3 w-4/5 animate-pulse rounded-lg bg-slate-100 dark:bg-slate-800"></div>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </template>
                    @forelse($products as $p)
                        <div x-show="!loading" x-cloak class="group relative flex flex-col overflow-hidden rounded-[2rem] border border-gray-100 bg-white p-2 shadow-lg transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:shadow-gray-200/50 dark:border-white/5 dark:bg-slate-900/60 dark:shadow-none">
                            <div class="relative aspect-[16/10] overflow-hidden rounded-[1.5rem] bg-gray-100 dark:bg-slate-800">
                                @if($p->photo_path)
                                    <img src="{{ asset('storage/'.$p->photo_path) }}" alt="{{ $p->name }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                                @else
                                    <div class="flex h-full items-center justify-center">
                                        <x-heroicon-o-photo class="h-12 w-12 text-gray-300 dark:text-slate-600" />
                                    </div>
                                @endif
                                <div class="absolute top-4 right-4 rounded-full bg-white/90 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-gray-900 shadow-sm backdrop-blur">
                                    {{ $p->category?->name }}
                                </div>
                            </div>
                            <div class="flex flex-1 flex-col p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <h3 class="text-lg font-bold text-gray-950 dark:text-white line-clamp-1">{{ $p->name }}</h3>
                                    <div class="shrink-0 text-lg font-black text-red-600 dark:text-red-500">
                                        <span class="text-[10px] font-bold text-gray-400">Rp</span>{{ number_format($p->price, 0, ',', '.') }}
                                    </div>
                                </div>
                                <p class="mt-2 line-clamp-2 text-xs leading-relaxed text-gray-500 dark:text-slate-400">{{ $p->description }}</p>
                                
                                <div class="mt-auto pt-5 flex items-center justify-between border-t border-gray-50 dark:border-white/5">
                                    <div class="flex items-center gap-2">
                                        <div class="h-2 w-2 rounded-full {{ $p->is_out_of_stock ? 'bg-gray-400' : 'bg-red-600 animate-pulse' }}"></div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">{{ $p->is_out_of_stock ? 'Stok Habis' : 'Tersedia' }}</span>
                                    </div>
                                    <button
                                        type="button"
                                        class="{{ $p->is_out_of_stock ? 'bg-gray-100 text-gray-400 cursor-not-allowed dark:bg-slate-800 dark:text-slate-600' : 'bg-red-600 text-white hover:bg-red-700 active:scale-90 shadow-lg shadow-red-600/20' }} flex h-10 w-10 items-center justify-center rounded-xl transition"
                                        @click="if(!{{ $p->is_out_of_stock ? 'true' : 'false' }}) $dispatch('cart-add', { id: {{ $p->id }}, name: '{{ $p->name }}', price: {{ $p->price }} })"
                                    >
                                        <x-heroicon-o-plus class="h-5 w-5" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-3xl border-2 border-dashed border-gray-200 bg-gray-50 p-12 text-center dark:border-white/5 dark:bg-slate-900/40 sm:col-span-2">
                            <x-heroicon-o-face-frown class="mx-auto h-12 w-12 text-gray-300" />
                            <h3 class="mt-4 text-sm font-bold text-gray-950 dark:text-white">Menu Tidak Ditemukan</h3>
                            <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">Coba gunakan kata kunci lain atau pilih kategori yang berbeda.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-12">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.customer>
