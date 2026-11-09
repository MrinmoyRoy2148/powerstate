<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoadSheddingSchedule;
use App\Models\Zone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LoadSheddingScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'period' => ['nullable', Rule::in(['upcoming', 'past', 'all'])],
            'search' => ['nullable', 'string', 'max:120'],
            'zone_id' => ['nullable', 'integer', 'exists:zones,id'],
            'scheduled_date' => ['nullable', 'date_format:Y-m-d'],
            'status' => ['nullable', Rule::in(['draft', 'published', 'cancelled'])],
        ]);

        $period = $validated['period'] ?? 'upcoming';
        $schedules = LoadSheddingSchedule::query()
            ->with(['zone', 'creator'])
            ->when(
                $validated['search'] ?? null,
                fn ($query, string $search) => $query->where('title', 'like', '%'.$search.'%')
            )
            ->when($period === 'upcoming', fn ($query) => $query->whereDate('scheduled_date', '>=', today()))
            ->when($period === 'past', fn ($query) => $query->whereDate('scheduled_date', '<', today()))
            ->when($validated['zone_id'] ?? null, fn ($query, int $zoneId) => $query->where('zone_id', $zoneId))
            ->when(
                $validated['scheduled_date'] ?? null,
                fn ($query, string $date) => $query->whereDate('scheduled_date', $date)
            )
            ->when($validated['status'] ?? null, fn ($query, string $status) => $query->where('status', $status));

        if ($period === 'upcoming') {
            $schedules->orderBy('scheduled_date')->orderBy('start_time');
        } else {
            $schedules->orderByDesc('scheduled_date')->orderByDesc('start_time');
        }

        return view('admin.schedules.index', [
            'schedules' => $schedules->paginate(20)->withQueryString(),
            'zones' => Zone::query()->orderBy('name')->get(),
            'period' => $period,
        ]);
    }

    public function create(): View
    {
        return view('admin.schedules.create', [
            'zones' => Zone::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedData($request);
        $request->user()->schedulesCreated()->create($validated);

        return redirect()->route('admin.schedules.index')->with('success', __('Schedule created successfully.'));
    }

    public function edit(LoadSheddingSchedule $schedule): View
    {
        return view('admin.schedules.edit', [
            'schedule' => $schedule,
            'zones' => Zone::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, LoadSheddingSchedule $schedule): RedirectResponse
    {
        $schedule->update($this->validatedData($request));

        return redirect()->route('admin.schedules.index')->with('success', __('Schedule updated successfully.'));
    }

    public function publish(LoadSheddingSchedule $schedule): RedirectResponse
    {
        $schedule->update(['status' => 'published']);

        return redirect()->route('admin.schedules.index')->with('success', __('Schedule published successfully.'));
    }

    public function cancel(LoadSheddingSchedule $schedule): RedirectResponse
    {
        $schedule->update(['status' => 'cancelled']);

        return redirect()->route('admin.schedules.index')->with('success', __('Schedule cancelled successfully.'));
    }

    public function destroy(LoadSheddingSchedule $schedule): RedirectResponse
    {
        $schedule->delete();

        return redirect()->route('admin.schedules.index')->with('success', __('Schedule deleted successfully.'));
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'zone_id' => ['required', 'integer', Rule::exists('zones', 'id')->where('is_active', true)],
            'title' => ['required', 'string', 'max:180'],
            'scheduled_date' => ['required', 'date_format:Y-m-d'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'notes' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['draft', 'published', 'cancelled'])],
        ]);
    }
}
