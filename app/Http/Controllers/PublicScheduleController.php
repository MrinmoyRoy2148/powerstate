<?php

namespace App\Http\Controllers;

use App\Models\LoadSheddingSchedule;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'zone' => ['nullable', 'integer', 'exists:zones,id'],
            'date' => ['nullable', 'date'],
        ]);

        $schedules = LoadSheddingSchedule::query()
            ->with('zone')
            ->where('status', 'published')
            ->whereDate('scheduled_date', '>=', today())
            ->whereHas('zone', fn ($query) => $query->where('is_active', true))
            ->when($validated['zone'] ?? null, fn ($query, int $zoneId) => $query->where('zone_id', $zoneId))
            ->when($validated['date'] ?? null, fn ($query, string $date) => $query->whereDate('scheduled_date', $date))
            ->orderBy('scheduled_date')
            ->orderBy('start_time')
            ->paginate(15)
            ->withQueryString();

        return view('schedules.index', [
            'schedules' => $schedules,
            'zones' => Zone::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }
}
