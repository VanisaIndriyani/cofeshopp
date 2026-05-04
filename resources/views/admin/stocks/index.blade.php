<x-layouts.admin title="Admin - Stok" header="Kelola Stok" subtitle="Stok masuk/keluar, histori, notifikasi stok menipis">
    <div class="space-y-6">
        <div class="grid gap-4 xl:grid-cols-3">
            <div class="rounded-3xl border border-black/5 bg-white/70 p-5 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40">
                <div class="text-sm font-semibold">Catat Stok</div>
                <form method="POST" action="{{ route('admin.stocks.store') }}" class="mt-4 space-y-3">
                    @csrf
                    <select name="product_id" class="w-full rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40" required>
                        @foreach($productOptions as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                    <select name="type" class="w-full rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40" required>
                        <option value="{{ \App\Models\StockHistory::TYPE_IN }}">Stok Masuk</option>
                        <option value="{{ \App\Models\StockHistory::TYPE_OUT }}">Stok Keluar</option>
                        <option value="{{ \App\Models\StockHistory::TYPE_ADJUST }}">Set Stok (Adjust)</option>
                    </select>
                    <input type="number" name="qty" min="1" value="1" class="w-full rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40" required />
                    <input name="note" placeholder="Catatan (opsional)" class="w-full rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40" />
                    <button class="w-full rounded-2xl bg-coffee-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-coffee-700">Simpan</button>
                </form>
            </div>

            <div class="xl:col-span-2 rounded-3xl border border-black/5 bg-white/70 p-5 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40">
                <div class="flex items-center justify-between gap-3">
                    <div class="text-sm font-semibold">Produk & Stok</div>
                    <form method="GET" class="flex gap-2" action="{{ route('admin.stocks.index') }}">
                        <input name="q" value="{{ $q }}" placeholder="Cari produk..." class="w-64 rounded-2xl border border-black/10 bg-white/80 px-4 py-2 text-sm focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40" />
                        <button class="rounded-2xl bg-gray-950 px-4 py-2 text-sm font-semibold text-white dark:bg-white dark:text-gray-950">Cari</button>
                    </form>
                </div>

                <div class="mt-4 overflow-hidden rounded-3xl border border-black/5 bg-white/60 dark:border-white/10 dark:bg-gray-900/40">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm">
                            <thead class="border-b border-black/5 text-xs uppercase tracking-wider text-gray-600 dark:border-white/10 dark:text-gray-300">
                                <tr>
                                    <th class="px-5 py-4">Produk</th>
                                    <th class="px-5 py-4">Kategori</th>
                                    <th class="px-5 py-4">Stok</th>
                                    <th class="px-5 py-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-black/5 dark:divide-white/10">
                                @foreach($products as $p)
                                    <tr class="bg-white/50 dark:bg-transparent">
                                        <td class="px-5 py-4 font-semibold">{{ $p->name }}</td>
                                        <td class="px-5 py-4">{{ $p->category?->name }}</td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold ring-1 {{ $p->is_out_of_stock ? 'bg-rose-500/10 text-rose-600 ring-rose-600/20 dark:text-rose-300 dark:ring-rose-300/20' : ($p->is_low_stock ? 'bg-amber-500/10 text-amber-700 ring-amber-700/20 dark:text-amber-300 dark:ring-amber-300/20' : 'bg-emerald-500/10 text-emerald-700 ring-emerald-700/20 dark:text-emerald-300 dark:ring-emerald-300/20') }}">
                                                {{ $p->stock }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-xs text-gray-600 dark:text-gray-300">
                                            @if($p->is_out_of_stock)
                                                Habis
                                            @elseif($p->is_low_stock)
                                                Menipis
                                            @else
                                                Aman
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-4">{{ $products->links() }}</div>
            </div>
        </div>

        <div class="rounded-3xl border border-black/5 bg-white/70 p-5 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40">
            <div class="text-sm font-semibold">Histori Stok</div>
            <div class="mt-4 overflow-hidden rounded-3xl border border-black/5 bg-white/60 dark:border-white/10 dark:bg-gray-900/40">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="border-b border-black/5 text-xs uppercase tracking-wider text-gray-600 dark:border-white/10 dark:text-gray-300">
                            <tr>
                                <th class="px-5 py-4">Tanggal</th>
                                <th class="px-5 py-4">Produk</th>
                                <th class="px-5 py-4">Tipe</th>
                                <th class="px-5 py-4">Qty</th>
                                <th class="px-5 py-4">Before → After</th>
                                <th class="px-5 py-4">User</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/5 dark:divide-white/10">
                            @foreach($histories as $h)
                                <tr class="bg-white/50 dark:bg-transparent">
                                    <td class="px-5 py-4 text-xs text-gray-500 dark:text-gray-400">{{ $h->created_at?->format('d M Y H:i') }}</td>
                                    <td class="px-5 py-4 font-semibold">{{ $h->product?->name }}</td>
                                    <td class="px-5 py-4 text-xs font-semibold">{{ strtoupper($h->type) }}</td>
                                    <td class="px-5 py-4">{{ $h->qty }}</td>
                                    <td class="px-5 py-4 text-xs text-gray-600 dark:text-gray-300">{{ $h->stock_before }} → {{ $h->stock_after }}</td>
                                    <td class="px-5 py-4 text-xs text-gray-600 dark:text-gray-300">{{ $h->createdBy?->name ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-4">{{ $histories->links() }}</div>
        </div>
    </div>
</x-layouts.admin>
