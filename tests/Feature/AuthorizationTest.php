<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_admins_can_manage_team_members(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($member)->get('/users')->assertForbidden();
        $this->actingAs($admin)->get('/users')->assertOk();
    }
}

