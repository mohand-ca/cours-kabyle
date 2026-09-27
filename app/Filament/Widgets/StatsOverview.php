<?php

namespace App\Filament\Widgets;

use App\Models\AvailabilitySlot;
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
    protected static ?int $sort = 1;

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

        $sessionsTotal = Purchase::where('status', 'completed')->sum('sessions_total');
        $sessionsRemaining = Purchase::where('status', 'completed')->sum('sessions_remaining');
        $sessionsUsed = $sessionsTotal - $sessionsRemaining;
        $utilizationPct = $sessionsTotal > 0 ? (int) round(($sessionsUsed / $sessionsTotal) * 100) : 0;

        $confirmedCount = LessonSession::where('status', 'confirmed')->count();
        $cancelledCount = LessonSession::where('status', 'cancelled')->count();
        $totalSessions = $confirmedCount + $cancelledCount;
        $cancellationPct = $totalSessions > 0 ? (int) round(($cancelledCount / $totalSessions) * 100) : 0;

        $availableSlots = AvailabilitySlot::where('status', 'available')
            ->where('starts_at', '>', now())
            ->count();

        $activeLearners = LessonSession::where('status', 'confirmed')
            ->whereHas('availabilitySlot', fn ($q) => $q->where('starts_at', '>=', now()->subDays(30)))
            ->distinct('learner_id')
            ->count('learner_id');

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

            Stat::make(__('admin.stats.sessions_booked'), $confirmedCount)
                ->description(__('admin.stats.sessions_sold', [
                    'count' => $sessionsTotal,
                ]))
                ->chart($bookingsPerDay)
                ->color('primary'),

            Stat::make(__('admin.stats.sessions_used'), $sessionsUsed)
                ->description(__('admin.stats.sessions_total_sold', ['count' => $sessionsTotal]))
                ->color($sessionsUsed === 0 ? 'gray' : 'success'),

            Stat::make(__('admin.stats.utilization_rate'), $utilizationPct.'%')
                ->description(__('admin.stats.utilization_pct', ['pct' => $utilizationPct]))
                ->color($utilizationPct >= 70 ? 'success' : ($utilizationPct >= 40 ? 'warning' : 'gray')),

            Stat::make(__('admin.stats.cancellation_rate'), $cancellationPct.'%')
                ->description(__('admin.stats.cancellation_pct', ['pct' => $cancellationPct]))
                ->color($cancellationPct >= 20 ? 'danger' : ($cancellationPct >= 10 ? 'warning' : 'success')),

            Stat::make(__('admin.stats.available_slots'), $availableSlots)
                ->description(__('admin.stats.upcoming_slots', ['count' => $availableSlots]))
                ->color('info'),

            Stat::make(__('admin.stats.active_learners'), $activeLearners)
                ->description(__('admin.stats.active_this_month'))
                ->color('success'),
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
