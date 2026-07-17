@extends('layouts.app')
@section('title', 'Tasks')
@section('content')
    <div class="page-heading"><div><h1>Tasks</h1><p>Review assignments, priorities and deadlines across the portfolio.</p></div><div class="heading-actions"><a class="btn btn-primary" href="{{ route('tasks.create') }}">Create task</a></div></div>
    <section class="card">
        <form class="filters" method="GET">
            <input class="form-control search-control" name="search" type="search" value="{{ request('search') }}" placeholder="Search tasks">
            <select class="form-select" name="project"><option value="">All projects</option>@foreach($projects as $project)<option value="{{ $project->id }}" @selected((string) request('project') === (string) $project->id)>{{ $project->name }}</option>@endforeach</select>
            <select class="form-select" name="status"><option value="">All statuses</option>@foreach(['todo', 'in_progress', 'review', 'completed'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ str($status)->replace('_', ' ')->title() }}</option>@endforeach</select>
            <button class="btn btn-secondary" type="submit">Apply</button>
        </form>
        <div class="table-wrap">
            <table class="table">
                <thead><tr><th>Task</th><th>Project</th><th>Status</th><th>Priority</th><th>Assignee</th><th>Due date</th><th></th></tr></thead>
                <tbody>
                    @forelse($tasks as $task)
                        <tr>
                            <td><span class="cell-title">{{ $task->title }}</span></td>
                            <td><a href="{{ route('projects.show', $task->project) }}">{{ $task->project->name }}</a></td>
                            <td><span class="badge badge-{{ $task->status }}">{{ str($task->status)->replace('_', ' ') }}</span></td>
                            <td><span class="badge badge-{{ $task->priority }}">{{ $task->priority }}</span></td>
                            <td>{{ $task->assignee?->name ?? 'Unassigned' }}</td>
                            <td class="nowrap {{ $task->is_overdue ? 'text-danger' : '' }}">{{ $task->due_at?->format('M j, Y') ?? '—' }}</td>
                            <td><div class="table-actions"><a class="btn btn-secondary btn-sm" href="{{ route('tasks.edit', $task) }}">Edit</a><form method="POST" action="{{ route('tasks.destroy', $task) }}" data-confirm="Delete this task?">@csrf @method('DELETE')<button class="btn btn-danger btn-sm" type="submit">Delete</button></form></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="empty-state"><strong>No tasks found</strong>Create a task or adjust the current filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $tasks->links() }}
    </section>
@endsection

