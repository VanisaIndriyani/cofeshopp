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
        class="space-y-8"
    >
        <div class="relative overflow-hidden rounded-[2.5rem] border border-gray-200 bg-white p-8 shadow-xl shadow-gray-200/50 dark:border-white/10 dark:bg-slate-900/40">
            <div class="pointer-events-none absolute -left-24 -top-24 h-72 w-72 rounded-full bg-red-500/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -right-24 -bottom-24 h-72 w-72 rounded-full bg-yellow-500/10 blur-3xl"></div>

            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-3">
                    <div class="text-xs font-black uppercase tracking-[0.2em] text-gray-400">Nomor Order</div>
                    <div class="text-2xl sm:text-3xl font-black tracking-tight text-gray-950 dark:text-white">{{ $order->invoice }}</div>
                    <div class="text-sm font-bold text-gray-500 dark:text-slate-400">
                        Meja <span class="text-red-600">{{ $order->table->code }}</span> • {{ $order->customer_name }}
                    </div>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="rounded-3xl border-2 border-red-100 bg-red-50 px-6 py-5 shadow-sm dark:border-red-900/30 dark:bg-red-950/20">
                        <div class="text-[10px] font-black uppercase tracking-widest text-red-400">Status</div>
                        <div class="mt-2 text-2xl font-black text-red-600" x-text="statusLabel"></div>
                        <div class="mt-2 flex items-center gap-2 text-[10px] font-bold text-red-400">
                            <div class="h-2 w-2 rounded-full bg-red-500 animate-ping"></div>
                            <span>Auto refresh</span>
                        </div>
                    </div>
                    <a href="{{ route('landing') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-gray-200 bg-white px-5 py-4 text-xs font-black uppercase tracking-widest text-gray-900 shadow-sm transition hover:bg-gray-50 active:scale-[0.99] dark:border-white/10 dark:bg-slate-950/40 dark:text-slate-100">
                        <x-heroicon-o-home class="h-5 w-5 text-red-600" />
                        <span>Beranda</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="grid gap-8 lg:grid-cols-12">
            <div class="lg:col-span-7">
                <div class="rounded-[2.5rem] border border-gray-100 bg-white p-6 shadow-xl shadow-gray-200/50 dark:border-white/5 dark:bg-slate-900/40 sm:p-8">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-1 bg-red-600 rounded-full"></div>
                        <h3 class="text-sm font-black uppercase tracking-widest text-gray-950 dark:text-white">Tracking Progress</h3>
                    </div>

                    <div class="mt-8 space-y-4">
                        @php($steps = [
                            ['k' => 'pending', 't' => 'Menunggu Konfirmasi', 'd' => 'Pesanan masuk, menunggu admin konfirmasi'],
                            ['k' => 'processing', 't' => 'Diproses', 'd' => 'Pesanan diproses oleh admin/kasir'],
                            ['k' => 'brewing', 't' => 'Sedang Dibuat', 'd' => 'Barista mulai membuat pesanan'],
                            ['k' => 'delivering', 't' => 'Siap Diantar', 'd' => 'Pesanan siap diantar ke meja'],
                            ['k' => 'completed', 't' => 'Selesai', 'd' => 'Pesanan sudah diterima, terima kasih!'],
                        ])
                        @foreach($steps as $i => $s)
                            <div class="group relative flex gap-4 rounded-3xl border-2 p-6 transition-all duration-300"
                                :class="stepIndex() >= {{ $i }} ? 'border-red-600 bg-white shadow-lg shadow-red-600/10' : 'border-gray-100 bg-gray-50/60 opacity-60 dark:border-white/5 dark:bg-slate-800/30'">
                                <div class="shrink-0">
                                    <div
                                        class="grid h-10 w-10 place-items-center rounded-2xl text-sm font-black transition-colors"
                                        :class="stepIndex() >= {{ $i }} ? 'bg-red-600 text-white shadow-lg shadow-red-600/20' : 'bg-white text-gray-400 border-2 border-gray-100 dark:bg-slate-900 dark:border-white/10'"
                                    >{{ $i + 1 }}</div>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-base font-black text-gray-950 dark:text-white">{{ $s['t'] }}</div>
                                    <div class="mt-1 text-xs font-bold leading-relaxed text-gray-500 dark:text-slate-400">{{ $s['d'] }}</div>
                                </div>
                                <template x-if="stepIndex() === {{ $i }}">
                                    <div class="absolute right-6 top-1/2 -translate-y-1/2">
                                        <div class="h-3 w-3 rounded-full bg-red-600 animate-ping"></div>
                                    </div>
                                </template>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 rounded-[2rem] border border-gray-100 bg-gray-50/50 p-6 dark:border-white/5 dark:bg-slate-800/30">
                        <div class="flex items-center justify-between gap-4">
                            <div class="text-sm font-black text-gray-950 dark:text-white">Pembayaran</div>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center rounded-full bg-white px-3 py-1 text-[10px] font-black uppercase tracking-widest text-gray-900 ring-1 ring-gray-100 dark:bg-slate-900 dark:text-white dark:ring-white/10">
                                    {{ strtoupper($order->payment?->method ?? '-') }}
                                </span>
                                <span class="inline-flex items-center rounded-full bg-red-600 px-3 py-1 text-[10px] font-black uppercase tracking-widest text-white">
                                    {{ strtoupper($order->payment?->status ?? '-') }}
                                </span>
                            </div>
                        </div>
                        @if($order->payment?->qris_proof_path)
                            <div class="mt-5">
                                <div class="text-[10px] font-black uppercase tracking-widest text-gray-400">Bukti QRIS</div>
                                <a href="{{ asset('storage/'.$order->payment->qris_proof_path) }}" target="_blank" class="mt-3 block overflow-hidden rounded-2xl ring-1 ring-gray-200 dark:ring-white/10">
                                    <img src="{{ asset('storage/'.$order->payment->qris_proof_path) }}" alt="Bukti QRIS" class="w-full">
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="space-y-6 lg:sticky lg:top-24">
                    <div class="rounded-[2.5rem] border border-gray-200 bg-white p-6 shadow-xl shadow-gray-200/50 dark:border-white/10 dark:bg-slate-900/40 sm:p-8">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-1 bg-yellow-500 rounded-full"></div>
                            <div class="text-sm font-black uppercase tracking-widest text-gray-950 dark:text-white">Detail Pesanan</div>
                        </div>

                        <div class="mt-8 space-y-4">
                            @foreach($order->items as $it)
                                <div class="flex items-start justify-between gap-4 rounded-3xl border border-gray-100 bg-gray-50/50 p-4 dark:border-white/5 dark:bg-slate-800/40">
                                    <div class="min-w-0">
                                        <div class="truncate text-sm font-black text-gray-950 dark:text-white">{{ $it->product_name }}</div>
                                        <div class="mt-1 text-xs font-bold text-gray-500 dark:text-slate-400">{{ $it->qty }} x <span class="text-red-600">Rp {{ number_format($it->price, 0, ',', '.') }}</span></div>
                                        @if($it->note)
                                            <div class="mt-2 inline-flex rounded-lg bg-gray-100 px-2 py-1 text-[10px] font-bold text-gray-500 dark:bg-slate-700 dark:text-slate-300">Catatan: {{ $it->note }}</div>
                                        @endif
                                    </div>
                                    <div class="shrink-0 text-sm font-black text-gray-950 dark:text-white">Rp {{ number_format($it->subtotal, 0, ',', '.') }}</div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8 space-y-3 border-t border-gray-100 pt-8 dark:border-white/5">
                            <div class="flex items-center justify-between text-sm font-bold">
                                <div class="text-gray-500">Subtotal</div>
                                <div class="text-gray-950 dark:text-white">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</div>
                            </div>
                            <div class="flex items-center justify-between text-sm font-bold">
                                <div class="text-gray-500">Pajak</div>
                                <div class="text-gray-950 dark:text-white">Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</div>
                            </div>
                            <div class="flex items-center justify-between text-sm font-bold">
                                <div class="text-gray-500">Service</div>
                                <div class="text-gray-950 dark:text-white">Rp {{ number_format($order->service_amount, 0, ',', '.') }}</div>
                            </div>
                            <div class="mt-4 flex items-center justify-between rounded-[1.5rem] bg-gray-950 p-6 text-white dark:bg-white dark:text-gray-950">
                                <div class="text-base font-black">Total</div>
                                <div class="text-2xl font-black text-yellow-400 dark:text-red-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-3 sm:grid-cols-2">
                            <a href="{{ route('table.menu', ['code' => $order->table->code]) }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-900 shadow-sm transition hover:bg-gray-50 active:scale-[0.99] dark:border-white/10 dark:bg-slate-950/40 dark:text-slate-100">
                                <x-heroicon-o-clipboard-document-list class="h-5 w-5 text-red-600" />
                                <span>Menu</span>
                            </a>
                            <a href="{{ route('cart.show') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-red-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700 active:scale-[0.99]">
                                <x-heroicon-o-shopping-bag class="h-5 w-5" />
                                <span>Keranjang</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.customer>
