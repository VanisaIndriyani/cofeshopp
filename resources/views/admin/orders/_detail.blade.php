<div class="space-y-4">
    <div class="grid gap-3 sm:grid-cols-2">
        <div class="rounded-2xl border border-black/5 bg-white/70 p-4 text-sm dark:border-white/10 dark:bg-gray-950/40">
            <div class="text-xs text-gray-500 dark:text-gray-400">Invoice</div>
            <div class="mt-1 font-semibold">{{ $order->invoice }}</div>
            <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">Meja</div>
            <div class="mt-1 font-semibold">{{ $order->table?->code }}</div>
        </div>
        <div class="rounded-2xl border border-black/5 bg-white/70 p-4 text-sm dark:border-white/10 dark:bg-gray-950/40">
            <div class="text-xs text-gray-500 dark:text-gray-400">Customer</div>
            <div class="mt-1 font-semibold">{{ $order->customer_name }}</div>
            <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">Catatan</div>
            <div class="mt-1 text-gray-700 dark:text-gray-200">{{ $order->customer_note ?: '-' }}</div>
        </div>
    </div>

    <div class="rounded-2xl border border-black/5 bg-white/70 p-4 text-sm dark:border-white/10 dark:bg-gray-950/40">
        <div class="flex items-center justify-between">
            <div class="font-semibold">Item</div>
            <div class="font-semibold">Total: Rp {{ number_format($order->grand_total, 0, ',', '.') }}</div>
        </div>
        <div class="mt-3 divide-y divide-black/5 dark:divide-white/10">
            @foreach($order->items as $it)
                <div class="flex items-start justify-between gap-3 py-3">
                    <div class="min-w-0">
                        <div class="truncate font-semibold">{{ $it->product_name }}</div>
                        <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $it->qty }} x Rp {{ number_format($it->price, 0, ',', '.') }}</div>
                        @if($it->note)
                            <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">Catatan: {{ $it->note }}</div>
                        @endif
                    </div>
                    <div class="shrink-0 font-semibold">Rp {{ number_format($it->subtotal, 0, ',', '.') }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="grid gap-3 sm:grid-cols-2">
        <div class="rounded-2xl border border-black/5 bg-white/70 p-4 text-sm dark:border-white/10 dark:bg-gray-950/40">
            <div class="text-xs text-gray-500 dark:text-gray-400">Pembayaran</div>
            <div class="mt-1 font-semibold">{{ strtoupper($order->payment?->method ?? '-') }} • {{ strtoupper($order->payment?->status ?? '-') }}</div>
            @if($order->payment?->qris_proof_path)
                <a href="{{ asset('storage/'.$order->payment->qris_proof_path) }}" target="_blank" class="mt-3 inline-flex items-center gap-2 rounded-xl bg-white/80 px-3 py-2 text-xs font-semibold ring-1 ring-black/10 transition hover:bg-white dark:bg-gray-950/40 dark:ring-white/10">
                    <x-heroicon-o-photo class="h-4 w-4" />
                    <span>Lihat bukti</span>
                </a>
            @endif
        </div>
        <div class="rounded-2xl border border-black/5 bg-white/70 p-4 text-sm dark:border-white/10 dark:bg-gray-950/40">
            <div class="text-xs text-gray-500 dark:text-gray-400">Update Status</div>
            <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="mt-3 space-y-3">
                @csrf
                <select name="status" class="w-full rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm shadow-sm backdrop-blur focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40">
                    @foreach([\App\Models\Order::STATUS_PENDING => 'Menunggu Konfirmasi', \App\Models\Order::STATUS_PROCESSING => 'Diproses', \App\Models\Order::STATUS_BREWING => 'Sedang Dibuat', \App\Models\Order::STATUS_DELIVERING => 'Siap Diantar', \App\Models\Order::STATUS_COMPLETED => 'Selesai', \App\Models\Order::STATUS_CANCELLED => 'Dibatalkan'] as $k => $v)
                        <option value="{{ $k }}" @selected($order->status === $k)>{{ $v }}</option>
                    @endforeach
                </select>
                <label class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-300">
                    <input type="checkbox" name="mark_paid" value="1" class="h-4 w-4 rounded border-black/20 text-coffee-600 focus:ring-coffee-400 dark:border-white/20">
                    <span>Set pembayaran jadi PAID</span>
                </label>
                <button class="w-full rounded-2xl bg-gray-950 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-black dark:bg-white dark:text-gray-950">
                    Simpan
                </button>
            </form>
        </div>
    </div>
</div>
