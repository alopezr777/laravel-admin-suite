<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $projects = Project::query()
            ->with('manager')
            ->withCount([
                'tasks',
                'tasks as completed_tasks_count' => fn ($query) => $query->where('status', 'completed'),
            ])
            ->when($request->string('search')->toString(), function ($query, string $search) {
                $query->where(fn ($query) => $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('client', 'like', "%{$search}%"));
            })
            ->when($request->string('status')->toString(), fn ($query, string $status) => $query->where('status', $status))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('projects.index', compact('projects'));
    }

    public function create(): View
    {
        return view('projects.create', ['managers' => $this->managers()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $project = Project::create($this->validated($request));
        Activity::record('created', $project, "Created project {$project->name}.");

        return redirect()->route('projects.show', $project)->with('success', 'Project created successfully.');
    }

    public function show(Project $project): View
    {
        $project->load([
            'manager',
            'tasks' => fn ($query) => $query->with('assignee')->orderBy('due_at'),
        ]);

        return view('projects.show', compact('project'));
    }

    public function edit(Project $project): View
    {
        return view('projects.edit', ['project' => $project, 'managers' => $this->managers()]);
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $project->update($this->validated($request));
        Activity::record('updated', $project, "Updated project {$project->name}.");

        return redirect()->route('projects.show', $project)->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $name = $project->name;
        Activity::record('deleted', $project, "Deleted project {$name}.");
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'client' => ['required', 'string', 'max:120'],
            'manager_id' => ['nullable', 'exists:users,id'],
            'status' => ['required', 'in:planning,active,on_hold,completed'],
            'priority' => ['required', 'in:low,medium,high'],
            'budget' => ['required', 'numeric', 'min:0'],
            'starts_at' => ['nullable', 'date'],
            'due_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'description' => ['nullable', 'string', 'max:3000'],
        ]);
    }

    private function managers()
    {
        return User::where('is_active', true)->whereIn('role', ['admin', 'manager'])->orderBy('name')->get();
    }
}

