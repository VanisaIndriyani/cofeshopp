<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    public function show(string $invoice)
    {
        $settings = Setting::allAsArray();

        $order = Order::query()
            ->where('invoice', $invoice)
            ->with(['items', 'table', 'payment'])
            ->firstOrFail();

        return view('customer.order', [
            'settings' => $settings,
            'order' => $order,
        ]);
    }

    public function status(string $invoice)
    {
        $order = Order::query()
            ->where('invoice', $invoice)
            ->with(['payment'])
            ->firstOrFail();

        return response()->json([
            'invoice' => $order->invoice,
            'status' => $order->status,
            'status_label' => $order->status_label,
            'payment' => $order->payment ? [
                'method' => $order->payment->method,
                'status' => $order->payment->status,
            ] : null,
            'updated_at' => optional($order->updated_at)->toISOString(),
        ]);
    }
}
