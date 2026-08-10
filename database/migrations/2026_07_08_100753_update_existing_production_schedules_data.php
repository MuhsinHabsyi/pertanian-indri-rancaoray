<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ProductionSchedule;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Query schedules with their material usages and products
        $schedules = ProductionSchedule::with('materialUsages.product')->get();

        foreach ($schedules as $sch) {
            $updated = false;

            // 1. Backfill Crop Season
            if (empty($sch->crop_season)) {
                $month = (int) date('m', strtotime($sch->planting_start_date));
                if (in_array($month, [11, 12, 1, 2, 3])) {
                    $sch->crop_season = 'MT 1 (Nov-Mar)';
                } elseif (in_array($month, [4, 5, 6, 7])) {
                    $sch->crop_season = 'MT 2 (Apr-Jul)';
                } else {
                    $sch->crop_season = 'MT 3 (Aug-Oct)';
                }
                $updated = true;
            }

            // 2. Backfill Estimated Harvest Date (+120 days)
            if (empty($sch->estimated_harvest_date)) {
                $sch->estimated_harvest_date = date('Y-m-d', strtotime($sch->planting_start_date . ' + 120 days'));
                $updated = true;
            }

            // 3. Backfill Estimated Harvest Weight (seed qty * 100 fallback to fertilizer * 10 or default 500)
            if (empty($sch->estimated_harvest) || $sch->estimated_harvest == 0) {
                $seedUsage = $sch->materialUsages->filter(function($usage) {
                    return $usage->product && $usage->product->category === 'Seed';
                })->sum('quantity_used');

                if ($seedUsage > 0) {
                    $sch->estimated_harvest = $seedUsage * 100;
                } else {
                    $fertilizerUsage = $sch->materialUsages->filter(function($usage) {
                        return $usage->product && $usage->product->category === 'Fertilizer';
                    })->sum('quantity_used');

                    if ($fertilizerUsage > 0) {
                        $sch->estimated_harvest = $fertilizerUsage * 10;
                    } else {
                        $sch->estimated_harvest = 500;
                    }
                }
                $updated = true;
            }

            if ($updated) {
                $sch->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback needed for data updates
    }
};
