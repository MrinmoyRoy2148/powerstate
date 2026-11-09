@extends('layouts.admin')
@section('title', __('Outage reasons'))
@section('content')
    <x-admin-page-header :title="__('Outage reasons')" :description="__('Maintain the selectable reasons used when recording an outage.')" :action="route('admin.reasons.create')" :action-label="__('Add reason')" />
    <div class="card">
        <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>{{ __('Reason') }}</th><th>{{ __('Outages') }}</th><th>{{ __('labels.status') }}</th><th>{{ __('Actions') }}</th></tr></thead>
            <tbody>
                @forelse ($reasons as $reason)
                    <tr><td><strong>{{ $reason->name }}</strong><div class="small text-secondary">{{ $reason->description ?: '—' }}</div></td><td>{{ $reason->outages_count }}</td><td><x-status-badge :value="$reason->is_active ? 'active' : 'inactive'" /></td><td><div class="d-flex flex-wrap gap-2"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.reasons.edit', $reason) }}">{{ __('Edit') }}</a><form method="POST" action="{{ route('admin.reasons.activation.update', $reason) }}">@csrf @method('PATCH')<input type="hidden" name="is_active" value="{{ $reason->is_active ? 0 : 1 }}"><button class="btn btn-sm {{ $reason->is_active ? 'btn-outline-secondary' : 'btn-success' }}" type="submit">{{ $reason->is_active ? __('Deactivate') : __('Activate') }}</button></form><form method="POST" action="{{ route('admin.reasons.destroy', $reason) }}" onsubmit="return confirm(@js(__('Delete this reason?')))">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit">{{ __('Delete') }}</button></form></div></td></tr>
                @empty
                    <tr><td colspan="4" class="text-center text-secondary py-5">{{ __('No outage reasons have been created.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-5">{{ $reasons->links() }}</div>
@endsection
