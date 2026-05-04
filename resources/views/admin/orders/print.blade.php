<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Struk {{ $order->invoice }}</title>
        <style>
            body { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; margin: 0; padding: 12px; }
            .w { width: 280px; }
            .c { text-align: center; }
            .r { text-align: right; }
            .muted { color: #666; font-size: 12px; }
            table { width: 100%; border-collapse: collapse; }
            td { padding: 4px 0; vertical-align: top; }
            .line { border-top: 1px dashed #999; margin: 10px 0; }
            @media print { body { padding: 0; } }
        </style>
    </head>
    <body onload="window.print()">
        <div class="w">
            <div class="c">
                <div style="font-weight: 700; font-size: 14px;">{{ \App\Models\Setting::get('store_name', 'CoffeeShop') }}</div>
                <div class="muted">{{ \App\Models\Setting::get('address', '') }}</div>
                <div class="muted">WA: {{ \App\Models\Setting::get('whatsapp', '') }}</div>
            </div>

            <div class="line"></div>

            <table>
                <tr>
                    <td>Invoice</td>
                    <td class="r">{{ $order->invoice }}</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td class="r">{{ $order->created_at?->format('d-m-Y H:i') }}</td>
                </tr>
                <tr>
                    <td>Meja</td>
                    <td class="r">{{ $order->table?->code }}</td>
                </tr>
                <tr>
                    <td>Customer</td>
                    <td class="r">{{ $order->customer_name }}</td>
                </tr>
                <tr>
                    <td>Bayar</td>
                    <td class="r">{{ strtoupper($order->payment?->method ?? '-') }} / {{ strtoupper($order->payment?->status ?? '-') }}</td>
                </tr>
            </table>

            <div class="line"></div>

            <table>
                @foreach($order->items as $it)
                    <tr>
                        <td colspan="2" style="font-weight: 700;">{{ $it->product_name }}</td>
                    </tr>
                    <tr>
                        <td class="muted">{{ $it->qty }} x {{ number_format($it->price, 0, ',', '.') }}</td>
                        <td class="r">{{ number_format($it->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @if($it->note)
                        <tr>
                            <td colspan="2" class="muted">Catatan: {{ $it->note }}</td>
                        </tr>
                    @endif
                @endforeach
            </table>

            <div class="line"></div>

            <table>
                <tr>
                    <td>Subtotal</td>
                    <td class="r">{{ number_format($order->subtotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Pajak</td>
                    <td class="r">{{ number_format($order->tax_amount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Service</td>
                    <td class="r">{{ number_format($order->service_amount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="font-weight: 700;">Total</td>
                    <td class="r" style="font-weight: 700;">{{ number_format($order->grand_total, 0, ',', '.') }}</td>
                </tr>
            </table>

            <div class="line"></div>
            <div class="c muted">Terima kasih!</div>
        </div>
    </body>
</html>
