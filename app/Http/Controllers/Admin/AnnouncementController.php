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
            'search' => ['nullable', 'string', 'max:120'],
            'status' => ['nullable', Rule::in(['draft', 'published', 'expired'])],
            'zone_id' => ['nullable', 'integer', 'exists:zones,id'],
        ]);

        $announcements = Announcement::query()
            ->with(['zone', 'creator'])
            ->when($validated['search'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('title', 'like', '%'.$search.'%')
                        ->orWhere('body', 'like', '%'.$search.'%');
                });
            })
            ->when(
                $validated['zone_id'] ?? null,
                fn ($query, int $zoneId) => $query->where('zone_id', $zoneId)
            )
            ->when(
                ($validated['status'] ?? null) === 'expired',
                fn ($query) => $query->where('status', 'published')
                    ->whereNotNull('expires_at')
                    ->where('expires_at', '<=', now())
            )
            ->when(
                ($validated['status'] ?? null) === 'published',
                fn ($query) => $query->where('status', 'published')
                    ->where(fn ($query) => $query->whereNull('expires_at')->orWhere('expires_at', '>', now()))
            )
            ->when(
                ($validated['status'] ?? null) === 'draft',
                fn ($query) => $query->where('status', 'draft')
            )
            ->latest('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.announcements.index', [
            'announcements' => $announcements,
            'zones' => Zone::query()->orderBy('name')->get(),
        ]);
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

        $validated['expires_at'] = $validated['expires_at'] ?? null;
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
        $validated['expires_at'] = $validated['expires_at'] ?? null;

        $announcement->update($validated);

        return redirect()->route('admin.announcements.index')->with('success', __('Announcement updated successfully.'));
    }

    public function publish(Announcement $announcement): RedirectResponse
    {
        if ($announcement->expires_at?->isPast()) {
            return back()->withErrors(['expires_at' => __('The expiry date must be in the future to publish this announcement.')]);
        }

        $announcement->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        return redirect()->route('admin.announcements.index')->with('success', __('Announcement published successfully.'));
    }

    public function unpublish(Announcement $announcement): RedirectResponse
    {
        $announcement->update([
            'status' => 'draft',
            'published_at' => null,
        ]);

        return redirect()->route('admin.announcements.index')->with('success', __('Announcement unpublished successfully.'));
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
            'expires_at' => [
                'nullable',
                'date',
                Rule::when(
                    $request->input('status') === 'published',
                    [$request->filled('published_at') ? 'after:published_at' : 'after:now']
                ),
            ],
        ]);
    }
}
