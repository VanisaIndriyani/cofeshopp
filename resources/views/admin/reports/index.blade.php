<x-layouts.admin title="Admin - Laporan" header="Laporan Penjualan" subtitle="Filter tanggal & export Excel/PDF">
    <div class="space-y-5">
        <form class="grid gap-3 rounded-3xl border border-black/5 bg-white/70 p-5 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40 sm:grid-cols-12" method="GET" action="{{ route('admin.reports.index') }}">
            <div class="sm:col-span-4">
                <label class="text-xs font-semibold text-gray-600 dark:text-gray-300">Dari</label>
                <input type="date" name="from" value="{{ $from }}" class="mt-2 w-full rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40" />
            </div>
            <div class="sm:col-span-4">
                <label class="text-xs font-semibold text-gray-600 dark:text-gray-300">Sampai</label>
                <input type="date" name="to" value="{{ $to }}" class="mt-2 w-full rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40" />
            </div>
            <div class="sm:col-span-4 flex items-end gap-2">
                <button class="w-full rounded-2xl bg-gray-950 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-black dark:bg-white dark:text-gray-950">Terapkan</button>
                <a href="{{ route('admin.reports.excel', ['from' => $from, 'to' => $to]) }}" class="w-full rounded-2xl bg-white/80 px-5 py-3 text-center text-sm font-semibold ring-1 ring-black/10 transition hover:bg-white dark:bg-gray-950/40 dark:ring-white/10">Excel</a>
                <a href="{{ route('admin.reports.pdf', ['from' => $from, 'to' => $to]) }}" class="w-full rounded-2xl bg-white/80 px-5 py-3 text-center text-sm font-semibold ring-1 ring-black/10 transition hover:bg-white dark:bg-gray-950/40 dark:ring-white/10">PDF</a>
            </div>
        </form>

        <div class="grid gap-4 sm:grid-cols-2">
            <div class="rounded-3xl border border-black/5 bg-white/70 p-5 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40">
                <div class="text-xs text-gray-600 dark:text-gray-400">Omzet Periode</div>
                <div class="mt-2 text-xl font-semibold">Rp {{ number_format($total, 0, ',', '.') }}</div>
                <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $from }} s/d {{ $to }}</div>
            </div>
            <div class="rounded-3xl border border-black/5 bg-white/70 p-5 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40">
                <div class="text-xs text-gray-600 dark:text-gray-400">Jumlah Order Selesai</div>
                <div class="mt-2 text-xl font-semibold">{{ $orders->total() }}</div>
                <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">Hanya status selesai</div>
            </div>
        </div>

        <div class="overflow-hidden rounded-3xl border border-black/5 bg-white/70 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="border-b border-black/5 bg-white/60 text-xs uppercase tracking-wider text-gray-600 dark:border-white/10 dark:bg-gray-900/40 dark:text-gray-300">
                        <tr>
                            <th class="px-5 py-4">Tanggal</th>
                            <th class="px-5 py-4">Invoice</th>
                            <th class="px-5 py-4">Meja</th>
                            <th class="px-5 py-4">Metode</th>
                            <th class="px-5 py-4">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5 dark:divide-white/10">
                        @foreach($orders as $o)
                            <tr class="bg-white/50 dark:bg-transparent">
                                <td class="px-5 py-4 text-xs text-gray-500 dark:text-gray-400">{{ $o->created_at?->format('d M Y H:i') }}</td>
                                <td class="px-5 py-4 font-semibold">{{ $o->invoice }}</td>
                                <td class="px-5 py-4">{{ $o->table?->code }}</td>
                                <td class="px-5 py-4 text-xs font-semibold">{{ strtoupper($o->payment?->method ?? '-') }}</td>
                                <td class="px-5 py-4 font-semibold">Rp {{ number_format($o->grand_total, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div>{{ $orders->links() }}</div>
    </div>
</x-layouts.admin>
