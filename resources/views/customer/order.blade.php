<x-layouts.customer :title="'Order '.$order->invoice" :brand="($settings['store_name'] ?? 'CoffeeShop')" :settings="$settings">
    <div
        x-data="{
            invoice: @js($order->invoice),
            status: @js($order->status),
            statusLabel: @js($order->status_label),
            async refresh() {
                const res = await fetch(@js(route('order.status', ['invoice' => $order->invoice])));
                if (!res.ok) return;
                const json = await res.json();
                this.status = json.status;
                this.statusLabel = json.status_label;
            },
            stepIndex() {
                const map = { pending: 0, processing: 1, brewing: 2, delivering: 3, completed: 4, cancelled: -1 };
                return map[this.status] ?? 0;
            }
        }"
        x-init="refresh(); setInterval(() => refresh(), 4000)"
        class="space-y-6"
    >
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-slate-900/60">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <div class="text-sm text-gray-500 dark:text-slate-400">Nomor Order</div>
                    <div class="mt-1 text-2xl font-semibold tracking-tight text-gray-900 dark:text-slate-100">{{ $order->invoice }}</div>
                    <div class="mt-2 text-sm text-gray-500 dark:text-slate-400">Meja <span class="font-semibold text-gray-900 dark:text-slate-100">{{ $order->table->code }}</span> • {{ $order->customer_name }}</div>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-[#FFF4E8] p-4 dark:border-white/10 dark:bg-slate-950/30">
                    <div class="text-xs text-gray-500 dark:text-slate-400">Status</div>
                    <div class="mt-1 text-sm font-semibold text-[#8B5E3C]" x-text="statusLabel"></div>
                    <div class="mt-2 text-xs text-gray-500 dark:text-slate-400">Auto refresh setiap 4 detik</div>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-12">
            <div class="lg:col-span-7">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-slate-900/60">
                    <div class="text-sm font-semibold text-gray-900 dark:text-slate-100">Tracking Pesanan</div>
                    <div class="mt-5 space-y-3">
                        @php($steps = [
                            ['k' => 'pending', 't' => 'Menunggu Konfirmasi', 'd' => 'Pesanan masuk, menunggu admin konfirmasi'],
                            ['k' => 'processing', 't' => 'Diproses', 'd' => 'Pesanan diproses oleh admin/kasir'],
                            ['k' => 'brewing', 't' => 'Sedang Dibuat', 'd' => 'Barista mulai membuat pesanan'],
                            ['k' => 'delivering', 't' => 'Siap Diantar', 'd' => 'Pesanan siap diantar ke meja'],
                            ['k' => 'completed', 't' => 'Selesai', 'd' => 'Pesanan sudah diterima, terima kasih!'],
                        ])
                        @foreach($steps as $i => $s)
                            <div class="flex gap-3 rounded-2xl border border-gray-200 bg-[#F8F7F4] p-4 dark:border-white/10 dark:bg-slate-950/30">
                                <div class="mt-0.5">
                                    <div
                                        class="grid h-8 w-8 place-items-center rounded-2xl text-xs font-semibold ring-1"
                                        :class="stepIndex() >= {{ $i }} ? 'bg-[#8B5E3C]/10 text-[#8B5E3C] ring-[#8B5E3C]/15' : 'bg-white text-gray-400 ring-gray-200 dark:bg-slate-900/60 dark:text-slate-500 dark:ring-white/10'"
                                    >{{ $i + 1 }}</div>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-slate-100">{{ $s['t'] }}</div>
                                    <div class="mt-1 text-sm text-gray-600 dark:text-slate-300">{{ $s['d'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-slate-950/30">
                        <div class="text-sm font-semibold text-gray-900 dark:text-slate-100">Pembayaran</div>
                        <div class="mt-2 text-sm text-gray-600 dark:text-slate-300">
                            Metode:
                            <span class="font-semibold text-gray-900 dark:text-slate-100">{{ strtoupper($order->payment?->method ?? '-') }}</span>
                            • Status:
                            <span class="font-semibold text-gray-900 dark:text-slate-100">{{ strtoupper($order->payment?->status ?? '-') }}</span>
                        </div>
                        @if($order->payment?->qris_proof_path)
                            <div class="mt-4">
                                <div class="text-xs text-gray-500 dark:text-slate-400">Bukti transfer</div>
                                <a href="{{ asset('storage/'.$order->payment->qris_proof_path) }}" target="_blank" class="mt-2 block overflow-hidden rounded-2xl ring-1 ring-gray-200 dark:ring-white/10">
                                    <img src="{{ asset('storage/'.$order->payment->qris_proof_path) }}" alt="Bukti QRIS" class="w-full">
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="sticky top-24 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-slate-900/60">
                    <div class="text-sm font-semibold text-gray-900 dark:text-slate-100">Detail Pesanan</div>
                    <div class="mt-4 space-y-3">
                        @foreach($order->items as $it)
                            <div class="flex items-start justify-between gap-3 rounded-2xl border border-gray-200 bg-[#F8F7F4] p-4 dark:border-white/10 dark:bg-slate-950/30">
                                <div class="min-w-0">
                                    <div class="truncate text-sm font-semibold text-gray-900 dark:text-slate-100">{{ $it->product_name }}</div>
                                    <div class="mt-1 text-xs text-gray-500 dark:text-slate-400">{{ $it->qty }} x Rp {{ number_format($it->price, 0, ',', '.') }}</div>
                                    @if($it->note)
                                        <div class="mt-2 text-xs text-gray-500 dark:text-slate-400">Catatan: {{ $it->note }}</div>
                                    @endif
                                </div>
                                <div class="shrink-0 text-sm font-semibold text-gray-900 dark:text-slate-100">Rp {{ number_format($it->subtotal, 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 space-y-2 text-sm text-gray-600 dark:text-slate-300">
                        <div class="flex items-center justify-between">
                            <div>Subtotal</div>
                            <div class="font-semibold text-gray-900 dark:text-slate-100">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>Pajak</div>
                            <div class="font-semibold text-gray-900 dark:text-slate-100">Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>Service</div>
                            <div class="font-semibold text-gray-900 dark:text-slate-100">Rp {{ number_format($order->service_amount, 0, ',', '.') }}</div>
                        </div>
                        <div class="mt-3 flex items-center justify-between rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-slate-950/30">
                            <div class="text-sm font-semibold text-gray-900 dark:text-slate-100">Total</div>
                            <div class="text-lg font-semibold text-[#8B5E3C]">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <div class="mt-5 grid gap-3 sm:grid-cols-2">
                        <a href="{{ route('landing') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-900 shadow-sm transition hover:bg-[#F8F7F4] active:scale-[0.99] dark:border-white/10 dark:bg-slate-950/40 dark:text-slate-100 dark:hover:bg-slate-950">
                            <x-heroicon-o-home class="h-5 w-5 text-[#8B5E3C]" />
                            <span>Landing</span>
                        </a>
                        <a href="{{ route('cart.show') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#8B5E3C] px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#6F4A2D] active:scale-[0.99]">
                            <x-heroicon-o-shopping-bag class="h-5 w-5" />
                            <span>Keranjang</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.customer>
