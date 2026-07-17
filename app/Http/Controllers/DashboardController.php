<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'activeProjects' => Project::where('status', 'active')->count(),
            'openTasks' => Task::whereNot('status', 'completed')->count(),
            'overdueTasks' => Task::whereNot('status', 'completed')->whereDate('due_at', '<', today())->count(),
            'teamMembers' => User::where('is_active', true)->count(),
        ];

        $projects = Project::query()
            ->with('manager')
            ->withCount([
                'tasks',
                'tasks as completed_tasks_count' => fn ($query) => $query->where('status', 'completed'),
            ])
            ->orderByRaw("CASE status WHEN 'active' THEN 1 WHEN 'planning' THEN 2 WHEN 'on_hold' THEN 3 ELSE 4 END")
            ->limit(5)
            ->get();

        $upcomingTasks = Task::query()
            ->with(['project', 'assignee'])
            ->whereNot('status', 'completed')
            ->orderBy('due_at')
            ->limit(6)
            ->get();

        $recentActivity = Activity::with('user')->latest()->limit(6)->get();

        return view('dashboard', compact('stats', 'projects', 'upcomingTasks', 'recentActivity'));
    }
}

