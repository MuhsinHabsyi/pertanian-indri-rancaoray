<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota #{{ $order->nota_number ?? $order->id }} — Tani Rancaoray</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier New', Courier, monospace;
            background: #f5f5f5;
            padding: 30px;
            color: #1a1a1a;
        }
        .nota-wrapper {
            max-width: 520px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        /* Header */
        .nota-header {
            background: #1a4d2e;
            color: #fff;
            padding: 22px 28px 18px;
            text-align: center;
        }
        .nota-header h1 { font-size: 18px; font-family: Arial, sans-serif; letter-spacing: 1px; }
        .nota-header p  { font-size: 11px; color: rgba(255,255,255,0.7); margin-top: 4px; }

        /* Nota number badge */
        .nota-number {
            text-align: center;
            padding: 12px 28px;
            background: #f8fdf9;
            border-bottom: 1px dashed #ccc;
        }
        .nota-number span {
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 2px;
            color: #1a4d2e;
            background: #e8f5e9;
            border: 1px dashed #2e7d32;
            padding: 6px 18px;
            border-radius: 4px;
            display: inline-block;
        }

        /* Body */
        .nota-body { padding: 20px 28px; }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            font-size: 11.5px;
            margin-bottom: 20px;
        }
        .info-block .label { color: #888; margin-bottom: 2px; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        .info-block .value { font-weight: bold; color: #222; word-break: break-word; }

        /* Divider */
        .divider { border: none; border-top: 1px dashed #ccc; margin: 16px 0; }

        /* Items table */
        table { width: 100%; font-size: 11.5px; border-collapse: collapse; }
        thead th {
            font-size: 10px;
            text-transform: uppercase;
            color: #555;
            letter-spacing: 0.5px;
            padding: 6px 4px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        thead th:last-child { text-align: right; }
        tbody td { padding: 8px 4px; border-bottom: 1px solid #f0f0f0; vertical-align: top; }
        tbody td:last-child { text-align: right; font-weight: bold; }
        tfoot td { padding: 8px 4px; font-size: 11.5px; }
        tfoot tr.total-row td {
            border-top: 1.5px solid #222;
            font-weight: bold;
            font-size: 13px;
            color: #1a4d2e;
        }
        .text-right { text-align: right; }
        .text-muted { color: #888; font-size: 10px; }

        /* Footer */
        .nota-footer {
            background: #f8fdf9;
            border-top: 1px dashed #ccc;
            padding: 16px 28px;
            text-align: center;
            font-size: 10.5px;
            color: #666;
            line-height: 1.7;
        }
        .nota-footer strong { color: #1a4d2e; }

        /* Stamp */
        .stamp-area {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            font-size: 11px;
            color: #555;
        }
        .stamp-box { text-align: center; }
        .stamp-box .line { border-top: 1px solid #aaa; margin-top: 48px; padding-top: 6px; width: 130px; }

        /* Print actions */
        .print-actions {
            text-align: center;
            margin-top: 24px;
        }
        .btn-print {
            background: #1a4d2e;
            color: #fff;
            border: none;
            padding: 10px 28px;
            border-radius: 6px;
            font-size: 13px;
            font-family: Arial, sans-serif;
            cursor: pointer;
            margin-right: 8px;
        }
        .btn-back {
            background: #fff;
            color: #555;
            border: 1px solid #ccc;
            padding: 10px 28px;
            border-radius: 6px;
            font-size: 13px;
            font-family: Arial, sans-serif;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-print:hover { background: #145a32; }
        .btn-back:hover  { background: #f5f5f5; }

        @media print {
            body { background: #fff; padding: 0; }
            .nota-wrapper { box-shadow: none; border: none; border-radius: 0; }
            .print-actions { display: none !important; }
        }
    </style>
</head>
<body>

<div class="nota-wrapper">
    {{-- Header --}}
    <div class="nota-header">
        <h1>TANI RANCAORAY</h1>
        <p>Pertanian Organik Rancaoray · Jawa Barat</p>
    </div>

    {{-- Nomor Nota --}}
    <div class="nota-number">
        <div style="font-size:10px; color:#888; margin-bottom:6px; text-transform:uppercase; letter-spacing:1px;">Nomor Nota</div>
        <span>{{ $order->nota_number ?? ('TRX-' . str_pad($order->id, 4, '0', STR_PAD_LEFT)) }}</span>
    </div>

    {{-- Body --}}
    <div class="nota-body">

        {{-- Info Transaksi --}}
        <div class="info-grid" style="margin-bottom:14px;">
            <div class="info-block">
                <div class="label">Tanggal Transaksi</div>
                <div class="value">{{ $order->transaction_date ? $order->transaction_date->format('d M Y, H:i') : '-' }}</div>
            </div>
            <div class="info-block">
                <div class="label">Metode Pembayaran</div>
                <div class="value">{{ $order->payment_method }}</div>
            </div>
            <div class="info-block">
                <div class="label">Pembeli</div>
                <div class="value">{{ $order->customer->full_name ?? 'Pelanggan #' . $order->customer_id }}</div>
            </div>
            <div class="info-block">
                <div class="label">Kanal Penjualan</div>
                <div class="value">{{ $order->sale_channel ?? 'Offline' }}</div>
            </div>
            @if($order->recipient_name)
            <div class="info-block">
                <div class="label">Penerima</div>
                <div class="value">{{ $order->recipient_name }} {{ $order->recipient_phone ? '(' . $order->recipient_phone . ')' : '' }}</div>
            </div>
            <div class="info-block">
                <div class="label">Kurir</div>
                <div class="value">
                    @if($order->delivery_courier === 'Rancaoray')
                        Diantar Langsung (6283116581808)
                    @elseif($order->delivery_courier === 'JNT')
                        JNT Express {{ $order->courier_phone ? '(' . $order->courier_phone . ')' : '' }}
                    @else
                        {{ $order->delivery_courier ?? '-' }}
                    @endif
                </div>
            </div>
            @endif
            <div class="info-block" style="grid-column:span 2;">
                <div class="label">Alamat Pengiriman</div>
                <div class="value">{{ $order->shipping_address }}</div>
            </div>
        </div>

        <hr class="divider">

        {{-- Tabel Item --}}
        <table>
            <thead>
                <tr>
                    <th style="width:5%">#</th>
                    <th>Produk</th>
                    <th style="text-align:right; width:18%">Qty (Kg)</th>
                    <th style="text-align:right; width:22%">Harga/Kg</th>
                    <th style="text-align:right; width:22%">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>
                        <strong>{{ $item->product->name ?? 'Produk #' . $item->product_id }}</strong>
                        @if($item->product && $item->product->category)
                            <br><span class="text-muted">{{ $item->product->category }}</span>
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($item->quantity, 2, ',', '.') }}</td>
                    <td class="text-right">Rp{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right" style="color:#555; font-size:11px;">Subtotal Barang</td>
                    <td class="text-right">Rp{{ number_format($order->total_payment - $order->shipping_cost, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right" style="color:#555; font-size:11px;">Ongkos Kirim</td>
                    <td class="text-right">Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="4" class="text-right">TOTAL BAYAR</td>
                    <td class="text-right">Rp{{ number_format($order->total_payment, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        

    </div>{{-- end nota-body --}}

    {{-- Footer --}}
    <div class="nota-footer">
        <strong>Terima kasih telah berbelanja di Tani Rancaoray!</strong><br>
        Beras segar langsung dari sawah organik kami untuk meja makan Anda.<br>
        Simpan nota ini sebagai bukti transaksi yang sah.
    </div>
</div>

{{-- Print Actions (tersembunyi saat print) --}}
<div class="print-actions">
    <button class="btn-print" onclick="window.print()">Cetak Nota</button>
    <a class="btn-back" href="/internal/orders">← Kembali ke Daftar Pesanan</a>
</div>

</body>
</html>
