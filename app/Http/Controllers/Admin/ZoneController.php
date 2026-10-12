<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ZoneController extends Controller
{
    public function index(Request $request): View
    {
        $zones = Zone::query()
            ->with('parent')
            ->withCount(['schedules', 'outages', 'children'])
            ->when($request->string('search')->isNotEmpty(), fn ($query) => $query->where('name', 'like', '%'.$request->string('search').'%'))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.zones.index', compact('zones'));
    }

    public function create(): View
    {
        return view('admin.zones.create', ['zones' => Zone::query()->orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['required', 'string', 'max:140', 'alpha_dash', 'unique:zones,slug'],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'integer', 'exists:zones,id'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        Zone::query()->create($validated);

        return redirect()->route('admin.zones.index')->with('success', __('Zone created successfully.'));
    }

    public function edit(Zone $zone): View
    {
        return view('admin.zones.edit', [
            'zone' => $zone,
            'zones' => Zone::query()->where('id', '!=', $zone->id)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Zone $zone): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['required', 'string', 'max:140', 'alpha_dash', Rule::unique('zones', 'slug')->ignore($zone)],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'integer', 'exists:zones,id', Rule::notIn([$zone->id])],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $zone->update($validated);

        return redirect()->route('admin.zones.index')->with('success', __('Zone updated successfully.'));
    }

    public function destroy(Zone $zone): RedirectResponse
    {
        if (
            $zone->children()->exists()
            || $zone->schedules()->exists()
            || $zone->outages()->exists()
            || $zone->announcements()->exists()
        ) {
            return back()->withErrors(['zone' => __('This zone is in use. Deactivate it instead of deleting it.')]);
        }

        $zone->delete();

        return redirect()->route('admin.zones.index')->with('success', __('Zone deleted successfully.'));
    }
}
