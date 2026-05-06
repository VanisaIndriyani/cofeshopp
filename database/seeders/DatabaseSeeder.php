<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Setting;
use App\Models\StockHistory;
use App\Models\Table;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::transaction(function () {
            User::query()->updateOrCreate(
                ['email' => 'admin@coffee.com'],
                [
                    'name' => 'Admin Way Hitam Coffee',
                    'password' => Hash::make('password'),
                    'role' => 'admin',
                ]
            );

            Setting::set('store_name', 'Way Hitam Coffee', 'general');
            Setting::set('address', 'Jalan R. Sukamto Jalan. Panca Sari, 8 Ilir, Ilir Tim. II, No. 63A, Kota Palembang, Sumatera Selatan 30164', 'general');
            Setting::set('whatsapp', '081234567890', 'general');
            Setting::set('instagram', 'https://www.instagram.com/coffee_way_hitam/', 'general');
            Setting::set('tax_percent', '0', 'pricing');
            Setting::set('service_percent', '0', 'pricing');
            Setting::set('transfer_proof_required', '0', 'payment');

            $hotCoffee = Category::query()->updateOrCreate(['slug' => 'hot-coffee'], ['name' => 'Hot Coffee', 'sort_order' => 1, 'is_active' => true]);
            $iceCoffee = Category::query()->updateOrCreate(['slug' => 'ice-coffee'], ['name' => 'Ice Coffee', 'sort_order' => 2, 'is_active' => true]);
            $nonCoffee = Category::query()->updateOrCreate(['slug' => 'non-coffee'], ['name' => 'Non Coffee', 'sort_order' => 3, 'is_active' => true]);
            $jus = Category::query()->updateOrCreate(['slug' => 'jus'], ['name' => 'Jus', 'sort_order' => 4, 'is_active' => true]);
            $wedang = Category::query()->updateOrCreate(['slug' => 'wedang'], ['name' => 'Wedang', 'sort_order' => 5, 'is_active' => true]);
            $makanan = Category::query()->updateOrCreate(['slug' => 'makanan'], ['name' => 'Makanan', 'sort_order' => 6, 'is_active' => true]);

            $menus = [
                [$hotCoffee, [
                    ['Kopi Espresso', 12000],
                    ['Kopi Americano', 15000],
                    ['Kopi Tubruk Susu', 15000],
                    ['Kopi Latte', 20000],
                    ['Vietnam Drip', 17000],
                    ['Kopi Sangek', 17000],
                    ['Susu Panas Aren', 17000],
                    ['Kopi Tubruk', 12000],
                ]],
                [$iceCoffee, [
                    ['Cappucino', 17000],
                    ['Es Kopi Gula Aren', 17000],
                    ['Es Kopi Latte', 20000],
                    ['Es Americano', 15000],
                ]],
                [$nonCoffee, [
                    ['Es Gula Aren Susu', 10000],
                    ['Es Coklat', 13000],
                    ['Es Te Tarik', 8000],
                    ['Es Lemon Tea', 8000],
                    ['Es Susu Telur', 18000],
                    ['Es Jeruk', 7000],
                    ['Es Teh', 5000],
                    ['Es Susu Fresmilk', 10000],
                ]],
                [$jus, [
                    ['Jus Mangga', 10000],
                    ['Jus Alpukat', 10000],
                    ['Jus Buah Naga', 10000],
                    ['Jus Mix', 15000],
                    ['Jus Melon', 10000],
                ]],
                [$wedang, [
                    ['Wedang Detox', 16000],
                    ['Wedang Secang', 16000],
                    ['Wedang Purwokerto', 16000],
                    ['Wedang Serai Jeruk', 16000],
                    ['Bandrek Susu', 12000],
                    ['STMJ', 20000],
                ]],
                [$makanan, [
                    ['Siomay', 15000],
                    ['Mie Goreng', 10000],
                    ['Mie Kuah', 10000],
                    ['Pempek Kapal Selam', 13000],
                    ['Pempek Kecil', 2000],
                ]],
            ];

            foreach ($menus as [$cat, $items]) {
                foreach ($items as $idx => [$name, $price]) {
                    Product::query()->updateOrCreate(
                        ['slug' => \Illuminate\Support\Str::slug($name)],
                        [
                            'category_id' => $cat->id,
                            'name' => $name,
                            'price' => $price,
                            'description' => "Minuman favorit dengan rasa autentik dan kualitas premium.",
                            'stock' => rand(15, 60),
                            'low_stock_threshold' => 5,
                            'is_active' => true,
                            'is_featured' => $idx < 2 && $cat->sort_order <= 2,
                        ]
                    );
                }
            }

            for ($i = 1; $i <= 12; $i++) {
                $code = 'A'.$i;
                Table::query()->updateOrCreate(['code' => $code], ['name' => null, 'is_active' => true]);
            }

            $products = Product::query()->active()->get();
            $tables = Table::query()->active()->get();

            $taxPercent = Setting::number('tax_percent', 0);
            $servicePercent = Setting::number('service_percent', 0);

            foreach (range(0, 6) as $daysAgo) {
                foreach (range(1, 2) as $n) {
                    $date = Carbon::today()->subDays($daysAgo)->addHours(rand(8, 21))->addMinutes(rand(0, 59));
                    $table = $tables->random();
                    $picked = $products->random(rand(1, 3));
                    $picked = $picked instanceof \Illuminate\Support\Collection ? $picked : collect([$picked]);

                    $subtotal = 0;
                    $order = Order::create([
                        'table_id' => $table->id,
                        'customer_name' => 'Customer '.$this->randomNameSuffix(),
                        'status' => Order::STATUS_COMPLETED,
                        'ordered_at' => $date,
                        'confirmed_at' => $date->copy()->addMinutes(3),
                        'completed_at' => $date->copy()->addMinutes(25),
                        'tax_percent' => (string) $taxPercent,
                        'service_percent' => (string) $servicePercent,
                    ]);
                    $order->forceFill(['created_at' => $date, 'updated_at' => $date])->saveQuietly();

                    foreach ($picked as $p) {
                        $qty = rand(1, 2);
                        $line = (int) $p->price * $qty;
                        $subtotal += $line;

                        $orderItem = OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $p->id,
                            'product_name' => $p->name,
                            'price' => (int) $p->price,
                            'qty' => $qty,
                            'note' => rand(0, 1) ? null : 'Less sugar',
                            'subtotal' => $line,
                        ]);
                        $orderItem->forceFill(['created_at' => $date, 'updated_at' => $date])->saveQuietly();

                        $before = (int) $p->stock;
                        $after = max(0, $before - $qty);
                        $p->update(['stock' => $after]);

                        $history = StockHistory::create([
                            'product_id' => $p->id,
                            'created_by_user_id' => null,
                            'type' => StockHistory::TYPE_OUT,
                            'qty' => $qty,
                            'stock_before' => $before,
                            'stock_after' => $after,
                            'note' => "Seeder {$order->invoice}",
                        ]);
                        $history->forceFill(['created_at' => $date, 'updated_at' => $date])->saveQuietly();
                    }

                    $taxAmount = (int) round($subtotal * ($taxPercent / 100));
                    $serviceAmount = (int) round($subtotal * ($servicePercent / 100));
                    $grandTotal = $subtotal + $taxAmount + $serviceAmount;

                    $order->update([
                        'subtotal' => $subtotal,
                        'tax_amount' => $taxAmount,
                        'service_amount' => $serviceAmount,
                        'grand_total' => $grandTotal,
                    ]);

                    $payment = Payment::create([
                        'order_id' => $order->id,
                        'method' => rand(0, 1) ? Payment::METHOD_CASH : Payment::METHOD_QRIS,
                        'status' => Payment::STATUS_PAID,
                        'amount' => $grandTotal,
                        'paid_at' => $date->copy()->addMinutes(5),
                    ]);
                    $payment->forceFill(['created_at' => $date, 'updated_at' => $date])->saveQuietly();
                }
            }

            foreach (range(1, 3) as $n) {
                $table = $tables->random();
                $order = Order::create([
                    'table_id' => $table->id,
                    'customer_name' => 'Customer '.$this->randomNameSuffix(),
                    'status' => Order::STATUS_PENDING,
                    'tax_percent' => (string) $taxPercent,
                    'service_percent' => (string) $servicePercent,
                ]);

                $picked = $products->random(rand(1, 2));
                $picked = $picked instanceof \Illuminate\Support\Collection ? $picked : collect([$picked]);
                $subtotal = 0;
                foreach ($picked as $p) {
                    $qty = 1;
                    $line = (int) $p->price * $qty;
                    $subtotal += $line;
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $p->id,
                        'product_name' => $p->name,
                        'price' => (int) $p->price,
                        'qty' => $qty,
                        'note' => null,
                        'subtotal' => $line,
                    ]);
                }

                $taxAmount = (int) round($subtotal * ($taxPercent / 100));
                $serviceAmount = (int) round($subtotal * ($servicePercent / 100));
                $grandTotal = $subtotal + $taxAmount + $serviceAmount;

                $order->update([
                    'subtotal' => $subtotal,
                    'tax_amount' => $taxAmount,
                    'service_amount' => $serviceAmount,
                    'grand_total' => $grandTotal,
                ]);

                Payment::create([
                    'order_id' => $order->id,
                    'method' => Payment::METHOD_QRIS,
                    'status' => Payment::STATUS_UNPAID,
                    'amount' => $grandTotal,
                ]);
            }
        });
    }

    private function randomNameSuffix(): string
    {
        return strtoupper(\Illuminate\Support\Str::random(4));
    }
}
