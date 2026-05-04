<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <title>Laporan Penjualan</title>
        <style>
            @page { margin: 18mm 12mm; }
            body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111827; }
            .muted { color: #6b7280; }
            .h { font-weight: 700; color: #111827; }

            .top { width: 100%; border-collapse: collapse; }
            .top td { vertical-align: top; }
            .brand {
                padding: 0 0 10px 0;
            }
            .brand .store { font-size: 16px; font-weight: 800; letter-spacing: -0.2px; }
            .brand .sub { margin-top: 2px; font-size: 10px; }
            .report {
                text-align: right;
                padding: 0 0 10px 0;
            }
            .report .title { font-size: 14px; font-weight: 800; letter-spacing: -0.2px; }
            .report .sub { margin-top: 4px; font-size: 10px; }

            .summary { width: 100%; border-collapse: collapse; margin-top: 8px; }
            .summary td {
                border: 1px solid #e5e7eb;
                padding: 10px 12px;
                border-radius: 10px;
            }
            .summary .label { font-size: 10px; color: #6b7280; }
            .summary .value { margin-top: 4px; font-size: 13px; font-weight: 800; color: #111827; }

            .tbl { width: 100%; border-collapse: collapse; margin-top: 12px; }
            .tbl th, .tbl td { border-bottom: 1px solid #e5e7eb; padding: 8px 10px; }
            .tbl thead th {
                background: #f8fafc;
                border-top: 1px solid #e5e7eb;
                border-bottom: 1px solid #e5e7eb;
                font-size: 10px;
                text-transform: uppercase;
                letter-spacing: 0.8px;
                color: #374151;
            }
            .tbl tbody tr:nth-child(even) td { background: #fbfbfb; }
            .r { text-align: right; }
            .c { text-align: center; }
            .mono { font-family: DejaVu Sans Mono, DejaVu Sans, monospace; font-size: 10px; }

            .foot { margin-top: 10px; font-size: 10px; color: #6b7280; }
        </style>
    </head>
    <body>
        <table class="top">
            <tr>
                <td class="brand">
                    <div class="store">{{ $settings['store_name'] ?? 'CoffeeShop' }}</div>
                    <div class="sub muted">
                        @if(!empty($settings['address']))
                            {{ $settings['address'] }}
                        @else
                            UMKM Ordering System
                        @endif
                    </div>
                    @if(!empty($settings['whatsapp']))
                        <div class="sub muted">WA: {{ $settings['whatsapp'] }}</div>
                    @endif
                </td>
                <td class="report">
                    <div class="title">Laporan Penjualan</div>
                    <div class="sub muted">Periode: <span class="h">{{ $from->toDateString() }}</span> s/d <span class="h">{{ $to->toDateString() }}</span></div>
                    <div class="sub muted">Dicetak: {{ now()->format('Y-m-d H:i') }}</div>
                </td>
            </tr>
        </table>

        <table class="summary">
            <tr>
                <td style="width: 34%;">
                    <div class="label">Jumlah Order Selesai</div>
                    <div class="value">{{ $orders->count() }}</div>
                </td>
                <td style="width: 33%;">
                    <div class="label">Total Omzet</div>
                    <div class="value">Rp {{ number_format($total, 0, ',', '.') }}</div>
                </td>
                <td style="width: 33%;">
                    <div class="label">Rata-rata / Order</div>
                    <div class="value">
                        Rp {{ number_format($orders->count() ? (int) round($total / $orders->count()) : 0, 0, ',', '.') }}
                    </div>
                </td>
            </tr>
        </table>

        <table class="tbl">
            <thead>
                <tr>
                    <th style="width: 16%;">Tanggal</th>
                    <th style="width: 18%;">Invoice</th>
                    <th style="width: 8%;" class="c">Meja</th>
                    <th style="width: 22%;">Customer</th>
                    <th style="width: 10%;" class="c">Metode</th>
                    <th style="width: 12%;" class="c">Status Bayar</th>
                    <th style="width: 14%;" class="r">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $o)
                    <tr>
                        <td class="muted">{{ $o->created_at?->format('Y-m-d H:i') }}</td>
                        <td class="mono">{{ $o->invoice }}</td>
                        <td class="c">{{ $o->table?->code ?? '-' }}</td>
                        <td>{{ $o->customer_name ?: '-' }}</td>
                        <td class="c">{{ strtoupper($o->payment?->method ?? '-') }}</td>
                        <td class="c">{{ strtoupper($o->payment?->status ?? '-') }}</td>
                        <td class="r h">Rp {{ number_format($o->grand_total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="c muted" style="padding: 16px 10px;">Tidak ada data pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="foot">
            Laporan ini berisi transaksi dengan status selesai pada periode terpilih.
        </div>
    </body>
</html>
