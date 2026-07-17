@extends('layouts.app')

@section('title', 'Projects')

@section('content')
    <div class="page-heading">
        <div><h1>Projects</h1><p>Track client work, budgets, ownership and delivery progress.</p></div>
        <div class="heading-actions"><a class="btn btn-primary" href="{{ route('projects.create') }}">Create project</a></div>
    </div>

    <section class="card">
        <form class="filters" method="GET">
            <input class="form-control search-control" name="search" type="search" value="{{ request('search') }}" placeholder="Search by project or client">
            <select class="form-select" name="status">
                <option value="">All statuses</option>
                @foreach(['planning', 'active', 'on_hold', 'completed'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ str($status)->replace('_', ' ')->title() }}</option>
                @endforeach
            </select>
            <button class="btn btn-secondary" type="submit">Apply filters</button>
            @if(request()->hasAny(['search', 'status']))<a class="btn btn-secondary" href="{{ route('projects.index') }}">Clear</a>@endif
        </form>

        @if($projects->count())
            <div class="project-grid">
                @foreach($projects as $project)
                    <a class="project-card" href="{{ route('projects.show', $project) }}">
                        <div class="project-card-head">
                            <div><h3>{{ $project->name }}</h3><div class="project-card-client">{{ $project->client }}</div></div>
                            <span class="badge badge-{{ $project->status }}">{{ str($project->status)->replace('_', ' ') }}</span>
                        </div>
                        <p class="project-card-description">{{ $project->description ?: 'No project description has been added.' }}</p>
                        <div class="project-card-footer">
                            <div class="progress-meta"><span>{{ $project->completed_tasks_count }} of {{ $project->tasks_count }} tasks</span><span>{{ $project->progress }}%</span></div>
                            <div class="progress"><div class="progress-bar" style="width: {{ $project->progress }}%"></div></div>
                            <div class="project-card-meta"><span>{{ $project->manager?->name ?? 'Unassigned' }}</span><span>{{ $project->due_at?->format('M j, Y') ?? 'No due date' }}</span></div>
                        </div>
                    </a>
                @endforeach
            </div>
            {{ $projects->links() }}
        @else
            <div class="empty-state"><strong>No projects found</strong>Try adjusting the filters or create a new project.</div>
        @endif
    </section>
@endsection

