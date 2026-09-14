<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Order;
use App\Models\ProductionSchedule;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // SKENARIO LANGKAH 1: Menampilkan Form
    public function index()
    {
        return view('reports.index');
    }

    // SKENARIO LANGKAH 2-4 & ALTERNATIF: Tarik Data dan Validasi Kosong
    public function generate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $start = $request->start_date;
        $end = $request->end_date;

        // Menarik data dari basis data
        $purchases = Purchase::with(['items.product', 'user'])
                             ->whereBetween('submission_date', [$start, $end])
                             ->where('validation_status', 'Approved')->get();
                             
        $orders = Order::with(['items.product', 'customer'])
                       ->whereBetween('transaction_date', [$start, $end])
                       ->where('order_status', 'Completed')->get();

        $productions = ProductionSchedule::whereBetween('actual_harvest_date', [$start, $end])
                                         ->where('status', 'Completed')->get();

        // SKENARIO ALTERNATIF LANGKAH 3: Tidak menemukan data
        if ($purchases->isEmpty() && $orders->isEmpty() && $productions->isEmpty()) {
            return redirect('/reports')->with('info', 'Tidak ada aktivitas pada rentang waktu yang dipilih');
        }

        // SKENARIO NORMAL LANGKAH 4: Merekapitulasi dan menampilkan
        $summary = [
            'total_expense' => $purchases->sum('total_cost'),
            'total_revenue' => $orders->sum('total_payment'),
            'total_harvest' => $productions->sum('total_harvest')
        ];

        return view('reports.index', compact('start', 'end', 'summary', 'purchases', 'orders', 'productions'));
    }

    // SKENARIO LANGKAH 5-6: Sistem Menghasilkan Dokumen Laporan & Cetak PDF
    public function download(Request $request)
    {
        $start = $request->start_date ?? date('Y-m-01');
        $end = $request->end_date ?? date('Y-m-d');

        $purchases = Purchase::with(['items.product', 'user'])
            ->whereBetween('submission_date', [$start, $end])
            ->where('validation_status', 'Approved')
            ->orderBy('submission_date', 'desc')
            ->get();

        $orders = Order::with(['items.product', 'customer'])
            ->whereBetween('transaction_date', [$start, $end])
            ->where('order_status', 'Completed')
            ->orderBy('transaction_date', 'desc')
            ->get();

        $productions = ProductionSchedule::whereBetween('actual_harvest_date', [$start, $end])
            ->where('status', 'Completed')
            ->orderBy('actual_harvest_date', 'desc')
            ->get();

        $products = \App\Models\Product::with('packages')->orderBy('category')->orderBy('name')->get();
        
        $summary = [
            'total_expense'  => $purchases->sum('total_cost'),
            'total_revenue'  => $orders->sum('total_payment'),
            'total_harvest'  => $productions->sum('total_harvest'),
            'total_orders'   => $orders->count(),
            'total_purchases'=> $purchases->count(),
            'total_products' => $products->count(),
        ];

        return view('reports.print', compact('start', 'end', 'summary', 'purchases', 'orders', 'productions', 'products'));
    }
}