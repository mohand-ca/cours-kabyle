<?php

namespace App\Filament\Widgets;

use App\Models\LessonSession;
use App\Models\Purchase;
use App\Models\TeacherProfile;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class StatsOverview extends StatsOverviewWidget
{
    protected ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        $learnersPerDay = $this->countPerDay(
            User::query()->where('role', 'learner')
        );

        $bookingsPerDay = $this->countPerDay(
            LessonSession::query()->where('status', 'confirmed')
        );

        $revenue = Purchase::where('status', 'completed')->get()
            ->sum(fn ($p) => config('packages.'.$p->package_key.'.price_cents', 0)) / 100;

        return [
            Stat::make(__('admin.stats.learners'), User::where('role', 'learner')->count())
                ->description(__('admin.stats.new_this_month', [
                    'count' => User::where('role', 'learner')
                        ->whereMonth('created_at', now()->month)
                        ->count(),
                ]))
                ->chart($learnersPerDay)
                ->color('success'),

            Stat::make(__('admin.stats.teachers_approved'), TeacherProfile::where('status', 'approved')->count())
                ->description(__('admin.stats.pending_count', [
                    'count' => TeacherProfile::where('status', 'pending')->count(),
                ]))
                ->color('info'),

            Stat::make(__('admin.stats.revenue'), '$'.number_format($revenue, 2).' CAD')
                ->description(__('admin.stats.purchases_count', [
                    'count' => Purchase::where('status', 'completed')->count(),
                ]))
                ->color('warning'),

            Stat::make(__('admin.stats.sessions_booked'), LessonSession::where('status', 'confirmed')->count())
                ->description(__('admin.stats.sessions_sold', [
                    'count' => Purchase::where('status', 'completed')->sum('sessions_total'),
                ]))
                ->chart($bookingsPerDay)
                ->color('primary'),
        ];
    }

    /** @return array<int, int> */
    private function countPerDay(Builder $query): array
    {
        $days = collect(range(6, 0))->map(fn ($i) => Carbon::today()->subDays($i)->toDateString());

        $counts = (clone $query)
            ->whereDate('created_at', '>=', Carbon::today()->subDays(6))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date');

        return $days->map(fn ($d) => (int) ($counts[$d] ?? 0))->values()->all();
    }
}
