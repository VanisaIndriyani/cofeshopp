<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');

        $query = Order::query()
            ->with(['table', 'payment'])
            ->orderByDesc('created_at');

        if ($q !== '') {
            $query->where(function ($qq) use ($q) {
                $qq->where('invoice', 'like', "%{$q}%")
                    ->orWhere('customer_name', 'like', "%{$q}%");
            });
        }

        if ($status !== '') {
            $query->where('status', $status);
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'q' => $q,
            'status' => $status,
        ]);
    }

    public function show(Order $order)
    {
        $order->load(['items', 'table', 'payment']);

        return response()->json([
            'ok' => true,
            'html' => view('admin.orders._detail', ['order' => $order])->render(),
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in([
                Order::STATUS_PENDING,
                Order::STATUS_PROCESSING,
                Order::STATUS_BREWING,
                Order::STATUS_DELIVERING,
                Order::STATUS_COMPLETED,
                Order::STATUS_CANCELLED,
            ])],
            'mark_paid' => ['nullable', 'boolean'],
        ]);

        $next = $data['status'];

        $updates = ['status' => $next];
        if ($next === Order::STATUS_PROCESSING && ! $order->confirmed_at) {
            $updates['confirmed_at'] = Carbon::now();
        }
        if ($next === Order::STATUS_COMPLETED) {
            $updates['completed_at'] = Carbon::now();
        }

        $order->update($updates);

        if ($request->boolean('mark_paid') && $order->payment) {
            $order->payment->update([
                'status' => Payment::STATUS_PAID,
                'paid_at' => Carbon::now(),
            ]);
        }

        $request->session()->flash('toast', ['type' => 'success', 'message' => 'Status pesanan diperbarui.']);
        return back();
    }

    public function print(Order $order)
    {
        $order->load(['items', 'table', 'payment']);

        return view('admin.orders.print', [
            'order' => $order,
        ]);
    }

    public function pendingCount()
    {
        $count = (int) Order::query()
            ->where('status', Order::STATUS_PENDING)
            ->count();

        return response()->json(['count' => $count]);
    }
}
