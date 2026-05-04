<x-layouts.admin title="Admin - Pengaturan" header="Pengaturan" subtitle="Logo, nama toko, QRIS, pajak, service charge">
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid gap-4 lg:grid-cols-12">
            <div class="lg:col-span-7 space-y-4">
                <div class="rounded-3xl border border-black/5 bg-white/70 p-5 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40">
                    <div class="text-sm font-semibold">Identitas Toko</div>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="text-xs font-semibold text-gray-600 dark:text-gray-300">Nama Toko</label>
                            <input name="store_name" value="{{ old('store_name', $settings['store_name'] ?? 'CoffeeShop') }}" class="mt-2 w-full rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40" required />
                        </div>
                        <div class="sm:col-span-2">
                            <label class="text-xs font-semibold text-gray-600 dark:text-gray-300">Alamat</label>
                            <input name="address" value="{{ old('address', $settings['address'] ?? '') }}" class="mt-2 w-full rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40" />
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-600 dark:text-gray-300">No WA</label>
                            <input name="whatsapp" value="{{ old('whatsapp', $settings['whatsapp'] ?? '') }}" class="mt-2 w-full rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40" />
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-600 dark:text-gray-300">Bukti transfer wajib</label>
                            <label class="mt-3 flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
                                <input type="checkbox" name="transfer_proof_required" value="1" class="h-4 w-4 rounded border-black/20 text-coffee-600 focus:ring-coffee-400 dark:border-white/20" @checked(old('transfer_proof_required', ($settings['transfer_proof_required'] ?? '0') === '1'))>
                                <span>Wajib untuk pembayaran QRIS</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-black/5 bg-white/70 p-5 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40">
                    <div class="text-sm font-semibold">Harga & Biaya</div>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="text-xs font-semibold text-gray-600 dark:text-gray-300">Pajak (%)</label>
                            <input type="number" step="0.01" min="0" max="100" name="tax_percent" value="{{ old('tax_percent', $settings['tax_percent'] ?? 0) }}" class="mt-2 w-full rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40" />
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-600 dark:text-gray-300">Service charge (%)</label>
                            <input type="number" step="0.01" min="0" max="100" name="service_percent" value="{{ old('service_percent', $settings['service_percent'] ?? 0) }}" class="mt-2 w-full rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5 space-y-4">
                <div class="rounded-3xl border border-black/5 bg-white/70 p-5 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40">
                    <div class="text-sm font-semibold">Logo</div>
                    <div class="mt-4 overflow-hidden rounded-3xl bg-black/5 ring-1 ring-black/10 dark:bg-white/5 dark:ring-white/10">
                        @if(!empty($settings['logo_path']))
                            <img src="{{ asset('storage/'.$settings['logo_path']) }}" alt="Logo" class="w-full">
                        @else
                            <div class="flex h-44 items-center justify-center text-xs text-gray-500 dark:text-gray-400">Belum ada logo</div>
                        @endif
                    </div>
                    <input type="file" name="logo" accept="image/*" class="mt-4 block w-full text-sm text-gray-700 dark:text-gray-300 file:mr-4 file:rounded-2xl file:border-0 file:bg-black/5 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-900 hover:file:bg-black/10 dark:file:bg-white/10 dark:file:text-gray-100 dark:hover:file:bg-white/15" />
                </div>

                <div class="rounded-3xl border border-black/5 bg-white/70 p-5 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40">
                    <div class="text-sm font-semibold">QRIS</div>
                    <div class="mt-4 overflow-hidden rounded-3xl bg-black/5 ring-1 ring-black/10 dark:bg-white/5 dark:ring-white/10">
                        @if(!empty($settings['qris_image_path']))
                            <img src="{{ asset('storage/'.$settings['qris_image_path']) }}" alt="QRIS" class="w-full">
                        @else
                            <div class="flex h-44 items-center justify-center text-xs text-gray-500 dark:text-gray-400">Belum ada QRIS</div>
                        @endif
                    </div>
                    <input type="file" name="qris_image" accept="image/*" class="mt-4 block w-full text-sm text-gray-700 dark:text-gray-300 file:mr-4 file:rounded-2xl file:border-0 file:bg-black/5 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-900 hover:file:bg-black/10 dark:file:bg-white/10 dark:file:text-gray-100 dark:hover:file:bg-white/15" />
                </div>

                <button class="w-full rounded-2xl bg-coffee-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-coffee-700">Simpan Pengaturan</button>
            </div>
        </div>
    </form>
</x-layouts.admin>
