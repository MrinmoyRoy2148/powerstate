<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoadSheddingSchedule;
use App\Models\Outage;
use App\Models\OutageReason;
use App\Models\Zone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OutageController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'status' => ['nullable', Rule::in(['active', 'restored', 'cancelled'])],
            'zone' => ['nullable', 'integer', 'exists:zones,id'],
        ]);

        $outages = Outage::query()
            ->with(['zone', 'reason', 'reporter'])
            ->when($validated['status'] ?? null, fn ($query, string $status) => $query->where('status', $status))
            ->when($validated['zone'] ?? null, fn ($query, int $zoneId) => $query->where('zone_id', $zoneId))
            ->latest('started_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.outages.index', [
            'outages' => $outages,
            'zones' => Zone::query()->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.outages.create', $this->formOptions());
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedData($request);
        $request->user()->outagesReported()->create($validated);

        return redirect()->route('admin.outages.index')->with('success', __('Outage reported successfully.'));
    }

    public function show(Outage $outage): View
    {
        $outage->load(['zone', 'schedule', 'reason', 'reporter']);

        return view('admin.outages.show', compact('outage'));
    }

    public function edit(Outage $outage): View
    {
        return view('admin.outages.edit', array_merge(
            ['outage' => $outage],
            $this->formOptions()
        ));
    }

    public function update(Request $request, Outage $outage): RedirectResponse
    {
        $validated = $this->validatedData($request, $outage);

        if ($outage->status === 'restored') {
            $validated['status'] = 'restored';
        }

        $outage->update($validated);

        return redirect()->route('admin.outages.show', $outage)->with('success', __('Outage updated successfully.'));
    }

    public function destroy(Outage $outage): RedirectResponse
    {
        $outage->delete();

        return redirect()->route('admin.outages.index')->with('success', __('Outage deleted successfully.'));
    }

    public function restore(Outage $outage): RedirectResponse
    {
        if ($outage->status !== 'active') {
            return back()->withErrors(['outage' => __('Only active outages can be marked as restored.')]);
        }

        $outage->forceFill([
            'status' => 'restored',
            'restored_at' => now(),
        ])->save();

        return redirect()->route('admin.outages.show', $outage)->with('success', __('Outage marked as restored.'));
    }

    /**
     * @return array<string, mixed>
     */
    private function formOptions(): array
    {
        return [
            'zones' => Zone::query()->where('is_active', true)->orderBy('name')->get(),
            'schedules' => LoadSheddingSchedule::query()->where('status', 'published')->with('zone')->orderByDesc('scheduled_date')->get(),
            'reasons' => OutageReason::query()->where('is_active', true)->orderBy('name')->get(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request, ?Outage $outage = null): array
    {
        $statusRules = $outage?->status === 'restored'
            ? ['sometimes', Rule::in(['restored'])]
            : ['required', Rule::in(['active', 'cancelled'])];

        return $request->validate([
            'zone_id' => ['required', 'integer', Rule::exists('zones', 'id')->where('is_active', true)],
            'schedule_id' => [
                'nullable',
                'integer',
                Rule::exists('load_shedding_schedules', 'id')
                    ->where('zone_id', $request->input('zone_id'))
                    ->where('status', 'published'),
            ],
            'reason_id' => [
                'nullable',
                'integer',
                Rule::exists('outage_reasons', 'id')->where('is_active', true),
            ],
            'title' => ['required', 'string', 'max:180'],
            'outage_type' => ['required', Rule::in(['planned', 'unexpected'])],
            'description' => ['nullable', 'string'],
            'started_at' => ['required', 'date'],
            'expected_restoration_at' => ['nullable', 'date', 'after:started_at'],
            'status' => $statusRules,
        ]);
    }
}
