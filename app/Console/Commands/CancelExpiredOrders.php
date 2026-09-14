<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class CancelExpiredOrders extends Command
{
    protected $signature   = 'orders:cancel-expired';
    protected $description = 'Batalkan (Cancelled) semua pesanan Pending yang sudah melewati waktu pembayaran 1 jam';

    public function handle()
    {
        $expired = Order::where('order_status', 'Pending')
            ->whereNotNull('payment_expires_at')
            ->where('payment_expires_at', '<', now())
            ->get();

        $count = 0;
        foreach ($expired as $order) {
            $order->update(['order_status' => 'Cancelled']);
            $count++;
            $this->line("  Order #{$order->id} dibatalkan (expired: {$order->payment_expires_at})");
        }

        $this->info("Selesai. Total {$count} pesanan dibatalkan karena kedaluwarsa.");
        return Command::SUCCESS;
    }
}
