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
                    'name' => 'Admin CoffeeShop',
                    'password' => Hash::make('password'),
                    'role' => 'admin',
                ]
            );

            Setting::set('store_name', 'CoffeeShop UMKM', 'general');
            Setting::set('address', 'Jl. Contoh No. 123, Kota', 'general');
            Setting::set('whatsapp', '081234567890', 'general');
            Setting::set('tax_percent', '10', 'pricing');
            Setting::set('service_percent', '5', 'pricing');
            Setting::set('transfer_proof_required', '0', 'payment');

            $coffee = Category::query()->updateOrCreate(['slug' => 'coffee'], ['name' => 'Coffee', 'sort_order' => 1, 'is_active' => true]);
            $nonCoffee = Category::query()->updateOrCreate(['slug' => 'non-coffee'], ['name' => 'Non Coffee', 'sort_order' => 2, 'is_active' => true]);
            $snack = Category::query()->updateOrCreate(['slug' => 'snack'], ['name' => 'Snack', 'sort_order' => 3, 'is_active' => true]);

            $menus = [
                [$coffee, [
                    ['Espresso', 18000],
                    ['Americano', 22000],
                    ['Cappuccino', 28000],
                    ['Caramel Latte', 32000],
                    ['Spanish Latte', 34000],
                    ['Mocha', 30000],
                ]],
                [$nonCoffee, [
                    ['Matcha Latte', 32000],
                    ['Chocolate', 28000],
                    ['Red Velvet', 30000],
                    ['Taro Latte', 30000],
                    ['Lemon Tea', 20000],
                    ['Yakult Lychee', 26000],
                ]],
                [$snack, [
                    ['Croissant Butter', 22000],
                    ['French Fries', 20000],
                    ['Chicken Popcorn', 26000],
                    ['Donut Sugar', 16000],
                    ['Brownies', 24000],
                    ['Toast Peanut', 18000],
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
                            'description' => "Signature {$cat->name} dengan rasa premium.",
                            'stock' => rand(15, 60),
                            'low_stock_threshold' => 5,
                            'is_active' => true,
                            'is_featured' => $idx < 2,
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
