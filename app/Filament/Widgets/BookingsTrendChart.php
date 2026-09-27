<?php

namespace App\Filament\Widgets;

use App\Models\LessonSession;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class BookingsTrendChart extends ChartWidget
{
    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 'full';

    public function getHeading(): string
    {
        return __('admin.stats.bookings_trend');
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $dates = collect(range(29, 0))->map(
            fn ($i) => Carbon::today()->subDays($i)->toDateString()
        );

        $sessions = LessonSession::where('created_at', '>=', Carbon::today()->subDays(29))
            ->selectRaw('DATE(created_at) as date, status, COUNT(*) as count')
            ->groupBy('date', 'status')
            ->get();

        $confirmed = $dates->map(function ($d) use ($sessions) {
            return (int) ($sessions->where('date', $d)->where('status', 'confirmed')->first()?->count ?? 0);
        });

        $cancelled = $dates->map(function ($d) use ($sessions) {
            return (int) ($sessions->where('date', $d)->where('status', 'cancelled')->first()?->count ?? 0);
        });

        return [
            'datasets' => [
                [
                    'label' => __('admin.lesson_sessions.statuses.confirmed'),
                    'data' => $confirmed->values()->all(),
                    'borderColor' => 'rgb(34, 197, 94)',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'tension' => 0.3,
                    'fill' => true,
                ],
                [
                    'label' => __('admin.lesson_sessions.statuses.cancelled'),
                    'data' => $cancelled->values()->all(),
                    'borderColor' => 'rgb(239, 68, 68)',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'tension' => 0.3,
                    'fill' => true,
                ],
            ],
            'labels' => $dates->map(fn ($d) => Carbon::parse($d)->format('d M'))->values()->all(),
        ];
    }
}
