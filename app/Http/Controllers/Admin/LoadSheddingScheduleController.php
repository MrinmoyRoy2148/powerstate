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
    public function index(): View
    {
        return view('admin.schedules.index', [
            'schedules' => LoadSheddingSchedule::query()
                ->with(['zone', 'creator'])
                ->latest('scheduled_date')
                ->paginate(20),
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
            'scheduled_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'notes' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['draft', 'published', 'cancelled'])],
        ]);
    }
}
