<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\View\View;

class PublicAnnouncementController extends Controller
{
    public function index(): View
    {
        $announcements = Announcement::query()
            ->with('zone')
            ->publiclyVisible()
            ->where(fn ($query) => $query->whereNull('zone_id')->orWhereHas('zone', fn ($zoneQuery) => $zoneQuery->where('is_active', true)))
            ->latest('published_at')
            ->paginate(15);

        return view('announcements.index', compact('announcements'));
    }

    public function show(Announcement $announcement): View
    {
        abort_unless(
            Announcement::query()->publiclyVisible()->whereKey($announcement->getKey())->exists(),
            404
        );
        abort_if($announcement->zone_id !== null && ! $announcement->zone()->where('is_active', true)->exists(), 404);

        $announcement->load('zone');

        return view('announcements.show', compact('announcement'));
    }
}
