<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $tasks = Task::query()
            ->with(['project', 'assignee'])
            ->when($request->string('search')->toString(), fn ($query, string $search) => $query->where('title', 'like', "%{$search}%"))
            ->when($request->string('status')->toString(), fn ($query, string $status) => $query->where('status', $status))
            ->when($request->integer('project'), fn ($query, int $project) => $query->where('project_id', $project))
            ->orderByRaw('due_at IS NULL, due_at')
            ->paginate(12)
            ->withQueryString();

        return view('tasks.index', [
            'tasks' => $tasks,
            'projects' => Project::orderBy('name')->get(),
        ]);
    }

    public function create(Request $request): View
    {
        return view('tasks.create', $this->formData((int) $request->integer('project')));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['completed_at'] = $data['status'] === 'completed' ? now() : null;
        $task = Task::create($data);
        Activity::record('created', $task, "Created task {$task->title}.");

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function edit(Task $task): View
    {
        return view('tasks.edit', $this->formData() + ['task' => $task]);
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $data = $this->validated($request);
        $data['completed_at'] = $data['status'] === 'completed'
            ? ($task->completed_at ?? now())
            : null;

        $task->update($data);
        Activity::record('updated', $task, "Updated task {$task->title}.");

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        Activity::record('deleted', $task, "Deleted task {$task->title}.");
        $task->delete();

        return back()->with('success', 'Task deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'title' => ['required', 'string', 'max:160'],
            'status' => ['required', 'in:todo,in_progress,review,completed'],
            'priority' => ['required', 'in:low,medium,high'],
            'due_at' => ['nullable', 'date'],
            'description' => ['nullable', 'string', 'max:3000'],
        ]);
    }

    private function formData(int $selectedProject = 0): array
    {
        return [
            'projects' => Project::orderBy('name')->get(),
            'users' => User::where('is_active', true)->orderBy('name')->get(),
            'selectedProject' => $selectedProject,
        ];
    }
}

