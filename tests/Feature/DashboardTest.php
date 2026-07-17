<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_dashboard_displays_workspace_metrics(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $project = Project::create([
            'name' => 'Demo project',
            'client' => 'Demo client',
            'status' => 'active',
            'priority' => 'high',
            'budget' => 10000,
        ]);
        Task::create([
            'project_id' => $project->id,
            'title' => 'Demo task',
            'status' => 'todo',
            'priority' => 'medium',
        ]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Demo project')
            ->assertSee('Open tasks');
    }
}

