<x-layouts.admin title="Admin - Kasir POS" header="Kasir POS" subtitle="Input manual order kasir, cash/QRIS, cetak struk">
    @php($productData = $products->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'price' => (int) $p->price, 'stock' => (int) $p->stock, 'category' => $p->category?->name])->values())
    <div
        x-data="{
            q: '',
            tableId: '{{ old('table_id', $tables->first()?->id) }}',
            customerName: '{{ old('customer_name', '') }}',
            paymentMethod: '{{ old('payment_method', 'cash') }}',
            markPaid: {{ old('mark_paid') ? 'true' : 'false' }},
            products: @js($productData),
            cart: {},
            add(p) {
                if (p.stock <= 0) return;
                const item = this.cart[p.id] ?? { product_id: p.id, name: p.name, price: p.price, qty: 0, note: '' };
                if (item.qty + 1 > p.stock) return;
                item.qty += 1;
                this.cart[p.id] = item;
            },
            dec(p) {
                const item = this.cart[p.id];
                if (!item) return;
                item.qty -= 1;
                if (item.qty <= 0) delete this.cart[p.id];
            },
            itemsArray() {
                return Object.values(this.cart).map(i => ({ product_id: i.product_id, qty: i.qty, note: i.note }));
            },
            subtotal() {
                return Object.values(this.cart).reduce((sum, i) => sum + (i.price * i.qty), 0);
            }
        }"
        class="grid gap-6 xl:grid-cols-12"
    >
        <div class="xl:col-span-7 space-y-4">
            <div class="rounded-3xl border border-black/5 bg-white/70 p-5 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40">
                <div class="text-sm font-semibold">Pilih Menu</div>
                <input x-model="q" placeholder="Search menu..." class="mt-4 w-full rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40" />
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <template x-for="p in products.filter(pp => (pp.name + ' ' + (pp.category ?? '')).toLowerCase().includes(q.toLowerCase()))" :key="p.id">
                    <div class="rounded-3xl border border-black/5 bg-white/70 p-5 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="truncate text-sm font-semibold" x-text="p.name"></div>
                                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400" x-text="p.category"></div>
                            </div>
                            <div class="shrink-0 text-sm font-semibold" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(p.price)"></div>
                        </div>
                        <div class="mt-3 flex items-center justify-between">
                            <div class="text-xs" :class="p.stock <= 0 ? 'text-rose-600 dark:text-rose-300' : (p.stock <= 5 ? 'text-amber-700 dark:text-amber-300' : 'text-emerald-700 dark:text-emerald-300')">
                                <span x-text="p.stock <= 0 ? 'Stok habis' : ('Stok: ' + p.stock)"></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" class="rounded-2xl bg-white/80 px-3 py-2 text-xs font-semibold ring-1 ring-black/10 transition hover:bg-white dark:bg-gray-950/40 dark:ring-white/10" @click="dec(p)">-</button>
                                <div class="w-8 text-center text-sm font-semibold" x-text="cart[p.id]?.qty ?? 0"></div>
                                <button type="button" class="rounded-2xl bg-coffee-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-coffee-700" @click="add(p)" :disabled="p.stock <= 0">+</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="xl:col-span-5">
            <div class="sticky top-6 space-y-4">
                <div class="rounded-3xl border border-black/5 bg-white/70 p-5 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40">
                    <div class="text-sm font-semibold">Order POS</div>
                    <form method="POST" action="{{ route('admin.pos.store') }}" class="mt-4 space-y-3">
                        @csrf
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <label class="text-xs font-semibold text-gray-600 dark:text-gray-300">Meja</label>
                                <select name="table_id" x-model="tableId" class="mt-2 w-full rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40">
                                    @foreach($tables as $t)
                                        <option value="{{ $t->id }}">{{ $t->code }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="text-xs font-semibold text-gray-600 dark:text-gray-300">Nama Customer (opsional)</label>
                                <input name="customer_name" x-model="customerName" class="mt-2 w-full rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40" />
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-600 dark:text-gray-300">Metode</label>
                                <select name="payment_method" x-model="paymentMethod" class="mt-2 w-full rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40">
                                    <option value="cash">Cash</option>
                                    <option value="qris">QRIS</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
                                    <input type="checkbox" name="mark_paid" value="1" x-model="markPaid" class="h-4 w-4 rounded border-black/20 text-coffee-600 focus:ring-coffee-400 dark:border-white/20">
                                    <span>PAID</span>
                                </label>
                            </div>
                        </div>

                        <input type="hidden" name="items_json" :value="JSON.stringify(itemsArray())" />

                        <div class="rounded-3xl border border-black/5 bg-white/60 p-4 text-sm dark:border-white/10 dark:bg-gray-900/40">
                            <div class="flex items-center justify-between">
                                <div class="text-xs text-gray-600 dark:text-gray-400">Subtotal</div>
                                <div class="font-semibold" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal())"></div>
                            </div>
                            <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">Pajak & service charge otomatis mengikuti setting.</div>
                        </div>

                        <button class="w-full rounded-2xl bg-coffee-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-coffee-700" :disabled="itemsArray().length === 0">
                            Buat Order
                        </button>
                    </form>
                </div>

                <div class="rounded-3xl border border-black/5 bg-white/70 p-5 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40">
                    <div class="text-sm font-semibold">Item</div>
                    <div class="mt-4 space-y-2">
                        <template x-for="it in Object.values(cart)" :key="it.product_id">
                            <div class="flex items-start justify-between gap-3 rounded-2xl border border-black/5 bg-white/60 p-4 text-sm dark:border-white/10 dark:bg-gray-900/40">
                                <div class="min-w-0">
                                    <div class="truncate font-semibold" x-text="it.name"></div>
                                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400" x-text="it.qty + ' x Rp ' + new Intl.NumberFormat('id-ID').format(it.price)"></div>
                                    <input x-model="it.note" placeholder="Catatan (opsional)" class="mt-2 w-full rounded-xl border border-black/10 bg-white/80 px-3 py-2 text-xs focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40" />
                                </div>
                                <div class="shrink-0 font-semibold" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(it.price * it.qty)"></div>
                            </div>
                        </template>
                        <div x-show="Object.keys(cart).length === 0" class="rounded-2xl border border-black/5 bg-white/60 p-4 text-sm text-gray-600 dark:border-white/10 dark:bg-gray-900/40 dark:text-gray-300">Belum ada item.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
