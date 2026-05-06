<x-layouts.customer :title="($settings['store_name'] ?? 'Way Hitam Coffee').' - Premium Coffee Experience'" :brand="($settings['store_name'] ?? 'Way Hitam Coffee')" :settings="$settings">

<div class="flex flex-col gap-8">
    <!-- Header Banner -->
    <div class="relative overflow-hidden rounded-[2.5rem] border border-gray-200 bg-white p-8 shadow-xl shadow-gray-200/50 dark:border-white/10 dark:bg-slate-900/40">
        <div class="pointer-events-none absolute -left-24 -top-24 h-72 w-72 rounded-full bg-red-500/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-24 -bottom-24 h-72 w-72 rounded-full bg-yellow-500/10 blur-3xl"></div>
        
        <div class="relative flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div>
                <div class="text-xs font-black uppercase tracking-[0.2em] text-gray-400">Selamat Datang di</div>
                <h1 class="mt-2 text-3xl font-black tracking-tight text-gray-950 dark:text-white leading-tight">
                    {{ $settings['store_name'] ?? 'Way Hitam Coffee' }}
                </h1>
                <div class="mt-4 inline-flex items-center gap-3 rounded-[1.25rem] bg-red-600 px-6 py-3 text-sm font-black text-white shadow-2xl shadow-red-600/40">
                    <x-heroicon-s-sparkles class="h-5 w-5 text-yellow-400" />
                    <span>Premium Coffee Experience</span>
                </div>
            </div>
            <div class="max-w-xs text-right md:block hidden">
                <div class="text-sm font-bold text-gray-950 dark:text-white">Tanpa Antri, Pesan dari Meja</div>
                <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">Nikmati kemudahan memesan kopi favorit Anda melalui sistem digital kami.</p>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid gap-8 lg:grid-cols-12">
        <!-- Sidebar Info -->
        <div class="lg:col-span-4">
            <div class="sticky top-24 space-y-6">
                <!-- 3 Steps Section -->
                <div class="rounded-[2rem] border border-gray-200 bg-white p-8 shadow-xl shadow-gray-200/50 dark:border-white/10 dark:bg-slate-900/40">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-1 bg-red-600 rounded-full"></div>
                        <h3 class="text-sm font-black uppercase tracking-widest text-gray-950 dark:text-white">3 Langkah Mudah</h3>
                    </div>
                    
                    <div class="mt-8 space-y-8">
                        <div class="flex items-center gap-4 group">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gray-50 text-lg font-black transition group-hover:bg-red-600 group-hover:text-white dark:bg-slate-800">1</div>
                            <div class="space-y-1">
                                <p class="text-sm font-black text-gray-950 dark:text-white">Scan QR Meja</p>
                                <p class="text-xs font-medium text-gray-500">Scan kode QR yang ada di meja Anda.</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 group">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gray-50 text-lg font-black transition group-hover:bg-red-600 group-hover:text-white dark:bg-slate-800">2</div>
                            <div class="space-y-1">
                                <p class="text-sm font-black text-gray-950 dark:text-white">Pilih Menu</p>
                                <p class="text-xs font-medium text-gray-500">Pilih menu favorit Anda dari daftar.</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 group">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gray-50 text-lg font-black transition group-hover:bg-red-600 group-hover:text-white dark:bg-slate-800">3</div>
                            <div class="space-y-1">
                                <p class="text-sm font-black text-gray-950 dark:text-white">Selesai!</p>
                                <p class="text-xs font-medium text-gray-500">Bayar & pesanan segera diantar.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Featured Menu -->
        <div class="lg:col-span-8">
            <div class="flex flex-col gap-8">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <div class="text-[10px] font-black uppercase tracking-widest text-red-600">Favorit Pelanggan</div>
                        <h2 class="text-2xl font-black tracking-tight text-gray-950 dark:text-white">Menu Best Seller</h2>
                    </div>
                    <a href="{{ route('table.menu', ['code' => 'A1']) }}" class="flex h-12 items-center gap-2 rounded-2xl bg-gray-100 px-6 text-xs font-black uppercase tracking-widest text-gray-900 transition hover:bg-red-600 hover:text-white dark:bg-slate-800 dark:text-white">
                        <span>Lihat Semua</span>
                        <x-heroicon-s-arrow-right class="h-4 w-4" />
                    </a>
                </div>

                <div class="grid gap-6 sm:grid-cols-2">
                    @foreach($featured as $p)
                        <div class="group relative flex flex-col overflow-hidden rounded-[2rem] border border-gray-100 bg-white p-2 shadow-lg transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:shadow-gray-200/50 dark:border-white/5 dark:bg-slate-900/60">
                            <div class="relative aspect-[16/10] overflow-hidden rounded-[1.5rem] bg-gray-100 dark:bg-slate-800">
                                @if($p->photo_path)
                                    <img src="{{ asset('storage/'.$p->photo_path) }}" alt="{{ $p->name }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                                @else
                                    <div class="flex h-full items-center justify-center">
                                        <x-heroicon-o-photo class="h-12 w-12 text-gray-300 dark:text-slate-600" />
                                    </div>
                                @endif
                                <div class="absolute top-4 right-4 rounded-full bg-white/90 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-gray-900 shadow-sm backdrop-blur">
                                    {{ $p->category?->name }}
                                </div>
                            </div>
                            <div class="flex flex-1 flex-col p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <h3 class="text-lg font-bold text-gray-950 dark:text-white line-clamp-1">{{ $p->name }}</h3>
                                    <div class="shrink-0 text-lg font-black text-red-600 dark:text-red-500">
                                        <span class="text-[10px] font-bold text-gray-400">Rp</span>{{ number_format($p->price, 0, ',', '.') }}
                                    </div>
                                </div>
                                <p class="mt-2 line-clamp-2 text-xs leading-relaxed text-gray-500 dark:text-slate-400">{{ $p->description }}</p>
                                
                                <div class="mt-auto pt-5 flex items-center justify-between border-t border-gray-50 dark:border-white/5">
                                    <div class="flex items-center gap-2">
                                        <div class="h-2 w-2 rounded-full bg-red-600 animate-pulse"></div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Tersedia</span>
                                    </div>
                                    <a href="{{ route('table.menu', ['code' => 'A1']) }}" class="bg-red-600 text-white hover:bg-red-700 active:scale-90 shadow-lg shadow-red-600/20 flex h-10 w-10 items-center justify-center rounded-xl transition">
                                        <x-heroicon-o-plus class="h-5 w-5" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Categories Grid -->
                <div class="mt-8 rounded-[2.5rem] bg-gray-950 p-8 text-white shadow-xl shadow-gray-950/20 relative overflow-hidden">
                    <div class="absolute -right-12 -top-12 h-48 w-48 rounded-full bg-red-600/10 blur-3xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-8">
                            <div class="space-y-2">
                                <h3 class="text-xl font-black tracking-tight">Kategori Menu</h3>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-500">Temukan rasa favorit Anda</p>
                            </div>
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/5 text-yellow-400">
                                <x-heroicon-s-squares-2x2 class="h-6 w-6" />
                            </div>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($categories as $c)
                                <a href="{{ route('table.menu', ['code' => 'A1', 'category' => $c->slug]) }}" 
                                   class="group relative overflow-hidden rounded-2xl bg-white/5 p-4 transition-all duration-300 hover:bg-white hover:text-black">
                                    <div class="relative z-10 flex flex-col gap-2">
                                        <div class="h-1.5 w-6 rounded-full bg-red-600 transition-all group-hover:w-full group-hover:bg-yellow-500"></div>
                                        <span class="text-[10px] font-black uppercase tracking-widest">{{ $c->name }}</span>
                                    </div>
                                    <div class="absolute inset-0 -z-0 translate-y-full bg-white transition-transform duration-300 group-hover:translate-y-0"></div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</x-layouts.customer>