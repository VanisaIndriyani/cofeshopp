<x-layouts.customer :title="'Checkout'" :brand="($settings['store_name'] ?? 'CoffeeShop')" :settings="$settings">
    <div class="grid gap-6 lg:grid-cols-12">
        <div class="lg:col-span-7">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-slate-900/60">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-semibold text-gray-900 dark:text-slate-100">Checkout</div>
                        <div class="mt-1 text-sm text-gray-500 dark:text-slate-400">Meja <span class="font-semibold text-gray-900 dark:text-slate-100">{{ $table->code }}</span> • Tanpa login</div>
                    </div>
                    <a href="{{ route('cart.show') }}" class="inline-flex items-center gap-2 rounded-2xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm transition hover:bg-[#F8F7F4] active:scale-[0.99] dark:border-white/10 dark:bg-slate-950/40 dark:text-slate-100 dark:hover:bg-slate-950">
                        <x-heroicon-o-pencil-square class="h-5 w-5 text-[#8B5E3C]" />
                        <span>Edit keranjang</span>
                    </a>
                </div>

                <form
                    class="mt-6 space-y-5"
                    method="POST"
                    action="{{ route('checkout.store') }}"
                    enctype="multipart/form-data"
                    x-data="{ method: '{{ old('payment_method', 'cash') }}' }"
                >
                    @csrf

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="text-xs font-semibold text-gray-600 dark:text-slate-300">Nama Customer</label>
                            <input
                                type="text"
                                name="customer_name"
                                value="{{ old('customer_name') }}"
                                class="mt-2 w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 shadow-sm outline-none transition focus:border-[#8B5E3C]/50 focus:ring-4 focus:ring-[#8B5E3C]/10 dark:border-white/10 dark:bg-slate-950/40 dark:text-slate-100 dark:placeholder:text-slate-500"
                                placeholder="Masukkan nama kamu"
                                required
                            />
                            @error('customer_name')<div class="mt-2 text-xs text-rose-600 dark:text-rose-300">{{ $message }}</div>@enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="text-xs font-semibold text-gray-600 dark:text-slate-300">Catatan Pesanan (opsional)</label>
                            <textarea
                                name="customer_note"
                                rows="3"
                                class="mt-2 w-full resize-none rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 shadow-sm outline-none transition focus:border-[#8B5E3C]/50 focus:ring-4 focus:ring-[#8B5E3C]/10 dark:border-white/10 dark:bg-slate-950/40 dark:text-slate-100 dark:placeholder:text-slate-500"
                                placeholder="Contoh: antar pelan-pelan / no sugar"
                            >{{ old('customer_note') }}</textarea>
                            @error('customer_note')<div class="mt-2 text-xs text-rose-600 dark:text-rose-300">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-[#FFF4E8] p-5 dark:border-white/10 dark:bg-slate-950/30">
                        <div class="text-sm font-semibold text-gray-900 dark:text-slate-100">Metode Pembayaran</div>
                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm transition hover:bg-[#F8F7F4] active:scale-[0.99] dark:border-white/10 dark:bg-slate-900/60 dark:hover:bg-slate-900">
                                <input type="radio" name="payment_method" value="cash" class="h-4 w-4 border-slate-300 text-[#8B5E3C] focus:ring-[#8B5E3C]" x-model="method">
                                <div class="flex-1">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-slate-100">Cash</div>
                                    <div class="text-xs text-gray-500 dark:text-slate-400">Bayar ke kasir saat pesanan diantar</div>
                                </div>
                                <x-heroicon-o-banknotes class="h-6 w-6 text-[#8B5E3C]" />
                            </label>

                            <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm transition hover:bg-[#F8F7F4] active:scale-[0.99] dark:border-white/10 dark:bg-slate-900/60 dark:hover:bg-slate-900">
                                <input type="radio" name="payment_method" value="qris" class="h-4 w-4 border-slate-300 text-[#8B5E3C] focus:ring-[#8B5E3C]" x-model="method">
                                <div class="flex-1">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-slate-100">QRIS</div>
                                    <div class="text-xs text-gray-500 dark:text-slate-400">Scan QRIS toko dan upload bukti (opsional sesuai setting)</div>
                                </div>
                                <x-heroicon-o-qr-code class="h-6 w-6 text-[#8B5E3C]" />
                            </label>
                        </div>
                        @error('payment_method')<div class="mt-2 text-xs text-rose-600 dark:text-rose-300">{{ $message }}</div>@enderror

                        <div class="mt-4" x-show="method === 'qris'" x-cloak>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-slate-900/60">
                                    <div class="text-xs font-semibold text-gray-600 dark:text-slate-300">QRIS Toko</div>
                                    <div class="mt-3 overflow-hidden rounded-2xl bg-[#F8F7F4] ring-1 ring-gray-200 dark:bg-slate-950/30 dark:ring-white/10">
                                        @if(!empty($settings['qris_image_path']))
                                            <img src="{{ asset('storage/'.$settings['qris_image_path']) }}" alt="QRIS" class="w-full">
                                        @else
                                            <div class="flex h-44 items-center justify-center text-xs text-gray-500 dark:text-slate-400">QRIS belum di-upload admin</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-slate-900/60">
                                    <div class="text-xs font-semibold text-gray-600 dark:text-slate-300">Upload Bukti</div>
                                    <div class="mt-2 text-xs text-gray-500 dark:text-slate-400">
                                        {{ $transferProofRequired ? 'Wajib upload bukti sesuai setting admin.' : 'Opsional, jika ingin mempercepat konfirmasi.' }}
                                    </div>
                                    <input
                                        type="file"
                                        name="qris_proof"
                                        accept="image/*"
                                        class="mt-4 block w-full text-sm text-gray-600 file:mr-4 file:rounded-xl file:border file:border-gray-200 file:bg-white file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-900 hover:file:bg-[#F8F7F4] dark:text-slate-300 dark:file:border-white/10 dark:file:bg-slate-950/40 dark:file:text-slate-100 dark:hover:file:bg-slate-950"
                                    />
                                    @error('qris_proof')<div class="mt-2 text-xs text-rose-600 dark:text-rose-300">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-[#8B5E3C] px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#6F4A2D] active:scale-[0.99]">
                        <x-heroicon-o-check-badge class="h-5 w-5" />
                        <span>Buat Pesanan</span>
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-5">
            <div class="sticky top-24 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-slate-900/60">
                <div class="text-sm font-semibold text-gray-900 dark:text-slate-100">Ringkasan Pesanan</div>

                <div class="mt-4 space-y-3">
                    @foreach($items as $item)
                        <div class="flex items-start justify-between gap-3 rounded-2xl border border-gray-200 bg-[#F8F7F4] p-4 dark:border-white/10 dark:bg-slate-950/30">
                            <div class="min-w-0">
                                <div class="truncate text-sm font-semibold text-gray-900 dark:text-slate-100">{{ $item['name'] ?? 'Menu' }}</div>
                                <div class="mt-1 text-xs text-gray-500 dark:text-slate-400">{{ (int) ($item['qty'] ?? 0) }} x Rp {{ number_format((int) ($item['price'] ?? 0), 0, ',', '.') }}</div>
                                @if(!empty($item['note']))
                                    <div class="mt-2 text-xs text-gray-500 dark:text-slate-400">Catatan: {{ $item['note'] }}</div>
                                @endif
                            </div>
                            <div class="shrink-0 text-sm font-semibold text-gray-900 dark:text-slate-100">Rp {{ number_format(((int) ($item['price'] ?? 0)) * ((int) ($item['qty'] ?? 0)), 0, ',', '.') }}</div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 space-y-2 text-sm text-gray-600 dark:text-slate-300">
                    <div class="flex items-center justify-between">
                        <div>Subtotal</div>
                        <div class="font-semibold text-gray-900 dark:text-slate-100">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>Pajak ({{ number_format($taxPercent, 2, ',', '.') }}%)</div>
                        <div class="font-semibold text-gray-900 dark:text-slate-100">Rp {{ number_format($taxAmount, 0, ',', '.') }}</div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>Service ({{ number_format($servicePercent, 2, ',', '.') }}%)</div>
                        <div class="font-semibold text-gray-900 dark:text-slate-100">Rp {{ number_format($serviceAmount, 0, ',', '.') }}</div>
                    </div>
                    <div class="mt-3 flex items-center justify-between rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-slate-950/30">
                        <div class="text-sm font-semibold text-gray-900 dark:text-slate-100">Total</div>
                        <div class="text-lg font-semibold text-[#8B5E3C]">Rp {{ number_format($grandTotal, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.customer>
