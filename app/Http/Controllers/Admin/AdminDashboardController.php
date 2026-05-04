<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function data()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $driver = DB::getDriverName();

        $todaySales = (int) Order::query()
            ->whereDate('created_at', $today)
            ->whereIn('status', [Order::STATUS_COMPLETED])
            ->sum('grand_total');

        $todayOrders = (int) Order::query()
            ->whereDate('created_at', $today)
            ->count();

        $monthSales = (int) Order::query()
            ->where('created_at', '>=', $startOfMonth)
            ->whereIn('status', [Order::STATUS_COMPLETED])
            ->sum('grand_total');

        $outOfStock = (int) Product::query()->where('stock', '<=', 0)->count();
        $pendingOrders = (int) Order::query()->where('status', Order::STATUS_PENDING)->count();

        $days = collect(range(0, 6))->map(fn ($i) => Carbon::today()->subDays(6 - $i)->format('Y-m-d'));

        $salesByDay = Order::query()
            ->selectRaw("date(created_at) as d, sum(grand_total) as total")
            ->where('created_at', '>=', Carbon::today()->subDays(6)->startOfDay())
            ->whereIn('status', [Order::STATUS_COMPLETED])
            ->groupBy('d')
            ->pluck('total', 'd')
            ->toArray();

        $chartSales = $days->map(fn ($d) => (int) ($salesByDay[$d] ?? 0))->values();

        $topProducts = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->selectRaw('order_items.product_name as name, sum(order_items.qty) as qty')
            ->where('orders.created_at', '>=', Carbon::now()->subDays(30)->startOfDay())
            ->where('orders.status', Order::STATUS_COMPLETED)
            ->groupBy('order_items.product_name')
            ->orderByDesc('qty')
            ->limit(7)
            ->get();

        $statusCounts = Order::query()
            ->selectRaw('status, count(*) as c')
            ->whereDate('created_at', $today)
            ->groupBy('status')
            ->pluck('c', 'status')
            ->toArray();

        $statusLabels = [
            Order::STATUS_PENDING => 'Pending',
            Order::STATUS_PROCESSING => 'Diproses',
            Order::STATUS_BREWING => 'Sedang Dibuat',
            Order::STATUS_DELIVERING => 'Siap Diantar',
            Order::STATUS_COMPLETED => 'Selesai',
            Order::STATUS_CANCELLED => 'Batal',
        ];

        $ordersByStatus = collect($statusLabels)
            ->map(fn ($label, $key) => [
                'key' => $key,
                'label' => $label,
                'value' => (int) ($statusCounts[$key] ?? 0),
            ])
            ->values();

        $paymentMethods = DB::table('payments')
            ->join('orders', 'orders.id', '=', 'payments.order_id')
            ->selectRaw('payments.method as method, count(*) as c')
            ->where('orders.created_at', '>=', Carbon::now()->subDays(30)->startOfDay())
            ->where('orders.status', Order::STATUS_COMPLETED)
            ->groupBy('payments.method')
            ->pluck('c', 'method')
            ->toArray();

        $pmLabels = [
            Payment::METHOD_CASH => 'Cash',
            Payment::METHOD_QRIS => 'QRIS',
        ];

        $paymentMethodChart = collect($pmLabels)
            ->map(fn ($label, $key) => [
                'key' => $key,
                'label' => $label,
                'value' => (int) ($paymentMethods[$key] ?? 0),
            ])
            ->values();

        $hourExpr = $driver === 'sqlite' ? "cast(strftime('%H', created_at) as integer)" : 'hour(created_at)';
        $hours = collect(range(8, 22));

        $salesByHour = Order::query()
            ->selectRaw($hourExpr.' as h, sum(grand_total) as total')
            ->whereDate('created_at', $today)
            ->whereIn('status', [Order::STATUS_COMPLETED])
            ->groupBy('h')
            ->pluck('total', 'h')
            ->toArray();

        $salesTodayHourly = [
            'labels' => $hours->map(fn ($h) => str_pad((string) $h, 2, '0', STR_PAD_LEFT).':00')->values(),
            'data' => $hours->map(fn ($h) => (int) ($salesByHour[$h] ?? 0))->values(),
        ];

        return response()->json([
            'stats' => [
                'today_sales' => $todaySales,
                'today_orders' => $todayOrders,
                'month_sales' => $monthSales,
                'out_of_stock' => $outOfStock,
                'pending_orders' => $pendingOrders,
            ],
            'charts' => [
                'sales_7d' => [
                    'labels' => $days->map(fn ($d) => Carbon::parse($d)->format('d M'))->values(),
                    'data' => $chartSales,
                ],
                'top_products' => [
                    'labels' => $topProducts->pluck('name'),
                    'data' => $topProducts->pluck('qty')->map(fn ($v) => (int) $v),
                ],
                'orders_by_status' => [
                    'labels' => $ordersByStatus->pluck('label'),
                    'data' => $ordersByStatus->pluck('value'),
                ],
                'payment_methods' => [
                    'labels' => $paymentMethodChart->pluck('label'),
                    'data' => $paymentMethodChart->pluck('value'),
                ],
                'sales_today_hourly' => $salesTodayHourly,
            ],
        ]);
    }
}
