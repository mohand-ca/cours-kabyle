<?php

namespace App\Filament\Widgets;

use App\Models\Purchase;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PackageSalesWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 4;

    protected function getStats(): array
    {
        $packages = ['starter', 'standard', 'premium'];
        $stats = [];

        foreach ($packages as $key) {
            $purchases = Purchase::where('status', 'completed')
                ->where('package_key', $key)
                ->get();

            $count = $purchases->count();
            $revenue = $purchases->sum(fn ($p) => config('packages.'.$p->package_key.'.price_cents', 0)) / 100;

            $stats[] = Stat::make(
                __('admin.purchases.packages.'.$key),
                $count
            )
                ->description(__('admin.stats.package_count', [
                    'count' => $count,
                    'revenue' => number_format($revenue, 0),
                ]))
                ->color('warning');
        }

        return $stats;
    }
}
