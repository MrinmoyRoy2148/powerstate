<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Zone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'status' => ['nullable', Rule::in(['draft', 'published'])],
        ]);

        $announcements = Announcement::query()
            ->with(['zone', 'creator'])
            ->when(
                $validated['status'] ?? null,
                fn ($query, string $status) => $query->where('status', $status)
            )
            ->latest('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.announcements.index', compact('announcements'));
    }

    public function create(): View
    {
        return view('admin.announcements.create', [
            'zones' => Zone::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedData($request);
        $validated['published_at'] = $validated['status'] === 'published'
            ? ($validated['published_at'] ?? now())
            : null;

        $request->user()->announcementsCreated()->create($validated);

        return redirect()->route('admin.announcements.index')->with('success', __('Announcement created successfully.'));
    }

    public function edit(Announcement $announcement): View
    {
        return view('admin.announcements.edit', [
            'announcement' => $announcement,
            'zones' => Zone::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        $validated = $this->validatedData($request);
        $validated['published_at'] = $validated['status'] === 'published'
            ? ($validated['published_at'] ?? $announcement->published_at ?? now())
            : null;

        $announcement->update($validated);

        return redirect()->route('admin.announcements.index')->with('success', __('Announcement updated successfully.'));
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        $announcement->delete();

        return redirect()->route('admin.announcements.index')->with('success', __('Announcement deleted successfully.'));
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'body' => ['required', 'string'],
            'zone_id' => [
                'nullable',
                'integer',
                Rule::exists('zones', 'id')->where('is_active', true),
            ],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'published_at' => ['nullable', 'date'],
        ]);
    }
}
