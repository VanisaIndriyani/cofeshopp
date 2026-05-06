<x-layouts.customer :title="'Checkout'" :brand="($settings['store_name'] ?? 'Way Hitam Coffee')" :settings="$settings">
    <div class="flex flex-col gap-8">
        <div class="relative overflow-hidden rounded-[2.5rem] border border-gray-200 bg-white p-8 shadow-xl shadow-gray-200/50 dark:border-white/10 dark:bg-slate-900/40">
            <div class="pointer-events-none absolute -left-24 -top-24 h-72 w-72 rounded-full bg-red-500/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -right-24 -bottom-24 h-72 w-72 rounded-full bg-yellow-500/10 blur-3xl"></div>

            <div class="relative flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <div>
                    <div class="text-xs font-black uppercase tracking-[0.2em] text-gray-400">Lokasi Pesanan</div>
                    <div class="mt-3 inline-flex items-center gap-3 rounded-[1.25rem] bg-red-600 px-6 py-3 text-sm font-black text-white shadow-2xl shadow-red-600/40">
                        <x-heroicon-s-qr-code class="h-5 w-5 text-yellow-400" />
                        <span>Meja {{ $table->code }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-3 md:justify-end">
                    <div class="hidden max-w-xs text-right md:block">
                        <div class="text-sm font-bold text-gray-950 dark:text-white">Lengkapi data & pilih pembayaran</div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">Pastikan data benar sebelum konfirmasi pesanan.</p>
                    </div>
                    <a href="{{ route('cart.show') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-gray-200 bg-white px-4 py-3 text-xs font-black uppercase tracking-widest text-gray-900 shadow-sm transition hover:bg-gray-50 active:scale-[0.99] dark:border-white/10 dark:bg-slate-950/40 dark:text-slate-100">
                        <x-heroicon-o-pencil-square class="h-5 w-5 text-red-600" />
                        <span>Ubah</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="grid gap-8 lg:grid-cols-12" x-data="{ method: '{{ old('payment_method', 'cash') }}', preview: null }">
            <div class="lg:col-span-7">
                <div class="rounded-[2.5rem] border border-gray-200 bg-white p-6 shadow-xl shadow-gray-200/50 dark:border-white/10 dark:bg-slate-900/40 sm:p-8">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-1 rounded-full bg-red-600"></div>
                        <h1 class="text-lg font-black uppercase tracking-widest text-gray-950 dark:text-white">Checkout</h1>
                    </div>

                    <form class="mt-8 space-y-8" method="POST" action="{{ route('checkout.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="grid gap-6">
                            <div>
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Nama</label>
                                <input
                                    type="text"
                                    name="customer_name"
                                    value="{{ old('customer_name') }}"
                                    class="mt-3 w-full rounded-2xl border-2 border-gray-100 bg-gray-50 px-6 py-4 text-sm font-bold text-gray-950 outline-none transition focus:border-red-600 focus:bg-white dark:border-white/5 dark:bg-slate-950/40 dark:text-white dark:focus:border-red-500"
                                    placeholder="Masukkan nama kamu"
                                    required
                                />
                                @error('customer_name')<div class="mt-2 text-xs font-bold text-red-600">{{ $message }}</div>@enderror
                            </div>

                            <div>
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Catatan (Opsional)</label>
                                <textarea
                                    name="customer_note"
                                    rows="3"
                                    class="mt-3 w-full resize-none rounded-2xl border-2 border-gray-100 bg-gray-50 px-6 py-4 text-sm font-bold text-gray-950 outline-none transition focus:border-red-600 focus:bg-white dark:border-white/5 dark:bg-slate-950/40 dark:text-white dark:focus:border-red-500"
                                    placeholder="Contoh: Es banyak..."
                                >{{ old('customer_note') }}</textarea>
                                @error('customer_note')<div class="mt-2 text-xs font-bold text-red-600">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="rounded-[2.5rem] bg-gray-50 p-6 dark:bg-slate-800/40 sm:p-8">
                            <div class="flex items-center justify-between gap-3">
                                <div class="text-[10px] font-black uppercase tracking-widest text-gray-400">Metode Pembayaran</div>
                                <div class="text-[10px] font-bold text-gray-400">{{ $transferProofRequired ? 'Bukti wajib untuk QRIS' : 'QRIS tanpa bukti (opsional)' }}</div>
                            </div>

                            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                                <label class="group relative flex cursor-pointer flex-col gap-4 rounded-3xl border-2 bg-white p-6 shadow-sm transition-all duration-300 hover:shadow-xl active:scale-[0.98] dark:bg-slate-900"
                                    :class="method === 'cash' ? 'border-red-600 ring-4 ring-red-600/5' : 'border-gray-100 dark:border-white/5'">
                                    <input type="radio" name="payment_method" value="cash" class="absolute right-6 top-6 h-5 w-5 border-gray-300 text-red-600 focus:ring-red-600" x-model="method">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-100 text-red-600 transition-transform group-hover:scale-110 dark:bg-red-500/20 dark:text-red-400">
                                        <x-heroicon-o-banknotes class="h-7 w-7" />
                                    </div>
                                    <div>
                                        <div class="text-base font-black text-gray-950 dark:text-white">Bayar Cash</div>
                                        <div class="mt-1 text-xs font-bold text-gray-500">Bayar ke kasir saat pesanan tiba</div>
                                    </div>
                                </label>

                                <label class="group relative flex cursor-pointer flex-col gap-4 rounded-3xl border-2 bg-white p-6 shadow-sm transition-all duration-300 hover:shadow-xl active:scale-[0.98] dark:bg-slate-900"
                                    :class="method === 'qris' ? 'border-yellow-500 ring-4 ring-yellow-500/5' : 'border-gray-100 dark:border-white/5'">
                                    <input type="radio" name="payment_method" value="qris" class="absolute right-6 top-6 h-5 w-5 border-gray-300 text-red-600 focus:ring-red-600" x-model="method">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-yellow-100 text-yellow-600 transition-transform group-hover:scale-110 dark:bg-yellow-500/20 dark:text-yellow-400">
                                        <x-heroicon-o-qr-code class="h-7 w-7" />
                                    </div>
                                    <div>
                                        <div class="text-base font-black text-gray-950 dark:text-white">Bayar QRIS</div>
                                        <div class="mt-1 text-xs font-bold text-gray-500">Scan QR dan upload bukti bayar</div>
                                    </div>
                                </label>
                            </div>
                            @error('payment_method')<div class="mt-4 text-xs font-bold text-red-600">{{ $message }}</div>@enderror

                            <div class="mt-8" x-show="method === 'qris'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
                                <div class="grid gap-6 sm:grid-cols-2">
                                    <div class="rounded-3xl border-2 border-gray-100 bg-white p-6 dark:border-white/5 dark:bg-slate-900">
                                        <div class="text-[10px] font-black uppercase tracking-widest text-gray-400">QRIS Toko</div>
                                        <div class="mt-4 overflow-hidden rounded-2xl bg-gray-50 ring-4 ring-gray-50 shadow-inner dark:ring-slate-800">
                                            <img
                                                src="{{ !empty($settings['qris_image_path']) ? asset('storage/'.$settings['qris_image_path']) : asset('img/qr.jpeg') }}"
                                                alt="QRIS"
                                                class="w-full"
                                            >
                                        </div>
                                    </div>

                                    <div class="rounded-3xl border-2 border-gray-100 bg-white p-6 dark:border-white/5 dark:bg-slate-900">
                                        <div class="text-[10px] font-black uppercase tracking-widest text-gray-400">Upload Bukti</div>
                                        <p class="mt-2 text-xs font-bold text-gray-500">
                                            {{ $transferProofRequired ? 'Wajib di-upload untuk konfirmasi.' : 'Opsional, untuk mempercepat proses.' }}
                                        </p>
                                        <div class="mt-6">
                                            <input
                                                type="file"
                                                name="qris_proof"
                                                accept="image/*"
                                                @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
                                                class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-xl file:border-0 file:bg-gray-950 file:px-6 file:py-3 file:text-xs file:font-black file:uppercase file:tracking-widest file:text-white hover:file:bg-black transition dark:file:bg-white dark:file:text-gray-950"
                                            />
                                        </div>
                                        @error('qris_proof')<div class="mt-2 text-xs font-bold text-red-600">{{ $message }}</div>@enderror
                                        <div class="mt-4" x-show="preview" x-cloak>
                                            <img :src="preview" class="h-28 w-auto rounded-2xl border border-gray-100 shadow-sm">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="group relative flex w-full items-center justify-center gap-3 overflow-hidden rounded-[2rem] bg-red-600 px-8 py-6 text-sm font-black uppercase tracking-widest text-white shadow-2xl shadow-red-600/40 transition-all duration-300 hover:bg-red-700 active:scale-95">
                            <div class="absolute inset-0 -z-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:animate-[shimmer_2s_infinite]"></div>
                            <x-heroicon-s-check-badge class="h-6 w-6 text-yellow-400 transition group-hover:scale-110" />
                            <span>Konfirmasi Pesanan</span>
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="space-y-6 lg:sticky lg:top-24">
                    <div class="rounded-[2.5rem] border border-gray-200 bg-white p-6 shadow-xl shadow-gray-200/50 dark:border-white/10 dark:bg-slate-900/40 sm:p-8">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-1 rounded-full bg-yellow-500"></div>
                            <h3 class="text-sm font-black uppercase tracking-widest text-gray-950 dark:text-white">Ringkasan</h3>
                        </div>

                        <div class="mt-8 space-y-4">
                            @foreach($items as $item)
                                <div class="flex items-start justify-between gap-4 rounded-3xl border border-gray-100 bg-gray-50/50 p-4 dark:border-white/5 dark:bg-slate-800/40">
                                    <div class="min-w-0">
                                        <div class="truncate text-sm font-black text-gray-950 dark:text-white">{{ $item['name'] ?? 'Menu' }}</div>
                                        <div class="mt-1 text-xs font-bold text-gray-500">
                                            {{ (int) ($item['qty'] ?? 0) }} x <span class="text-red-600">Rp {{ number_format((int) ($item['price'] ?? 0), 0, ',', '.') }}</span>
                                        </div>
                                        @if(!empty($item['note']))
                                            <div class="mt-2 inline-flex rounded-lg bg-gray-100 px-2 py-1 text-[10px] font-bold text-gray-500 dark:bg-slate-700 dark:text-slate-300">
                                                Catatan: {{ $item['note'] }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="shrink-0 text-sm font-black text-gray-950 dark:text-white">
                                        Rp {{ number_format((int) (($item['qty'] ?? 0) * ($item['price'] ?? 0)), 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8 space-y-3 border-t border-gray-100 pt-8 dark:border-white/5">
                            <div class="flex items-center justify-between text-sm font-bold">
                                <div class="text-gray-500">Subtotal</div>
                                <div class="text-gray-950 dark:text-white">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
                            </div>
                            <div class="flex items-center justify-between text-sm font-bold">
                                <div class="text-gray-500">Pajak ({{ number_format($taxPercent, 0) }}%)</div>
                                <div class="text-gray-950 dark:text-white">Rp {{ number_format($taxAmount, 0, ',', '.') }}</div>
                            </div>
                            <div class="flex items-center justify-between text-sm font-bold">
                                <div class="text-gray-500">Service ({{ number_format($servicePercent, 0) }}%)</div>
                                <div class="text-gray-950 dark:text-white">Rp {{ number_format($serviceAmount, 0, ',', '.') }}</div>
                            </div>
                            <div class="mt-4 flex items-center justify-between rounded-[1.5rem] bg-gray-950 p-6 text-white dark:bg-white dark:text-gray-950">
                                <div class="text-base font-black">Total</div>
                                <div class="text-2xl font-black text-yellow-400 dark:text-red-600">Rp {{ number_format($grandTotal, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl bg-red-600 p-6 text-white shadow-xl shadow-red-600/20">
                        <div class="text-xs font-black uppercase tracking-widest text-red-100">Info</div>
                        <div class="mt-2 text-sm font-bold text-red-50">Pastikan data sudah benar sebelum checkout.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.customer>
