<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OutageReason;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OutageReasonController extends Controller
{
    public function index(): View
    {
        return view('admin.reasons.index', [
            'reasons' => OutageReason::query()->withCount('outages')->orderBy('name')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.reasons.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120', 'unique:outage_reasons,name'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        OutageReason::query()->create($validated);

        return redirect()->route('admin.reasons.index')->with('success', __('Outage reason created successfully.'));
    }

    public function edit(OutageReason $reason): View
    {
        return view('admin.reasons.edit', compact('reason'));
    }

    public function update(Request $request, OutageReason $reason): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120', Rule::unique('outage_reasons', 'name')->ignore($reason)],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $reason->update($validated);

        return redirect()->route('admin.reasons.index')->with('success', __('Outage reason updated successfully.'));
    }

    public function destroy(OutageReason $reason): RedirectResponse
    {
        if ($reason->outages()->exists()) {
            return back()->withErrors(['reason' => __('This reason is in use. Deactivate it instead of deleting it.')]);
        }

        $reason->delete();

        return redirect()->route('admin.reasons.index')->with('success', __('Outage reason deleted successfully.'));
    }
}
