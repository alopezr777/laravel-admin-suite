@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page-heading">
        <div>
            <h1>Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening') }}, {{ str(auth()->user()->name)->before(' ') }}</h1>
            <p>Here is the latest overview of your team and delivery pipeline.</p>
        </div>
        <div class="heading-actions">
            <a class="btn btn-secondary" href="{{ route('tasks.create') }}">New task</a>
            <a class="btn btn-primary" href="{{ route('projects.create') }}">New project</a>
        </div>
    </div>

    <section class="stats-grid" aria-label="Workspace metrics">
        <article class="stat-card">
            <div class="stat-label">Active projects</div>
            <div class="stat-value">{{ $stats['activeProjects'] }}</div>
            <div class="stat-note">Currently in delivery</div>
        </article>
        <article class="stat-card">
            <div class="stat-label">Open tasks</div>
            <div class="stat-value">{{ $stats['openTasks'] }}</div>
            <div class="stat-note">Across all projects</div>
        </article>
        <article class="stat-card">
            <div class="stat-label">Overdue tasks</div>
            <div class="stat-value {{ $stats['overdueTasks'] ? 'text-danger' : '' }}">{{ $stats['overdueTasks'] }}</div>
            <div class="stat-note">Require team attention</div>
        </article>
        <article class="stat-card">
            <div class="stat-label">Team members</div>
            <div class="stat-value">{{ $stats['teamMembers'] }}</div>
            <div class="stat-note">Active accounts</div>
        </article>
    </section>

    <div class="dashboard-grid">
        <div class="stack">
            <section class="card">
                <header class="card-header">
                    <div>
                        <h2 class="card-title">Project portfolio</h2>
                        <div class="card-subtitle">Progress across current client work</div>
                    </div>
                    <a class="btn btn-secondary btn-sm" href="{{ route('projects.index') }}">View all</a>
                </header>
                <div class="table-wrap">
                    <table class="table">
                        <thead><tr><th>Project</th><th>Status</th><th>Manager</th><th>Progress</th><th>Due</th></tr></thead>
                        <tbody>
                            @forelse($projects as $project)
                                <tr>
                                    <td><a class="cell-title" href="{{ route('projects.show', $project) }}">{{ $project->name }}</a><div class="cell-subtitle">{{ $project->client }}</div></td>
                                    <td><span class="badge badge-{{ $project->status }}">{{ str($project->status)->replace('_', ' ') }}</span></td>
                                    <td>{{ $project->manager?->name ?? 'Unassigned' }}</td>
                                    <td style="min-width: 150px"><div class="progress-meta"><span>{{ $project->completed_tasks_count }}/{{ $project->tasks_count }} tasks</span><span>{{ $project->progress }}%</span></div><div class="progress"><div class="progress-bar" style="width: {{ $project->progress }}%"></div></div></td>
                                    <td class="nowrap">{{ $project->due_at?->format('M j') ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="empty-state"><strong>No projects yet</strong>Create your first project to populate the dashboard.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="card">
                <header class="card-header">
                    <div><h2 class="card-title">Upcoming work</h2><div class="card-subtitle">Tasks ordered by their delivery date</div></div>
                    <a class="btn btn-secondary btn-sm" href="{{ route('tasks.index') }}">All tasks</a>
                </header>
                <div class="table-wrap">
                    <table class="table">
                        <thead><tr><th>Task</th><th>Project</th><th>Owner</th><th>Due</th></tr></thead>
                        <tbody>
                            @forelse($upcomingTasks as $task)
                                <tr>
                                    <td><span class="cell-title">{{ $task->title }}</span><div class="cell-subtitle"><span class="badge badge-{{ $task->status }}">{{ str($task->status)->replace('_', ' ') }}</span></div></td>
                                    <td>{{ $task->project->name }}</td>
                                    <td>{{ $task->assignee?->name ?? 'Unassigned' }}</td>
                                    <td class="nowrap {{ $task->is_overdue ? 'text-danger' : '' }}">{{ $task->due_at?->format('M j, Y') ?? 'No date' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="empty-state">No open tasks.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <aside class="card" style="align-self: start">
            <header class="card-header"><div><h2 class="card-title">Recent activity</h2><div class="card-subtitle">Latest changes in the workspace</div></div></header>
            <div class="card-body">
                <div class="list">
                    @forelse($recentActivity as $activity)
                        <div class="list-item">
                            <span class="activity-dot"></span>
                            <div class="list-content">
                                <div class="list-title">{{ $activity->description }}</div>
                                <div class="list-meta">{{ $activity->user?->name ?? 'System' }} · {{ $activity->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state" style="padding: 20px">No activity recorded.</div>
                    @endforelse
                </div>
            </div>
        </aside>
    </div>
@endsection

