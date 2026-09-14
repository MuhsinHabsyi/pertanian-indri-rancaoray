<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Operasional &mdash; Tani Rancaoray</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                        }
                    }
                }
            }
        </script>
    @endif

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        @media print {
            body {
                background: #ffffff !important;
            }
            .no-print {
                display: none !important;
            }
            .report-page-container {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                border: none !important;
                box-shadow: none !important;
            }
            .section-box.hidden-section {
                display: none !important;
            }
            table th, table td {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .break-inside-avoid {
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 text-xs antialiased pb-10">

    {{-- ══════════════════════════════════════════════════════════════════
         TOOLBAR FILTER & PRINT (Hanya tampil di layar browser)
    ══════════════════════════════════════════════════════════════════ --}}
    <header class="no-print sticky top-0 z-50 bg-white border-b border-slate-200 shadow-sm px-6 py-3">
        <div class="max-w-6xl mx-auto flex flex-wrap items-center justify-between gap-3">
            
            <!-- Button Filter Tabs -->
            <div class="flex items-center flex-wrap gap-1.5">
                <span class="text-xs font-bold text-slate-700 mr-2">Pilih Bagian:</span>
                
                <button type="button" class="btn-tab px-3 py-1.5 rounded-lg text-xs font-semibold border transition bg-emerald-600 text-white border-emerald-700 shadow-sm" onclick="showSection('all', this)">
                    Semua Bagian
                </button>
                <button type="button" class="btn-tab px-3 py-1.5 rounded-lg text-xs font-semibold border transition bg-slate-100 text-slate-600 border-slate-300 hover:bg-slate-200" onclick="showSection('sec-summary', this)">
                    1. Ringkasan Eksekutif
                </button>
                <button type="button" class="btn-tab px-3 py-1.5 rounded-lg text-xs font-semibold border transition bg-slate-100 text-slate-600 border-slate-300 hover:bg-slate-200" onclick="showSection('sec-expense', this)">
                    2. Detail Pengeluaran
                </button>
                <button type="button" class="btn-tab px-3 py-1.5 rounded-lg text-xs font-semibold border transition bg-slate-100 text-slate-600 border-slate-300 hover:bg-slate-200" onclick="showSection('sec-revenue', this)">
                    3. Detail Pemasukan
                </button>
                <button type="button" class="btn-tab px-3 py-1.5 rounded-lg text-xs font-semibold border transition bg-slate-100 text-slate-600 border-slate-300 hover:bg-slate-200" onclick="showSection('sec-stock', this)">
                    4. Jumlah Stok Barang
                </button>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                <a href="/reports" class="inline-flex items-center gap-1 px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-white text-slate-600 border border-slate-300 hover:bg-slate-50 transition shadow-sm">
                    Kembali
                </a>
                <button type="button" onclick="window.print()" class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-lg text-xs font-semibold bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white transition shadow-sm">
                    Cetak / Simpan PDF
                </button>
            </div>

        </div>
    </header>

    {{-- ══════════════════════════════════════════════════════════════════
         KERTAS LAPORAN UTAMA (Tailwind Container)
    ══════════════════════════════════════════════════════════════════ --}}
    <main class="report-page-container max-w-6xl mx-auto my-6 bg-white p-8 md:p-10 rounded-xl border border-slate-200 shadow-sm print:p-0 print:m-0 print:border-none print:shadow-none print:max-w-full">

        <!-- KOP LAPORAN -->
        <div class="text-center border-b-2 border-emerald-600 pb-4 mb-6">
            <h1 class="text-xl font-extrabold text-emerald-800 uppercase tracking-tight">Pertanian Tani Rancaoray</h1>
            <h2 class="text-sm font-semibold text-slate-700 mt-0.5">Laporan Operasional, Keuangan & Inventaris</h2>
            <p class="text-xs text-slate-500 mt-1.5">
                Periode: <span class="font-semibold text-slate-700">{{ date('d M Y', strtotime($start)) }}</span> s/d <span class="font-semibold text-slate-700">{{ date('d M Y', strtotime($end)) }}</span>
                &nbsp;&bull;&nbsp; Tanggal Cetak: {{ date('d M Y H:i') }} WIB
            </p>
        </div>

        @php
            $profit = $summary['total_revenue'] - $summary['total_expense'];
        @endphp

        {{-- ── 1. RINGKASAN EKSEKUTIF ───────────────────────────────────── --}}
        <section id="sec-summary" class="section-box report-section mb-8 break-inside-avoid">
            <!-- Header Section -->
            <div class="flex items-center justify-between bg-slate-50 border-l-4 px-3.5 py-2 rounded-r-lg border-y border-r border-slate-100 mb-3.5">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">1. Ringkasan Eksekutif</h3>
                <span class="text-[11px] text-slate-500">Ikhtisar Kinerja Periode Terpilih</span>
            </div>

            <!-- Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
                <div class="bg-emerald-50/60 border-t-4 border border-emerald-100 rounded-lg p-3.5">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Total Pemasukan</span>
                    <span class="text-lg font-extrabold mt-1 block">Rp{{ number_format($summary['total_revenue'], 0, ',', '.') }}</span>
                    <span class="text-[10px] text-slate-400 mt-1 block">{{ $summary['total_orders'] }} pesanan selesai</span>
                </div>

                <div class="bg-red-50/50 border-t-4 border-t-red-600 border border-red-100 rounded-lg p-3.5">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Total Pengeluaran</span>
                    <span class="text-lg font-extrabold text-red-600 mt-1 block">Rp{{ number_format($summary['total_expense'], 0, ',', '.') }}</span>
                    <span class="text-[10px] text-slate-400 mt-1 block">{{ $summary['total_purchases'] }} pengajuan disetujui</span>
                </div>

                <div class="bg-blue-50/50 border-t-4 border-t-blue-600 border border-blue-100 rounded-lg p-3.5">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Laba / (Rugi) Bersih</span>
                    <span class="text-lg font-extrabold {{ $profit >= 0 ? 'text-emerald-700' : 'text-red-600' }} mt-1 block">
                        {{ $profit >= 0 ? '+' : '-' }}Rp{{ number_format(abs($profit), 0, ',', '.') }}
                    </span>
                    <span class="text-[10px] text-slate-400 mt-1 block">Pemasukan &minus; Pengeluaran</span>
                </div>

                <div class="bg-amber-50/50 border-t-4 border-t-amber-600 border border-amber-100 rounded-lg p-3.5">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Total Panen Padi</span>
                    <span class="text-lg font-extrabold text-amber-700 mt-1 block">{{ number_format($summary['total_harvest'], 0, ',', '.') }} Kg</span>
                    <span class="text-[10px] text-slate-400 mt-1 block">Hasil panen riil tercatat</span>
                </div>
            </div>

            <!-- Tabel Komparasi Ringkasan -->
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-slate-300 text-xs">
                    <thead>
                        <tr class="bg-slate-100 text-slate-800 uppercase font-bold text-[11px]">
                            <th class="border border-slate-300 px-3 py-2 text-center w-10">No</th>
                            <th class="border border-slate-300 px-3 py-2 text-left">Komponen Indikator</th>
                            <th class="border border-slate-300 px-3 py-2 text-center w-36">Jumlah / Volume</th>
                            <th class="border border-slate-300 px-3 py-2 text-right w-48">Nominal Nilai (Rp)</th>
                            <th class="border border-slate-300 px-3 py-2 text-left">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <tr class="hover:bg-slate-50">
                            <td class="border border-slate-300 px-3 py-2 text-center">1</td>
                            <td class="border border-slate-300 px-3 py-2 font-semibold text-slate-800">Pemasukan Penjualan Beras</td>
                            <td class="border border-slate-300 px-3 py-2 text-center font-medium">{{ $summary['total_orders'] }} Transaksi</td>
                            <td class="border border-slate-300 px-3 py-2 text-right font-bold text-emerald-700">Rp{{ number_format($summary['total_revenue'], 0, ',', '.') }}</td>
                            <td class="border border-slate-300 px-3 py-2 text-slate-500 text-[11px]">Total penerimaan dari pesanan berstatus Completed</td>
                        </tr>
                        <tr class="hover:bg-slate-50">
                            <td class="border border-slate-300 px-3 py-2 text-center">2</td>
                            <td class="border border-slate-300 px-3 py-2 font-semibold text-slate-800">Pengeluaran Pembiayaan Operasional</td>
                            <td class="border border-slate-300 px-3 py-2 text-center font-medium">{{ $summary['total_purchases'] }} Pengajuan</td>
                            <td class="border border-slate-300 px-3 py-2 text-right font-bold text-red-600">Rp{{ number_format($summary['total_expense'], 0, ',', '.') }}</td>
                            <td class="border border-slate-300 px-3 py-2 text-slate-500 text-[11px]">Biaya bibit, pupuk, sarana pertanian tervalidasi</td>
                        </tr>
                        <tr class="hover:bg-slate-50">
                            <td class="border border-slate-300 px-3 py-2 text-center">3</td>
                            <td class="border border-slate-300 px-3 py-2 font-semibold text-slate-800">Hasil Panen Padi Gabah / Beras</td>
                            <td class="border border-slate-300 px-3 py-2 text-center font-bold text-slate-800">{{ number_format($summary['total_harvest'], 0, ',', '.') }} Kg</td>
                            <td class="border border-slate-300 px-3 py-2 text-right text-slate-400">-</td>
                            <td class="border border-slate-300 px-3 py-2 text-slate-500 text-[11px]">Akumulasi jadwal produksi pertanian yang telah panen</td>
                        </tr>
                        <tr class="bg-slate-50 font-bold border-t-2 border-slate-400">
                            <td colspan="3" class="border border-slate-300 px-3 py-2 text-right uppercase">Laba / (Rugi) Bersih Operasional:</td>
                            <td class="border border-slate-300 px-3 py-2 text-right text-sm {{ $profit >= 0 ? 'text-emerald-700' : 'text-red-600' }}">
                                {{ $profit >= 0 ? '' : '-' }}Rp{{ number_format(abs($profit), 0, ',', '.') }}
                            </td>
                            <td class="border border-slate-300 px-3 py-2 {{ $profit >= 0 ? 'text-emerald-700' : 'text-red-600' }}">
                                {{ $profit >= 0 ? 'Surplus Operasional' : 'Defisit Operasional' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        {{-- ── 2. DETAIL PENGELUARAN (Tabel Tunggal Terpadu) ─────────────── --}}
        <section id="sec-expense" class="section-box report-section mb-8 break-inside-avoid">
            <div class="flex items-center justify-between bg-slate-50 border-l-4 border-red-600 px-3.5 py-2 rounded-r-lg border-y border-r border-slate-100 mb-3.5">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">2. Detail Pengeluaran (Pembiayaan Disetujui)</h3>
                <span class="text-[11px] text-slate-500">Total {{ $purchases->count() }} Pengajuan Transaksi</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-slate-300 text-xs">
                    <thead>
                        <tr class="bg-slate-100 text-slate-800 uppercase font-bold text-[11px]">
                            <th class="border border-slate-300 px-2.5 py-2 text-center w-9">No</th>
                            <th class="border border-slate-300 px-3 py-2 text-left w-24">Tanggal</th>
                            <th class="border border-slate-300 px-3 py-2 text-left w-36">Pengaju & Sumber</th>
                            <th class="border border-slate-300 px-3 py-2 text-left">Rincian Barang / Bahan</th>
                            <th class="border border-slate-300 px-2.5 py-2 text-center w-20">Jumlah</th>
                            <th class="border border-slate-300 px-3 py-2 text-right w-24">Harga Satuan</th>
                            <th class="border border-slate-300 px-3 py-2 text-right w-28">Subtotal</th>
                            <th class="border border-slate-300 px-2.5 py-2 text-center w-24">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @php $rowNo = 1; @endphp
                        @forelse($purchases as $p)
                            @if($p->items && $p->items->count() > 0)
                                @foreach($p->items as $i => $item)
                                    <tr class="hover:bg-slate-50">
                                        @if($i === 0)
                                            <td class="border border-slate-300 px-2.5 py-2 text-center font-medium" rowspan="{{ $p->items->count() }}">{{ $rowNo++ }}</td>
                                            <td class="border border-slate-300 px-3 py-2 text-slate-700" rowspan="{{ $p->items->count() }}">
                                                {{ date('d/m/Y', strtotime($p->submission_date)) }}
                                                <span class="block text-[10px] text-slate-400">#PB-{{ str_pad($p->id, 3, '0', STR_PAD_LEFT) }}</span>
                                            </td>
                                            <td class="border border-slate-300 px-3 py-2 text-slate-700" rowspan="{{ $p->items->count() }}">
                                                <span class="font-semibold text-slate-800">{{ $p->user->full_name ?? 'N/A' }}</span>
                                                <span class="block text-[10px] text-slate-400">{{ $p->procurement_source ?? 'Internal' }}</span>
                                            </td>
                                        @endif
                                        <td class="border border-slate-300 px-3 py-2 font-medium text-slate-800">{{ $item->product->name ?? 'Bahan/Item #'.$item->product_id }}</td>
                                        <td class="border border-slate-300 px-2.5 py-2 text-center">{{ number_format($item->quantity, 2, ',', '.') }}</td>
                                        <td class="border border-slate-300 px-3 py-2 text-right text-slate-600">Rp{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                        <td class="border border-slate-300 px-3 py-2 text-right font-semibold text-slate-800">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                        @if($i === 0)
                                            <td class="border border-slate-300 px-2.5 py-2 text-center" rowspan="{{ $p->items->count() }}">
                                                <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-800">
                                                    {{ $p->validation_status }}
                                                </span>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr class="hover:bg-slate-50">
                                    <td class="border border-slate-300 px-2.5 py-2 text-center font-medium">{{ $rowNo++ }}</td>
                                    <td class="border border-slate-300 px-3 py-2 text-slate-700">
                                        {{ date('d/m/Y', strtotime($p->submission_date)) }}
                                        <span class="block text-[10px] text-slate-400">#PB-{{ str_pad($p->id, 3, '0', STR_PAD_LEFT) }}</span>
                                    </td>
                                    <td class="border border-slate-300 px-3 py-2 text-slate-700">
                                        <span class="font-semibold text-slate-800">{{ $p->user->full_name ?? 'N/A' }}</span>
                                        <span class="block text-[10px] text-slate-400">{{ $p->procurement_source ?? 'Internal' }}</span>
                                    </td>
                                    <td class="border border-slate-300 px-3 py-2 text-slate-400 italic" colspan="3">Pengeluaran langsung (tanpa rincian item)</td>
                                    <td class="border border-slate-300 px-3 py-2 text-right font-semibold text-slate-800">Rp{{ number_format($p->total_cost, 0, ',', '.') }}</td>
                                    <td class="border border-slate-300 px-2.5 py-2 text-center">
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-800">
                                            {{ $p->validation_status }}
                                        </span>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="8" class="border border-slate-300 px-4 py-6 text-center text-slate-400 italic">Tidak ada data pembiayaan pengeluaran pada periode ini.</td>
                            </tr>
                        @endforelse
                        <tr class="bg-slate-50 font-bold border-t-2 border-slate-400">
                            <td colspan="6" class="border border-slate-300 px-3 py-2 text-right uppercase">Total Keseluruhan Pengeluaran:</td>
                            <td class="border border-slate-300 px-3 py-2 text-right text-red-600 font-extrabold text-sm">Rp{{ number_format($summary['total_expense'], 0, ',', '.') }}</td>
                            <td class="border border-slate-300"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        {{-- ── 3. DETAIL PEMASUKAN (Tabel Tunggal Terpadu) ──────────────── --}}
        <section id="sec-revenue" class="section-box report-section mb-8 break-inside-avoid">
            <div class="flex items-center justify-between bg-slate-50 border-l-4 border-emerald-600 px-3.5 py-2 rounded-r-lg border-y border-r border-slate-100 mb-3.5">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">3. Detail Pemasukan (Penjualan Selesai)</h3>
                <span class="text-[11px] text-slate-500">Total {{ $orders->count() }} Transaksi Pesanan</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-slate-300 text-xs">
                    <thead>
                        <tr class="bg-slate-100 text-slate-800 uppercase font-bold text-[11px]">
                            <th class="border border-slate-300 px-2.5 py-2 text-center w-9">No</th>
                            <th class="border border-slate-300 px-3 py-2 text-left w-24">Tanggal</th>
                            <th class="border border-slate-300 px-3 py-2 text-left w-28">No. Nota / ID</th>
                            <th class="border border-slate-300 px-3 py-2 text-left w-36">Pembeli & Alamat</th>
                            <th class="border border-slate-300 px-3 py-2 text-left">Rincian Produk</th>
                            <th class="border border-slate-300 px-2.5 py-2 text-center w-16">Total Kg</th>
                            <th class="border border-slate-300 px-2.5 py-2 text-right w-20">Ongkir</th>
                            <th class="border border-slate-300 px-3 py-2 text-right w-28">Total Bayar</th>
                            <th class="border border-slate-300 px-2 py-2 text-center w-20">Kurir</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @php 
                            $orderRowNo = 1;
                            $totalOngkir = 0;
                        @endphp
                        @forelse($orders as $o)
                            @php
                                $totalOngkir += $o->shipping_cost;
                                $totalKg = $o->items->sum('quantity');
                                $itemSummary = $o->items->map(function($item) {
                                    return ($item->product->name ?? 'Produk') . ' (' . number_format($item->quantity, 0) . ' Kg)';
                                })->join(', ');
                            @endphp
                            <tr class="hover:bg-slate-50">
                                <td class="border border-slate-300 px-2.5 py-2 text-center font-medium">{{ $orderRowNo++ }}</td>
                                <td class="border border-slate-300 px-3 py-2 text-slate-700">{{ date('d/m/Y H:i', strtotime($o->transaction_date)) }}</td>
                                <td class="border border-slate-300 px-3 py-2">
                                    <span class="font-semibold text-slate-800">{{ $o->nota_number ?? '#ORD-'.$o->id }}</span>
                                    <span class="block text-[10px] text-slate-400">{{ $o->payment_method }}</span>
                                </td>
                                <td class="border border-slate-300 px-3 py-2">
                                    <span class="font-semibold text-slate-800">{{ $o->recipient_name ?? $o->customer->full_name ?? 'Pelanggan' }}</span>
                                    @if($o->recipient_phone)
                                        <span class="block text-[10px] text-slate-500">{{ $o->recipient_phone }}</span>
                                    @endif
                                    <span class="block text-[10px] text-slate-400 truncate max-w-[140px]" title="{{ $o->shipping_address }}">
                                        {{ $o->shipping_address }}
                                    </span>
                                </td>
                                
                                <td class="border border-slate-300 px-3 py-2 text-slate-700">{{ $itemSummary ?: '-' }}</td>
                                <td class="border border-slate-300 px-2.5 py-2 text-center font-semibold text-slate-800">{{ number_format($totalKg, 0, ',', '.') }} Kg</td>
                                <td class="border border-slate-300 px-2.5 py-2 text-right text-slate-600">Rp{{ number_format($o->shipping_cost, 0, ',', '.') }}</td>
                                <td class="border border-slate-300 px-3 py-2 text-right font-bold text-emerald-700">Rp{{ number_format($o->total_payment, 0, ',', '.') }}</td>
                                <td class="border border-slate-300 px-2 py-2 text-center">
                                    @if($o->delivery_courier === 'Rancaoray')
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-800">Rancaoray</span>
                                        <span class="block text-[9px] text-slate-500 font-mono">6283116581808</span>
                                    @elseif($o->delivery_courier === 'JNT')
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-100 text-blue-800">JNT</span>
                                        @if($o->courier_phone)
                                            <span class="block text-[9px] text-slate-500 font-mono">{{ $o->courier_phone }}</span>
                                        @endif
                                    @else
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="border border-slate-300 px-4 py-6 text-center text-slate-400 italic">Tidak ada data penjualan selesai pada periode ini.</td>
                            </tr>
                        @endforelse
                        <tr class="bg-slate-50 font-bold border-t-2 border-slate-400">
                            <td colspan="7" class="border border-slate-300 px-3 py-2 text-right uppercase">Total Keseluruhan Pemasukan:</td>
                            <td class="border border-slate-300 px-2.5 py-2 text-right text-slate-700 font-semibold">Rp{{ number_format($totalOngkir, 0, ',', '.') }}</td>
                            <td class="border border-slate-300 px-3 py-2 text-right text-emerald-700 font-extrabold text-sm">Rp{{ number_format($summary['total_revenue'], 0, ',', '.') }}</td>
                            <td class="border border-slate-300"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        {{-- ── 4. JUMLAH STOK BARANG (Tabel Tunggal Terpadu) ────────────── --}}
        <section id="sec-stock" class="section-box report-section mb-8 break-inside-avoid">
            <div class="flex items-center justify-between bg-slate-50 border-l-4 border-blue-600 px-3.5 py-2 rounded-r-lg border-y border-r border-slate-100 mb-3.5">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">4. Jumlah Stok Barang (Inventaris Gudang & Online)</h3>
                <span class="text-[11px] text-slate-500">Total {{ $products->count() }} Item Terdaftar</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-slate-300 text-xs">
                    <thead>
                        <tr class="bg-slate-100 text-slate-800 uppercase font-bold text-[11px]">
                            <th class="border border-slate-300 px-2.5 py-2 text-center w-9">No</th>
                            <th class="border border-slate-300 px-3 py-2 text-left">Nama Barang / Produk</th>
                            <th class="border border-slate-300 px-3 py-2 text-center w-28">Kategori</th>
                            <th class="border border-slate-300 px-2.5 py-2 text-center w-20">Satuan</th>
                            <th class="border border-slate-300 px-3 py-2 text-right w-28">Stok Gudang</th>
                            <th class="border border-slate-300 px-3 py-2 text-right w-28">Stok Online</th>
                            <th class="border border-slate-300 px-3 py-2 text-right w-32">Harga Pokok / Jual</th>
                            <th class="border border-slate-300 px-2.5 py-2 text-center w-24">Status Stok</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @php $stockRowNo = 1; @endphp
                        @forelse($products as $prod)
                            @php
                                $onlinePacks = $prod->packages ? $prod->packages->where('is_active', true)->sum('online_stock') : 0;
                                $isOutOfStock = ($prod->stock_available <= 0 && $onlinePacks <= 0);
                                $isLowStock = ($prod->stock_available > 0 && $prod->stock_available <= 20);
                            @endphp
                            <tr class="hover:bg-slate-50">
                                <td class="border border-slate-300 px-2.5 py-2 text-center font-medium">{{ $stockRowNo++ }}</td>
                                <td class="border border-slate-300 px-3 py-2">
                                    <span class="font-semibold text-slate-800">{{ $prod->name }}</span>
                                    @if($prod->online_name && $prod->online_name !== $prod->name)
                                        <span class="block text-[10px] text-slate-400">Online: {{ $prod->online_name }}</span>
                                    @endif
                                </td>
                                <td class="border border-slate-300 px-3 py-2 text-center">
                                    @if($prod->category === 'Rice')
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold ">Beras</span>
                                    @elseif($prod->category === 'Seed')
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold ">Benih</span>
                                    @elseif($prod->category === 'Fertilizer')
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold ">Pupuk</span>
                                    @else
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold ">{{ $prod->category ?? 'Lainnya' }}</span>
                                    @endif
                                </td>
                                <td class="border border-slate-300 px-2.5 py-2 text-center text-slate-700">{{ $prod->unit ?? 'Kg' }}</td>
                                <td class="border border-slate-300 px-3 py-2 text-right font-semibold {{ $prod->stock_available <= 0 ? 'text-red-600' : 'text-slate-800' }}">
                                    {{ number_format($prod->stock_available, 2, ',', '.') }} {{ $prod->unit ?? 'Kg' }}
                                </td>
                                <td class="border border-slate-300 px-3 py-2 text-right">
                                    @if($prod->packages && $prod->packages->count() > 0)
                                        <span class="font-semibold text-blue-600">{{ number_format($onlinePacks, 0) }} pak</span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="border border-slate-300 px-3 py-2 text-right font-medium text-slate-700">
                                    @if($prod->price > 0)
                                        Rp{{ number_format($prod->price, 0, ',', '.') }}
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="border border-slate-300 px-2.5 py-2 text-center">
                                    @if($isOutOfStock)
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-red-100 text-red-800">Habis</span>
                                    @elseif($isLowStock)
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-100 text-amber-800">Menipis</span>
                                    @else
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-800">Tersedia</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="border border-slate-300 px-4 py-6 text-center text-slate-400 italic">Belum ada data barang/produk pada sistem.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        
    </main>

    {{-- ══════════════════════════════════════════════════════════════════
         JAVASCRIPT INTERAKTIF FILTER / TAB SECTION
    ══════════════════════════════════════════════════════════════════ --}}
    <script>
        function showSection(sectionId, btnElement) {
            // Update styling tab buttons
            const allTabs = document.querySelectorAll('.btn-tab');
            allTabs.forEach(tab => {
                tab.className = "btn-tab px-3 py-1.5 rounded-lg text-xs font-semibold border transition bg-slate-100 text-slate-600 border-slate-300 hover:bg-slate-200";
            });

            if (btnElement) {
                btnElement.className = "btn-tab px-3 py-1.5 rounded-lg text-xs font-semibold border transition bg-emerald-600 text-white border-emerald-700 shadow-sm";
            }

            // Tampilkan / Sembunyikan Bagian
            const allSections = document.querySelectorAll('.report-section');
            if (sectionId === 'all') {
                allSections.forEach(sec => {
                    sec.style.display = 'block';
                    sec.classList.remove('hidden-section');
                });
            } else {
                allSections.forEach(sec => {
                    if (sec.id === sectionId) {
                        sec.style.display = 'block';
                        sec.classList.remove('hidden-section');
                    } else {
                        sec.style.display = 'none';
                        sec.classList.add('hidden-section');
                    }
                });
            }
        }
    </script>
</body>
</html>