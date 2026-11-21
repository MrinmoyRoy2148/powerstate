<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Zone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicZoneController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:120'],
        ]);

        $zones = Zone::query()
            ->where('is_active', true)
            ->when($validated['search'] ?? null, function ($query, string $search): void {
                $query->where('name', 'like', '%'.$search.'%');
            })
            ->with('parent')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('zones.index', ['zones' => $zones]);
    }

    public function show(Zone $zone): View
    {
        abort_unless($zone->is_active, 404);

        $zone->load([
            'parent',
            'outages' => fn ($query) => $query->with('reason')->where('status', 'active')->latest('started_at'),
            'schedules' => fn ($query) => $query->upcoming()
                ->where('status', 'published')
                ->orderBy('scheduled_date')
                ->orderBy('start_time'),
            'announcements' => fn ($query) => $query->with('zone')
                ->publiclyVisible()
                ->latest('published_at'),
        ]);

        $announcements = Announcement::query()
            ->with('zone')
            ->whereNull('zone_id')
            ->publiclyVisible()
            ->latest('published_at')
            ->get();

        return view('zones.show', compact('zone', 'announcements'));
    }

    public function saved(Request $request): View
    {
        $zones = $request->user()->savedZones()
            ->where('is_active', true)
            ->orderBy('name')
            ->paginate(12);

        return view('zones.saved', compact('zones'));
    }

    public function save(Request $request, Zone $zone): RedirectResponse
    {
        abort_unless($zone->is_active, 404);
        $request->user()->savedZones()->syncWithoutDetaching([$zone->id]);

        return back()->with('success', __('Area saved.'));
    }

    public function unsave(Request $request, Zone $zone): RedirectResponse
    {
        $request->user()->savedZones()->detach($zone->id);

        return back()->with('success', __('Area removed from saved areas.'));
    }
}
