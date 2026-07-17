<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Alex Morgan',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        $manager = User::factory()->create([
            'name' => 'Sofia Bennett',
            'email' => 'manager@example.com',
            'role' => 'manager',
        ]);

        $members = collect([
            User::factory()->create(['name' => 'Daniel Kim', 'email' => 'daniel@example.com', 'role' => 'member']),
            User::factory()->create(['name' => 'Maya Patel', 'email' => 'maya@example.com', 'role' => 'member']),
        ]);

        $projects = collect([
            ['name' => 'Commerce Platform', 'client' => 'Northstar Retail', 'status' => 'active', 'priority' => 'high', 'budget' => 48000, 'starts_at' => now()->subDays(40), 'due_at' => now()->addDays(35)],
            ['name' => 'Operations Portal', 'client' => 'Atlas Logistics', 'status' => 'active', 'priority' => 'medium', 'budget' => 32500, 'starts_at' => now()->subDays(20), 'due_at' => now()->addDays(55)],
            ['name' => 'Customer Analytics', 'client' => 'Brightwave', 'status' => 'planning', 'priority' => 'medium', 'budget' => 24000, 'starts_at' => now()->addDays(10), 'due_at' => now()->addDays(90)],
            ['name' => 'Legacy Migration', 'client' => 'Cedar Finance', 'status' => 'completed', 'priority' => 'high', 'budget' => 61000, 'starts_at' => now()->subDays(110), 'due_at' => now()->subDays(8)],
        ])->map(fn (array $data) => Project::create($data + [
            'manager_id' => $manager->id,
            'description' => 'A cross-functional delivery managed through the Laravel Admin Suite demo.',
        ]));

        $taskTitles = [
            'Define technical architecture',
            'Build responsive dashboard',
            'Implement access control',
            'Review database indexes',
            'Prepare release checklist',
        ];

        foreach ($projects as $projectIndex => $project) {
            foreach ($taskTitles as $taskIndex => $title) {
                $completed = $project->status === 'completed' || $taskIndex < $projectIndex;

                Task::create([
                    'project_id' => $project->id,
                    'assigned_to' => $members[$taskIndex % $members->count()]->id,
                    'title' => $title,
                    'status' => $completed ? 'completed' : ($taskIndex === 1 ? 'in_progress' : 'todo'),
                    'priority' => $taskIndex === 2 ? 'high' : 'medium',
                    'due_at' => now()->addDays(($taskIndex * 6) + 3),
                    'completed_at' => $completed ? now()->subDays(2) : null,
                    'description' => 'Demo task with realistic assignment, priority and delivery tracking.',
                ]);
            }
        }

        Activity::create([
            'user_id' => $admin->id,
            'action' => 'seeded',
            'description' => 'Demo workspace was created with sample data.',
        ]);
    }
}

