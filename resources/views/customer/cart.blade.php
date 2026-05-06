<x-layouts.customer :title="'Keranjang'" :brand="($settings['store_name'] ?? 'CoffeeShop')" :settings="$settings">

<div class="px-3 sm:px-4 lg:px-0">
    <div class="grid gap-4 sm:gap-6 lg:grid-cols-12">

        <!-- ================= LEFT ================= -->
        <div class="lg:col-span-8">
            <div class="rounded-2xl border bg-white p-4 sm:p-6 lg:p-8 shadow">

                <!-- HEADER -->
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold">Keranjang</h2>
                        <p class="text-xs text-gray-500">
                            Meja: <span class="text-red-600 font-bold">{{ $cart['table_code'] ?? '-' }}</span>
                        </p>
                    </div>

                    @if($items->isNotEmpty())
                    <form method="POST" action="{{ route('cart.clear') }}">
                        @csrf
                        <button class="text-xs sm:text-sm px-3 py-2 border rounded-lg text-red-600 hover:bg-red-600 hover:text-white">
                            Kosongkan
                        </button>
                    </form>
                    @endif
                </div>

                <!-- LIST ITEM -->
                <div class="mt-5 space-y-4">

                    @forelse($items as $item)
                    <div class="border rounded-xl p-3 sm:p-4 bg-gray-50">

                        <div class="flex gap-3">

                            <!-- IMAGE -->
                            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-lg overflow-hidden bg-white">
                                @if(!empty($item['photo_path']))
                                    <img src="{{ asset('storage/'.$item['photo_path']) }}" class="w-full h-full object-cover">
                                @endif
                            </div>

                            <!-- INFO -->
                            <div class="flex-1">

                                <div class="flex justify-between">
                                    <h3 class="text-sm sm:text-base font-bold truncate">
                                        {{ $item['name'] }}
                                    </h3>

                                    <form method="POST" action="{{ route('cart.remove') }}">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                        <button class="text-red-600 text-sm">✕</button>
                                    </form>
                                </div>

                                <p class="text-xs text-red-600 font-bold">
                                    Rp {{ number_format($item['price']) }}
                                </p>

                                <!-- FORM UPDATE -->
                                <form method="POST" action="{{ route('cart.update') }}"
                                      class="mt-3 grid grid-cols-1 sm:grid-cols-3 gap-2">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">

                                    <!-- QTY -->
                                    <input type="number" name="qty"
                                        value="{{ $item['qty'] }}"
                                        class="border rounded-lg px-2 py-2 text-sm">

                                    <!-- NOTE -->
                                    <input type="text" name="note"
                                        value="{{ $item['note'] }}"
                                        placeholder="Catatan"
                                        class="border rounded-lg px-2 py-2 text-sm sm:col-span-1">

                                    <!-- BUTTON -->
                                    <button class="bg-gray-900 text-white rounded-lg text-xs py-2">
                                        Simpan
                                    </button>

                                </form>

                            </div>
                        </div>

                    </div>
                    @empty

                    <!-- EMPTY -->
                    <div class="text-center py-10">
                        <p class="text-gray-500">Keranjang kosong</p>

                        <a href="{{ route('table.menu', ['code' => $cart['table_code'] ?? 'A1']) }}"
                           class="inline-block mt-4 bg-red-600 text-white px-4 py-2 rounded-lg text-sm">
                            Mulai Pesan
                        </a>
                    </div>

                    @endforelse

                </div>
            </div>
        </div>

        <!-- ================= RIGHT ================= -->
        <div class="lg:col-span-4">

            <!-- ❗ mobile biasa, desktop sticky -->
            <div class="mt-4 lg:mt-0 lg:sticky lg:top-24 space-y-4">

                <div class="border rounded-2xl bg-white p-4 shadow">

                    <h3 class="font-bold mb-3 text-sm">Ringkasan</h3>

                    <div class="flex justify-between text-sm">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($subtotal) }}</span>
                    </div>

                    <hr class="my-3">

                    <div class="flex justify-between font-bold">
                        <span>Total</span>
                        <span class="text-red-600">
                            Rp {{ number_format($subtotal) }}
                        </span>
                    </div>

                </div>

                <!-- BUTTON -->
                <a href="{{ route('checkout.show') }}"
                   class="block text-center bg-red-600 text-white py-3 rounded-xl font-bold
                   {{ $items->isEmpty() ? 'opacity-40 pointer-events-none' : '' }}">
                    Checkout
                </a>

                <a href="{{ route('table.menu', ['code' => $cart['table_code'] ?? 'A1']) }}"
                   class="block text-center border py-3 rounded-xl font-bold">
                    Tambah Menu
                </a>

            </div>
        </div>

    </div>
</div>

</x-layouts.customer>