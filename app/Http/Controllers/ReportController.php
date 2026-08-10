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
        $purchases = Purchase::whereBetween('submission_date', [$start, $end])
                             ->where('validation_status', 'Approved')->get();
                             
        $orders = Order::whereBetween('transaction_date', [$start, $end])
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

    // SKENARIO LANGKAH 5-6: Sistem Menghasilkan Dokumen (Simulasi PDF)
    public function download(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        $purchases = Purchase::whereBetween('submission_date', [$start, $end])->where('validation_status', 'Approved')->get();
        $orders = Order::whereBetween('transaction_date', [$start, $end])->where('order_status', 'Completed')->get();
        $productions = ProductionSchedule::whereBetween('actual_harvest_date', [$start, $end])->where('status', 'Completed')->get();
        
        $summary = [
            'total_expense' => $purchases->sum('total_cost'),
            'total_revenue' => $orders->sum('total_payment'),
            'total_harvest' => $productions->sum('total_harvest')
        ];

        return view('reports.print', compact('start', 'end', 'summary', 'purchases', 'orders', 'productions'));
    }
}