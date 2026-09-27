<?php

namespace App\Filament\Widgets;

use App\Models\Purchase;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RevenueChart extends ChartWidget
{
    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    protected string $color = 'warning';

    public function getHeading(): string
    {
        return __('admin.stats.revenue_chart');
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $months = collect(range(11, 0))->map(
            fn ($i) => Carbon::now()->startOfMonth()->subMonths($i)
        );

        $purchases = Purchase::where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subMonths(12)->startOfMonth())
            ->get(['package_key', 'sessions_total', 'created_at']);

        $revenueByMonth = $months->map(function ($month) use ($purchases) {
            return $purchases
                ->filter(fn ($p) => $p->created_at->format('Y-m') === $month->format('Y-m'))
                ->sum(fn ($p) => config('packages.'.$p->package_key.'.price_cents', 0) / 100);
        });

        return [
            'datasets' => [
                [
                    'label' => __('admin.stats.revenue_chart'),
                    'data' => $revenueByMonth->values()->all(),
                    'backgroundColor' => 'rgba(242, 184, 29, 0.7)',
                    'borderColor' => 'rgb(242, 184, 29)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $months->map(fn ($m) => $m->format('M Y'))->values()->all(),
        ];
    }
}
