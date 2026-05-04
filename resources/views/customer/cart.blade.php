<x-layouts.customer :title="'Keranjang'" :brand="($settings['store_name'] ?? 'CoffeeShop')" :settings="$settings">
    <div class="grid gap-6 lg:grid-cols-12">
        <div class="lg:col-span-8">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-slate-900/60">
                <div class="h-1 rounded-full bg-gradient-to-r from-[#8B5E3C] via-amber-400 to-sky-400"></div>
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-semibold text-gray-900 dark:text-slate-100">Keranjang</div>
                        <div class="mt-1 text-sm text-gray-500 dark:text-slate-400">Meja: <span class="font-semibold text-gray-900 dark:text-slate-100">{{ $cart['table_code'] ?? '-' }}</span></div>
                    </div>
                    @if($items->isNotEmpty())
                        <form method="POST" action="{{ route('cart.clear') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 rounded-2xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm transition hover:bg-[#F8F7F4] active:scale-[0.99] dark:border-white/10 dark:bg-slate-950/40 dark:text-slate-100 dark:hover:bg-slate-950">
                                <x-heroicon-o-trash class="h-5 w-5 text-rose-600 dark:text-rose-300" />
                                <span>Kosongkan</span>
                            </button>
                        </form>
                    @endif
                </div>

                <div class="mt-6 space-y-4">
                    @forelse($items as $item)
                        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-slate-950/30">
                            <div class="flex items-start gap-4">
                                <div class="h-16 w-16 overflow-hidden rounded-2xl bg-[#F8F7F4] ring-1 ring-gray-200 dark:bg-slate-800 dark:ring-white/10">
                                    @if(!empty($item['photo_path']))
                                        <img src="{{ asset('storage/'.$item['photo_path']) }}" alt="" class="h-full w-full object-cover">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center">
                                            <x-heroicon-o-photo class="h-6 w-6 text-gray-300 dark:text-slate-600" />
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <div class="truncate text-sm font-semibold text-gray-900 dark:text-slate-100">{{ $item['name'] ?? 'Menu' }}</div>
                                            <div class="mt-1 text-xs text-gray-500 dark:text-slate-400">Rp {{ number_format((int) ($item['price'] ?? 0), 0, ',', '.') }}</div>
                                        </div>
                                        <form method="POST" action="{{ route('cart.remove') }}">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                            <button type="submit" class="rounded-xl border border-gray-200 bg-white p-2 text-gray-500 shadow-sm transition hover:bg-[#F8F7F4] hover:text-gray-900 active:scale-[0.99] dark:border-white/10 dark:bg-slate-950/40 dark:text-slate-300 dark:hover:bg-slate-950 dark:hover:text-white">
                                                <x-heroicon-o-x-mark class="h-5 w-5" />
                                            </button>
                                        </form>
                                    </div>

                                    <form method="POST" action="{{ route('cart.update') }}" class="mt-4 grid gap-3 sm:grid-cols-12">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                        <div class="sm:col-span-3">
                                            <label class="text-xs font-semibold text-gray-600 dark:text-slate-300">Qty</label>
                                            <input
                                                type="number"
                                                min="0"
                                                name="qty"
                                                value="{{ (int) ($item['qty'] ?? 0) }}"
                                                class="mt-2 w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition focus:border-[#8B5E3C]/50 focus:ring-4 focus:ring-[#8B5E3C]/10 dark:border-white/10 dark:bg-slate-950/40 dark:text-slate-100"
                                            />
                                        </div>
                                        <div class="sm:col-span-7">
                                            <label class="text-xs font-semibold text-gray-600 dark:text-slate-300">Catatan</label>
                                            <input
                                                type="text"
                                                name="note"
                                                value="{{ $item['note'] ?? '' }}"
                                                placeholder="Contoh: less sugar / no ice"
                                                class="mt-2 w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 shadow-sm outline-none transition focus:border-[#8B5E3C]/50 focus:ring-4 focus:ring-[#8B5E3C]/10 dark:border-white/10 dark:bg-slate-950/40 dark:text-slate-100 dark:placeholder:text-slate-500"
                                            />
                                        </div>
                                        <div class="sm:col-span-2 sm:flex sm:items-end">
                                            <button type="submit" class="w-full rounded-2xl bg-[#8B5E3C] px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#6F4A2D] active:scale-[0.99]">
                                                Update
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-gray-200 bg-white p-6 text-sm text-gray-600 shadow-sm dark:border-white/10 dark:bg-slate-900/60 dark:text-slate-300">
                            Keranjang masih kosong. Scan QR meja lalu pilih menu.
                            <div class="mt-4">
                                <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 rounded-2xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm transition hover:bg-[#F8F7F4] active:scale-[0.99] dark:border-white/10 dark:bg-slate-950/40 dark:text-slate-100 dark:hover:bg-slate-950">
                                    <x-heroicon-o-arrow-left class="h-5 w-5" />
                                    <span>Kembali</span>
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="lg:col-span-4">
            <div class="sticky top-24 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-slate-900/60">
                <div class="h-1 rounded-full bg-gradient-to-r from-[#8B5E3C] via-amber-400 to-sky-400"></div>
                <div class="text-sm font-semibold text-gray-900 dark:text-slate-100">Ringkasan</div>
                <div class="mt-4 space-y-2 text-sm text-gray-600 dark:text-slate-300">
                    <div class="flex items-center justify-between">
                        <div>Subtotal</div>
                        <div class="font-semibold text-gray-900 dark:text-slate-100">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="mt-5 border-t border-gray-200 pt-5 dark:border-white/10">
                    <a
                        href="{{ route('checkout.show') }}"
                        class="{{ $items->isEmpty() ? 'pointer-events-none opacity-40' : '' }} inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-[#8B5E3C] px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#6F4A2D] active:scale-[0.99]"
                    >
                        <x-heroicon-o-credit-card class="h-5 w-5" />
                        <span>Checkout</span>
                    </a>
                    <div class="mt-3">
                        <a href="{{ $cart['table_code'] ? route('table.menu', ['code' => $cart['table_code']]) : route('landing') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-gray-200 bg-white px-5 py-3 text-sm font-semibold text-gray-900 shadow-sm transition hover:bg-[#F8F7F4] active:scale-[0.99] dark:border-white/10 dark:bg-slate-950/40 dark:text-slate-100 dark:hover:bg-slate-950">
                            <x-heroicon-o-plus class="h-5 w-5 text-[#8B5E3C]" />
                            <span>Tambah Menu</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.customer>
