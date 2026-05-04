<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use App\Exports\OrdersReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->query('from')
            ? Carbon::parse((string) $request->query('from'))->startOfDay()
            : Carbon::today()->subDays(6)->startOfDay();

        $to = $request->query('to')
            ? Carbon::parse((string) $request->query('to'))->endOfDay()
            : Carbon::today()->endOfDay();

        $orders = Order::query()
            ->with(['table', 'payment'])
            ->whereBetween('created_at', [$from, $to])
            ->where('status', Order::STATUS_COMPLETED)
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $total = (int) Order::query()
            ->whereBetween('created_at', [$from, $to])
            ->where('status', Order::STATUS_COMPLETED)
            ->sum('grand_total');

        return view('admin.reports.index', [
            'orders' => $orders,
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
            'total' => $total,
        ]);
    }

    public function exportExcel(Request $request)
    {
        $from = Carbon::parse((string) $request->query('from', Carbon::today()->subDays(6)->toDateString()))->startOfDay();
        $to = Carbon::parse((string) $request->query('to', Carbon::today()->toDateString()))->endOfDay();

        return Excel::download(new OrdersReportExport($from, $to), "Laporan-Penjualan-{$from->format('Ymd')}-{$to->format('Ymd')}.xlsx");
    }

    public function exportPdf(Request $request)
    {
        $from = Carbon::parse((string) $request->query('from', Carbon::today()->subDays(6)->toDateString()))->startOfDay();
        $to = Carbon::parse((string) $request->query('to', Carbon::today()->toDateString()))->endOfDay();

        $orders = Order::query()
            ->with(['table', 'payment'])
            ->whereBetween('created_at', [$from, $to])
            ->where('status', Order::STATUS_COMPLETED)
            ->orderBy('created_at')
            ->get();

        $total = (int) $orders->sum('grand_total');
        $settings = Setting::allAsArray();

        $pdf = Pdf::loadView('admin.reports.pdf', [
            'orders' => $orders,
            'from' => $from,
            'to' => $to,
            'total' => $total,
            'settings' => $settings,
        ])->setPaper('a4', 'landscape');

        return $pdf->download("Laporan-Penjualan-{$from->format('Ymd')}-{$to->format('Ymd')}.pdf");
    }
}
