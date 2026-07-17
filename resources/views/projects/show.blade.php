@extends('layouts.app')
@section('title', $project->name)
@section('content')
    <div class="page-heading">
        <div><div style="display:flex; gap:8px; margin-bottom:8px"><span class="badge badge-{{ $project->status }}">{{ str($project->status)->replace('_', ' ') }}</span><span class="badge badge-{{ $project->priority }}">{{ $project->priority }} priority</span></div><h1>{{ $project->name }}</h1><p>{{ $project->client }}</p></div>
        <div class="heading-actions"><a class="btn btn-secondary" href="{{ route('projects.edit', $project) }}">Edit</a><a class="btn btn-primary" href="{{ route('tasks.create', ['project' => $project->id]) }}">Add task</a></div>
    </div>

    <div class="detail-grid">
        <div class="stack">
            <section class="card">
                <header class="card-header"><div><h2 class="card-title">Project overview</h2><div class="card-subtitle">Scope and current completion</div></div></header>
                <div class="card-body">
                    <p style="margin:0 0 22px; color:#475569">{{ $project->description ?: 'No description has been added to this project.' }}</p>
                    <div class="progress-meta"><span>{{ $project->tasks->where('status', 'completed')->count() }} of {{ $project->tasks->count() }} tasks completed</span><span>{{ $project->progress }}%</span></div>
                    <div class="progress" style="height:8px"><div class="progress-bar" style="width:{{ $project->progress }}%"></div></div>
                </div>
            </section>

            <section class="card">
                <header class="card-header"><div><h2 class="card-title">Project tasks</h2><div class="card-subtitle">Assignments and delivery dates</div></div><a class="btn btn-secondary btn-sm" href="{{ route('tasks.create', ['project' => $project->id]) }}">New task</a></header>
                <div class="table-wrap">
                    <table class="table">
                        <thead><tr><th>Task</th><th>Status</th><th>Assignee</th><th>Due</th><th></th></tr></thead>
                        <tbody>
                            @forelse($project->tasks as $task)
                                <tr>
                                    <td><span class="cell-title">{{ $task->title }}</span><div class="cell-subtitle"><span class="badge badge-{{ $task->priority }}">{{ $task->priority }}</span></div></td>
                                    <td><span class="badge badge-{{ $task->status }}">{{ str($task->status)->replace('_', ' ') }}</span></td>
                                    <td>{{ $task->assignee?->name ?? 'Unassigned' }}</td>
                                    <td class="nowrap {{ $task->is_overdue ? 'text-danger' : '' }}">{{ $task->due_at?->format('M j, Y') ?? '—' }}</td>
                                    <td class="text-right"><a class="btn btn-secondary btn-sm" href="{{ route('tasks.edit', $task) }}">Edit</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="empty-state"><strong>No tasks yet</strong>Add the first deliverable for this project.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <aside class="stack">
            <section class="card">
                <header class="card-header"><h2 class="card-title">Details</h2></header>
                <div class="card-body detail-list">
                    <div class="detail-row"><span class="detail-label">Manager</span><span class="detail-value">{{ $project->manager?->name ?? 'Unassigned' }}</span></div>
                    <div class="detail-row"><span class="detail-label">Budget</span><span class="detail-value">€{{ number_format((float) $project->budget, 2) }}</span></div>
                    <div class="detail-row"><span class="detail-label">Start date</span><span class="detail-value">{{ $project->starts_at?->format('M j, Y') ?? 'Not set' }}</span></div>
                    <div class="detail-row"><span class="detail-label">Due date</span><span class="detail-value">{{ $project->due_at?->format('M j, Y') ?? 'Not set' }}</span></div>
                    <div class="detail-row"><span class="detail-label">Created</span><span class="detail-value">{{ $project->created_at->format('M j, Y') }}</span></div>
                </div>
            </section>
            <section class="card">
                <div class="card-body"><form method="POST" action="{{ route('projects.destroy', $project) }}" data-confirm="Delete this project and all its tasks?">@csrf @method('DELETE')<button class="btn btn-danger btn-block" type="submit">Delete project</button></form></div>
            </section>
        </aside>
    </div>
@endsection

