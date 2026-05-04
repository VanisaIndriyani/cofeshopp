<x-layouts.admin title="Admin - Meja" header="Kelola Meja" subtitle="Generate QR otomatis & download QR">
    <div class="space-y-6">
        <div class="grid gap-4 xl:grid-cols-3">
            <div class="rounded-3xl border border-black/5 bg-white/70 p-5 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40">
                <div class="text-sm font-semibold">Tambah Meja</div>
                <form method="POST" action="{{ route('admin.tables.store') }}" class="mt-4 space-y-3">
                    @csrf
                    <input name="code" placeholder="Kode meja (contoh: A1)" class="w-full rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40" required />
                    <input name="name" placeholder="Nama (opsional)" class="w-full rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40" />
                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
                        <input type="checkbox" name="is_active" value="1" class="h-4 w-4 rounded border-black/20 text-coffee-600 focus:ring-coffee-400 dark:border-white/20" checked>
                        <span>Aktif</span>
                    </label>
                    <button class="w-full rounded-2xl bg-coffee-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-coffee-700">Simpan</button>
                </form>
            </div>

            <div class="xl:col-span-2 rounded-3xl border border-black/5 bg-white/70 p-5 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40">
                <div class="flex items-center justify-between gap-3">
                    <div class="text-sm font-semibold">Daftar Meja</div>
                    <form method="GET" class="flex gap-2" action="{{ route('admin.tables.index') }}">
                        <input name="q" value="{{ $q }}" placeholder="Cari meja..." class="w-64 rounded-2xl border border-black/10 bg-white/80 px-4 py-2 text-sm focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40" />
                        <button class="rounded-2xl bg-gray-950 px-4 py-2 text-sm font-semibold text-white dark:bg-white dark:text-gray-950">Cari</button>
                    </form>
                </div>

                <div class="mt-4 overflow-hidden rounded-3xl border border-black/5 bg-white/60 dark:border-white/10 dark:bg-gray-900/40">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm">
                            <thead class="border-b border-black/5 text-xs uppercase tracking-wider text-gray-600 dark:border-white/10 dark:text-gray-300">
                                <tr>
                                    <th class="px-5 py-4">Kode</th>
                                    <th class="px-5 py-4">Nama</th>
                                    <th class="px-5 py-4">Status</th>
                                    <th class="px-5 py-4">QR</th>
                                    <th class="px-5 py-4"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-black/5 dark:divide-white/10">
                                @foreach($tables as $t)
                                    <tr class="bg-white/50 dark:bg-transparent">
                                        <td class="px-5 py-4 font-semibold">{{ $t->code }}</td>
                                        <td class="px-5 py-4">{{ $t->name ?: '-' }}</td>
                                        <td class="px-5 py-4 text-xs">
                                            <span class="inline-flex rounded-full px-3 py-1 font-semibold ring-1 {{ $t->is_active ? 'bg-emerald-500/10 text-emerald-700 ring-emerald-700/20 dark:text-emerald-300 dark:ring-emerald-300/20' : 'bg-gray-500/10 text-gray-700 ring-gray-700/20 dark:text-gray-300 dark:ring-gray-300/20' }}">
                                                {{ $t->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-2">
                                                <img src="{{ route('table.qr.public', ['code' => $t->code]) }}" alt="QR" class="h-10 w-10 rounded-xl bg-white ring-1 ring-black/10" />
                                                <a href="{{ route('admin.tables.qr', [$t, 'download' => 1]) }}" class="rounded-2xl bg-white/80 px-3 py-2 text-xs font-semibold ring-1 ring-black/10 transition hover:bg-white dark:bg-gray-950/40 dark:ring-white/10">
                                                    Download
                                                </a>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center justify-end gap-2">
                                                <details class="group">
                                                    <summary class="cursor-pointer list-none rounded-2xl bg-white/80 px-4 py-2 text-xs font-semibold ring-1 ring-black/10 transition hover:bg-white dark:bg-gray-950/40 dark:ring-white/10">Edit</summary>
                                                    <div class="mt-2 rounded-3xl border border-black/5 bg-white/70 p-4 shadow-sm dark:border-white/10 dark:bg-gray-950/40">
                                                        <form method="POST" action="{{ route('admin.tables.update', $t) }}" class="space-y-3">
                                                            @csrf
                                                            @method('PUT')
                                                            <input name="code" value="{{ $t->code }}" class="w-full rounded-2xl border border-black/10 bg-white/80 px-4 py-2 text-sm focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40" required />
                                                            <input name="name" value="{{ $t->name }}" class="w-full rounded-2xl border border-black/10 bg-white/80 px-4 py-2 text-sm focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40" />
                                                            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
                                                                <input type="checkbox" name="is_active" value="1" class="h-4 w-4 rounded border-black/20 text-coffee-600 focus:ring-coffee-400 dark:border-white/20" @checked($t->is_active)>
                                                                <span>Aktif</span>
                                                            </label>
                                                            <button class="w-full rounded-2xl bg-coffee-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-coffee-700">Simpan</button>
                                                        </form>
                                                    </div>
                                                </details>
                                                <form method="POST" action="{{ route('admin.tables.destroy', $t) }}" onsubmit="return confirm('Hapus meja ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="rounded-2xl bg-white/80 px-4 py-2 text-xs font-semibold text-rose-600 ring-1 ring-black/10 transition hover:bg-white dark:bg-gray-950/40 dark:text-rose-300 dark:ring-white/10">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-4">{{ $tables->links() }}</div>
            </div>
        </div>
    </div>
</x-layouts.admin>
