<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\LoadSheddingSchedule;
use App\Models\Outage;
use App\Models\Zone;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $announcements = Announcement::query()
            ->with('zone')
            ->where('status', 'published')
            ->where(fn ($query) => $query->whereNull('published_at')->orWhere('published_at', '<=', now()))
            ->where(fn ($query) => $query->whereNull('zone_id')->orWhereHas('zone', fn ($zoneQuery) => $zoneQuery->where('is_active', true)))
            ->latest('published_at')
            ->limit(5)
            ->get();

        return view('home', [
            'zones' => Zone::query()->where('is_active', true)->orderBy('name')->limit(8)->get(),
            'activeOutages' => Outage::query()
                ->with(['zone', 'reason'])
                ->where('status', 'active')
                ->whereHas('zone', fn ($query) => $query->where('is_active', true))
                ->latest('started_at')
                ->limit(5)
                ->get(),
            'upcomingSchedules' => LoadSheddingSchedule::query()
                ->with('zone')
                ->where('status', 'published')
                ->whereDate('scheduled_date', '>=', today())
                ->whereHas('zone', fn ($query) => $query->where('is_active', true))
                ->orderBy('scheduled_date')
                ->orderBy('start_time')
                ->limit(5)
                ->get(),
            'announcements' => $announcements,
        ]);
    }
}
