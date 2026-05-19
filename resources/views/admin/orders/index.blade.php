<x-layouts.admin title="Admin - Pesanan" header="Pesanan" subtitle="Kelola pesanan realtime, update status, dan print struk">
    <div class="space-y-5" x-data="{
        detailOpen: false,
        detailHtml: '',
        detailBaseUrl: @js(url('/admin/orders')),
        async openDetail(id) {
            try {
                const res = await fetch(`${this.detailBaseUrl}/${id}`);
                if (!res.ok) return;
                const json = await res.json();
                this.detailHtml = json.html ?? '';
                this.detailOpen = true;
            } catch (e) {}
        }
    }">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <form class="flex flex-1 flex-col gap-2 sm:flex-row" method="GET" action="{{ route('admin.orders.index') }}">
                <input
                    name="q"
                    value="{{ $q }}"
                    placeholder="Cari invoice / customer..."
                    class="w-full rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm shadow-sm backdrop-blur focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40"
                />
                <select
                    name="status"
                    class="w-full rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm shadow-sm backdrop-blur focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40 sm:w-64"
                >
                    <option value="">Semua status</option>
                    @foreach([\App\Models\Order::STATUS_PENDING => 'Menunggu', \App\Models\Order::STATUS_PROCESSING => 'Diproses', \App\Models\Order::STATUS_BREWING => 'Sedang Dibuat', \App\Models\Order::STATUS_DELIVERING => 'Siap Diantar', \App\Models\Order::STATUS_COMPLETED => 'Selesai', \App\Models\Order::STATUS_CANCELLED => 'Batal'] as $k => $v)
                        <option value="{{ $k }}" @selected($status === $k)>{{ $v }}</option>
                    @endforeach
                </select>
                <button class="rounded-2xl bg-gray-950 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-black dark:bg-white dark:text-gray-950">Filter</button>
            </form>
            <div class="text-sm text-gray-600 dark:text-gray-400">Total: {{ $orders->total() }}</div>
        </div>

        <div class="overflow-hidden rounded-3xl border border-black/5 bg-white/70 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="border-b border-black/5 bg-white/60 text-xs uppercase tracking-wider text-gray-600 dark:border-white/10 dark:bg-gray-900/40 dark:text-gray-300">
                        <tr>
                            <th class="px-5 py-4">Invoice</th>
                            <th class="px-5 py-4">Meja</th>
                            <th class="px-5 py-4">Customer</th>
                            <th class="px-5 py-4">Total</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4">Bayar</th>
                            <th class="px-5 py-4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5 dark:divide-white/10">
                        @foreach($orders as $o)
                            <tr class="bg-white/50 dark:bg-transparent">
                                <td class="px-5 py-4">
                                    <div class="font-semibold">{{ $o->invoice }}</div>
                                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $o->created_at?->format('d M Y H:i') }}</div>
                                </td>
                                <td class="px-5 py-4 font-semibold">{{ $o->table?->code }}</td>
                                <td class="px-5 py-4">{{ $o->customer_name }}</td>
                                <td class="px-5 py-4 font-semibold">Rp {{ number_format($o->grand_total, 0, ',', '.') }}</td>
                                <td class="px-5 py-4">
                                    <div class="inline-flex rounded-full bg-coffee-600/10 px-3 py-1 text-xs font-semibold text-coffee-700 ring-1 ring-coffee-700/20 dark:bg-cream-200/10 dark:text-cream-200 dark:ring-cream-200/20">
                                        {{ $o->status_label }}
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-xs">
                                    <div class="font-semibold">{{ strtoupper($o->payment?->method ?? '-') }}</div>
                                    <div class="mt-1 text-gray-500 dark:text-gray-400">{{ strtoupper($o->payment?->status ?? '-') }}</div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex flex-wrap items-center justify-end gap-2">
                                        <button type="button" class="rounded-2xl bg-white/80 px-4 py-2 text-xs font-semibold ring-1 ring-black/10 transition hover:bg-white dark:bg-gray-950/40 dark:ring-white/10" @click="openDetail({{ $o->id }})">
                                            Detail
                                        </button>
                                        <a href="{{ route('admin.orders.print', $o) }}" target="_blank" class="rounded-2xl bg-white/80 px-4 py-2 text-xs font-semibold ring-1 ring-black/10 transition hover:bg-white dark:bg-gray-950/40 dark:ring-white/10">
                                            Print
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            {{ $orders->links() }}
        </div>

        <x-modal name="order-detail" :show="false" maxWidth="2xl">
            <div class="p-6" x-show="detailOpen" x-transition.opacity>
                <div class="flex items-start justify-between gap-3">
                    <div class="text-lg font-semibold">Detail Pesanan</div>
                    <button type="button" class="rounded-xl bg-black/5 p-2 text-gray-700 transition hover:bg-black/10 dark:bg-white/10 dark:text-gray-200 dark:hover:bg-white/15" @click="detailOpen = false">
                        <x-heroicon-o-x-mark class="h-5 w-5" />
                    </button>
                </div>
                <div class="mt-4" x-html="detailHtml"></div>
            </div>
        </x-modal>

        <template x-effect="detailOpen ? $dispatch('open-modal', 'order-detail') : $dispatch('close-modal', 'order-detail')"></template>
    </div>
</x-layouts.admin>
