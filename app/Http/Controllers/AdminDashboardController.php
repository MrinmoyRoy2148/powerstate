<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\LoadSheddingSchedule;
use App\Models\Outage;
use App\Models\Zone;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'zoneCount' => Zone::query()->count(),
            'upcomingScheduleCount' => LoadSheddingSchedule::query()
                ->where('status', 'published')
                ->whereDate('scheduled_date', '>=', today())
                ->count(),
            'activeOutageCount' => Outage::query()->where('status', 'active')->count(),
            'publishedAnnouncementCount' => Announcement::query()
                ->where('status', 'published')
                ->where(fn ($query) => $query->whereNull('published_at')->orWhere('published_at', '<=', now()))
                ->count(),
            'recentOutages' => Outage::query()->with('zone')->latest('started_at')->limit(5)->get(),
            'upcomingSchedules' => LoadSheddingSchedule::query()
                ->with('zone')
                ->where('status', 'published')
                ->whereDate('scheduled_date', '>=', today())
                ->orderBy('scheduled_date')
                ->orderBy('start_time')
                ->limit(5)
                ->get(),
            'latestAnnouncements' => Announcement::query()
                ->where('status', 'published')
                ->latest('published_at')
                ->limit(5)
                ->get(),
        ]);
    }
}
