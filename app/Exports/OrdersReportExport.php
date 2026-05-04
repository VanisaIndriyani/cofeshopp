<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersReportExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        private readonly Carbon $from,
        private readonly Carbon $to
    ) {}

    public function collection(): Collection
    {
        return Order::query()
            ->with(['table', 'payment'])
            ->whereBetween('created_at', [$this->from, $this->to])
            ->where('status', Order::STATUS_COMPLETED)
            ->orderBy('created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Invoice',
            'Meja',
            'Customer',
            'Metode',
            'Status Bayar',
            'Subtotal',
            'Pajak',
            'Service',
            'Total',
        ];
    }

    public function map($row): array
    {
        return [
            $row->created_at?->format('Y-m-d H:i'),
            $row->invoice,
            $row->table?->code,
            $row->customer_name,
            $row->payment?->method,
            $row->payment?->status,
            $row->subtotal,
            $row->tax_amount,
            $row->service_amount,
            $row->grand_total,
        ];
    }
}
