<x-layouts.admin title="Admin - Menu" header="Kelola Menu" subtitle="CRUD menu, foto, harga, stok, status aktif">
    <div class="space-y-5">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <form class="flex flex-1 flex-col gap-2 sm:flex-row" method="GET" action="{{ route('admin.products.index') }}">
                <input
                    name="q"
                    value="{{ $q }}"
                    placeholder="Cari menu..."
                    class="w-full rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm shadow-sm backdrop-blur focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40"
                />
                <select
                    name="category"
                    class="w-full rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm shadow-sm backdrop-blur focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40 sm:w-64"
                >
                    <option value="">Semua kategori</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->slug }}" @selected($category === $c->slug)>{{ $c->name }}</option>
                    @endforeach
                </select>
                <button class="rounded-2xl bg-gray-950 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-black dark:bg-white dark:text-gray-950">Filter</button>
            </form>
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-coffee-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-coffee-700">
                <x-heroicon-o-plus class="h-5 w-5" />
                <span>Tambah Menu</span>
            </a>
        </div>

        <div class="overflow-hidden rounded-3xl border border-black/5 bg-white/70 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="border-b border-black/5 bg-white/60 text-xs uppercase tracking-wider text-gray-600 dark:border-white/10 dark:bg-gray-900/40 dark:text-gray-300">
                        <tr>
                            <th class="px-5 py-4">Menu</th>
                            <th class="px-5 py-4">Kategori</th>
                            <th class="px-5 py-4">Harga</th>
                            <th class="px-5 py-4">Stok</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5 dark:divide-white/10">
                        @foreach($products as $p)
                            <tr class="bg-white/50 dark:bg-transparent">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 overflow-hidden rounded-2xl bg-black/5 ring-1 ring-black/10 dark:bg-white/5 dark:ring-white/10">
                                            @if($p->photo_path)
                                                <img src="{{ asset('storage/'.$p->photo_path) }}" alt="" class="h-full w-full object-cover">
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="truncate font-semibold">{{ $p->name }}</div>
                                            <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $p->slug }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4">{{ $p->category?->name }}</td>
                                <td class="px-5 py-4 font-semibold">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold ring-1 {{ $p->is_out_of_stock ? 'bg-rose-500/10 text-rose-600 ring-rose-600/20 dark:text-rose-300 dark:ring-rose-300/20' : ($p->is_low_stock ? 'bg-amber-500/10 text-amber-700 ring-amber-700/20 dark:text-amber-300 dark:ring-amber-300/20' : 'bg-emerald-500/10 text-emerald-700 ring-emerald-700/20 dark:text-emerald-300 dark:ring-emerald-300/20') }}">
                                        {{ $p->stock }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-xs">
                                    <div class="font-semibold">{{ $p->is_active ? 'Aktif' : 'Nonaktif' }}</div>
                                    <div class="mt-1 text-gray-500 dark:text-gray-400">{{ $p->is_featured ? 'Featured' : '' }}</div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.products.edit', $p) }}" class="rounded-2xl bg-white/80 px-4 py-2 text-xs font-semibold ring-1 ring-black/10 transition hover:bg-white dark:bg-gray-950/40 dark:ring-white/10">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.products.destroy', $p) }}" onsubmit="return confirm('Hapus menu ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="rounded-2xl bg-white/80 px-4 py-2 text-xs font-semibold text-rose-600 ring-1 ring-black/10 transition hover:bg-white dark:bg-gray-950/40 dark:text-rose-300 dark:ring-white/10">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div>{{ $products->links() }}</div>
    </div>
</x-layouts.admin>
