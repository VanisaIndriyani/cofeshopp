<x-layouts.admin :title="($product->exists ? 'Edit Menu' : 'Tambah Menu')" :header="($product->exists ? 'Edit Menu' : 'Tambah Menu')" subtitle="Kelola menu coffee shop">
    <form
        method="POST"
        action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}"
        enctype="multipart/form-data"
        class="space-y-6"
    >
        @csrf
        @if($product->exists)
            @method('PUT')
        @endif

        <div class="grid gap-4 lg:grid-cols-12">
            <div class="lg:col-span-8">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="text-xs font-semibold text-gray-600 dark:text-gray-300">Nama Menu</label>
                        <input name="name" value="{{ old('name', $product->name) }}" class="mt-2 w-full rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm shadow-sm focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40" required />
                        @error('name')<div class="mt-2 text-xs text-rose-600 dark:text-rose-300">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 dark:text-gray-300">Kategori</label>
                        <select name="category_id" class="mt-2 w-full rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm shadow-sm focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40" required>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}" @selected((int) old('category_id', $product->category_id) === $c->id)>{{ $c->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="mt-2 text-xs text-rose-600 dark:text-rose-300">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 dark:text-gray-300">Harga (Rp)</label>
                        <input type="number" name="price" min="0" value="{{ old('price', $product->price ?? 0) }}" class="mt-2 w-full rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm shadow-sm focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40" required />
                        @error('price')<div class="mt-2 text-xs text-rose-600 dark:text-rose-300">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 dark:text-gray-300">Stok</label>
                        <input type="number" name="stock" min="0" value="{{ old('stock', $product->stock ?? 0) }}" class="mt-2 w-full rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm shadow-sm focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40" required />
                        @error('stock')<div class="mt-2 text-xs text-rose-600 dark:text-rose-300">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 dark:text-gray-300">Batas stok menipis</label>
                        <input type="number" name="low_stock_threshold" min="0" value="{{ old('low_stock_threshold', $product->low_stock_threshold ?? 5) }}" class="mt-2 w-full rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm shadow-sm focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40" />
                        @error('low_stock_threshold')<div class="mt-2 text-xs text-rose-600 dark:text-rose-300">{{ $message }}</div>@enderror
                    </div>

                    <div class="flex items-end gap-4">
                        <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
                            <input type="checkbox" name="is_active" value="1" class="h-4 w-4 rounded border-black/20 text-coffee-600 focus:ring-coffee-400 dark:border-white/20" @checked(old('is_active', $product->is_active ?? true))>
                            <span>Aktif</span>
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
                            <input type="checkbox" name="is_featured" value="1" class="h-4 w-4 rounded border-black/20 text-coffee-600 focus:ring-coffee-400 dark:border-white/20" @checked(old('is_featured', $product->is_featured ?? false))>
                            <span>Featured</span>
                        </label>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="text-xs font-semibold text-gray-600 dark:text-gray-300">Deskripsi</label>
                    <textarea name="description" rows="5" class="mt-2 w-full resize-none rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm shadow-sm focus:border-coffee-400 focus:outline-none dark:border-white/10 dark:bg-gray-950/40">{{ old('description', $product->description) }}</textarea>
                    @error('description')<div class="mt-2 text-xs text-rose-600 dark:text-rose-300">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="lg:col-span-4">
                <div class="rounded-3xl border border-black/5 bg-white/70 p-5 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40">
                    <div class="text-sm font-semibold">Foto Menu</div>
                    <div class="mt-4 overflow-hidden rounded-3xl bg-black/5 ring-1 ring-black/10 dark:bg-white/5 dark:ring-white/10">
                        @if($product->photo_path)
                            <img src="{{ asset('storage/'.$product->photo_path) }}" alt="" class="w-full">
                        @else
                            <div class="flex h-44 items-center justify-center text-xs text-gray-500 dark:text-gray-400">Belum ada foto</div>
                        @endif
                    </div>
                    <input type="file" name="photo" accept="image/*" class="mt-4 block w-full text-sm text-gray-700 dark:text-gray-300 file:mr-4 file:rounded-2xl file:border-0 file:bg-black/5 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-900 hover:file:bg-black/10 dark:file:bg-white/10 dark:file:text-gray-100 dark:hover:file:bg-white/15" />
                    @error('photo')<div class="mt-2 text-xs text-rose-600 dark:text-rose-300">{{ $message }}</div>@enderror
                </div>

                <div class="mt-4 flex gap-3">
                    <a href="{{ route('admin.products.index') }}" class="inline-flex w-1/2 items-center justify-center rounded-2xl bg-white/80 px-5 py-3 text-sm font-semibold ring-1 ring-black/10 transition hover:bg-white dark:bg-gray-950/40 dark:ring-white/10">
                        Batal
                    </a>
                    <button class="inline-flex w-1/2 items-center justify-center rounded-2xl bg-coffee-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-coffee-700">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>
