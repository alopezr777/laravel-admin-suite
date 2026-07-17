@extends('layouts.app')
@section('title', 'Activity log')
@section('content')
    <div class="page-heading"><div><h1>Activity log</h1><p>An audit trail of important changes made across the workspace.</p></div></div>
    <section class="card"><div class="table-wrap"><table class="table"><thead><tr><th>Event</th><th>Action</th><th>User</th><th>Date</th></tr></thead><tbody>
        @forelse($activities as $activity)<tr><td class="cell-title">{{ $activity->description }}</td><td><span class="badge badge-low">{{ $activity->action }}</span></td><td>{{ $activity->user?->name ?? 'System' }}</td><td class="nowrap">{{ $activity->created_at->format('M j, Y · H:i') }}</td></tr>@empty<tr><td colspan="4" class="empty-state">No activity has been recorded.</td></tr>@endforelse
    </tbody></table></div>{{ $activities->links() }}</section>
@endsection

