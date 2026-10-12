<?php

namespace App\Http\Controllers;

use App\Models\Outage;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicOutageController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'zone' => ['nullable', 'integer', 'exists:zones,id'],
        ]);

        $outages = Outage::query()
            ->with(['zone', 'reason'])
            ->where('status', 'active')
            ->whereHas('zone', fn ($query) => $query->where('is_active', true))
            ->when($validated['zone'] ?? null, fn ($query, int $zoneId) => $query->where('zone_id', $zoneId))
            ->latest('started_at')
            ->paginate(15)
            ->withQueryString();

        return view('outages.index', [
            'outages' => $outages,
            'zones' => Zone::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function history(Request $request): View
    {
        $validated = $request->validate([
            'zone' => ['nullable', 'integer', 'exists:zones,id'],
            'type' => ['nullable', 'in:planned,unexpected'],
            'date' => ['nullable', 'date'],
        ]);

        $outages = Outage::query()
            ->with(['zone', 'reason'])
            ->whereIn('status', ['restored', 'cancelled'])
            ->whereHas('zone', fn ($query) => $query->where('is_active', true))
            ->when($validated['zone'] ?? null, fn ($query, int $zoneId) => $query->where('zone_id', $zoneId))
            ->when($validated['type'] ?? null, fn ($query, string $type) => $query->where('outage_type', $type))
            ->when($validated['date'] ?? null, fn ($query, string $date) => $query->whereDate('started_at', $date))
            ->latest('started_at')
            ->paginate(15)
            ->withQueryString();

        return view('outages.history', [
            'outages' => $outages,
            'zones' => Zone::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function show(Outage $outage): View
    {
        abort_unless(
            in_array($outage->status, ['active', 'restored'], true)
                && $outage->zone()->where('is_active', true)->exists(),
            404
        );

        $outage->load(['zone', 'reason', 'schedule']);

        return view('outages.show', compact('outage'));
    }
}
