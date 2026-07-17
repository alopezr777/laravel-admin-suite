<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_a_project(): void
    {
        $user = User::factory()->create(['role' => 'manager']);

        $response = $this->actingAs($user)->post('/projects', [
            'name' => 'Client portal',
            'client' => 'Acme Ltd',
            'manager_id' => $user->id,
            'status' => 'planning',
            'priority' => 'high',
            'budget' => 25000,
            'starts_at' => '2026-07-20',
            'due_at' => '2026-09-30',
            'description' => 'Secure customer portal implementation.',
        ]);

        $project = Project::first();

        $response->assertRedirect(route('projects.show', $project));
        $this->assertDatabaseHas('projects', ['name' => 'Client portal', 'client' => 'Acme Ltd']);
        $this->assertDatabaseHas('activities', ['action' => 'created', 'subject_id' => $project->id]);
    }

    public function test_project_filters_match_client_name(): void
    {
        $user = User::factory()->create();
        Project::create(['name' => 'Portal', 'client' => 'Northstar', 'status' => 'active', 'priority' => 'medium', 'budget' => 5000]);
        Project::create(['name' => 'Warehouse', 'client' => 'Atlas', 'status' => 'planning', 'priority' => 'low', 'budget' => 3000]);

        $this->actingAs($user)
            ->get('/projects?search=Northstar')
            ->assertOk()
            ->assertSee('Portal')
            ->assertDontSee('Warehouse');
    }
}

