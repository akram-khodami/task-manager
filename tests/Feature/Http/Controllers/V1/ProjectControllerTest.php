<?php

namespace Tests\Feature\Http\Controllers\V1;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Enums\ProjectStatus;
use App\Enums\ProjectPriority;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // ایجاد یک کاربر فرضی و ورود با Sanctum
        $this->user = User::factory()->create();

        $this->actingAs($this->user, 'sanctum');
    }

    public function test_index_returns_paginated_projects()
    {
        Project::factory()->count(3)->create(['owner_id' => $this->user->id]);

        $response = $this->getJson('/api/projects');

        $response->assertOk()
            ->assertJsonStructure(['data', 'links', 'meta']);
    }

    /*******************
     *** test store ***
     *******************/

    public function test_store_validation_fails_with_missing_fields()
    {
        $response = $this->postJson('/api/projects', []); // هیچ داده‌ای نمی‌فرستیم

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'status', 'start_date', 'priority']);
    }

    public function test_store_creates_new_project()
    {
        $data = [
            'name' => 'New Project',
            'status' => ProjectStatus::Active,//random?
            'start_date' => now()->toDateString(),
            'priority' => ProjectPriority::High,//random?
            'description' => 'Test description'
        ];

        $response = $this->postJson('/api/projects', $data);

        $response->assertCreated()
            ->assertJsonPath('data.name', 'New Project');
    }

    /*******************
     *** test show ***
     *******************/
    public function test_user_cannot_view_others_projects()
    {
        $otherUser = User::factory()->create();

        $project = Project::factory()->create(['owner_id' => $otherUser->id]);

        $response = $this->getJson("/api/projects/{$project->id}");

        $response->assertForbidden(); // یا assertStatus(403)
    }

    public function test_show_returns_project_details()
    {
        $project = Project::factory()->create(['owner_id' => $this->user->id]);

        $response = $this->getJson("/api/projects/{$project->id}");

        $response->assertOk()
            ->assertJsonPath('data.id', $project->id);
    }

    /*******************
     *** test update ***
     *******************/

    public function test_update_validation_fails_with_missing_fields()
    {
        $project = Project::factory()->create(['owner_id' => $this->user->id]);

        $response = $this->putJson("/api/projects/{$project->id}", []); // هیچ داده‌ای نمی‌فرستیم

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'status', 'start_date', 'priority']);
    }

    public function test_user_cannot_update_others_projects()
    {
        $otherUser = User::factory()->create();

        $project = Project::factory()->create(['owner_id' => $otherUser->id]);

        $data = [
            'name' => 'Updated Title',
            'status' => ProjectStatus::Active,
            'start_date' => now()->subDay()->toDateString(),
            'priority' => ProjectPriority::High,
            'description' => 'Updated description'
        ];

        $response = $this->putJson("/api/projects/{$project->id}", $data);

        $response->assertForbidden(); // یا assertStatus(403)
    }

    public function test_update_modifies_project()
    {
        $project = Project::factory()->create(['owner_id' => $this->user->id]);

        $data = [
            'name' => 'Updated Title',
            'status' => ProjectStatus::Active,
            'start_date' => now()->subDay()->toDateString(),
            'priority' => ProjectPriority::High,
            'description' => 'Updated description'
        ];

        $response = $this->putJson("/api/projects/{$project->id}", $data);

        $response->assertOk()
            ->assertJsonPath('data.name', 'Updated Title');
    }

    /*******************
     *** test delete ***
     *******************/

    public function test_user_cannot_destroy_others_projects()
    {
        $otherUser = User::factory()->create();

        $project = Project::factory()->create(['owner_id' => $otherUser->id]);

        $response = $this->deleteJson("/api/projects/{$project->id}");

        $response->assertForbidden(); // یا assertStatus(403)
    }

    public function test_destroy_deletes_project()
    {
        $project = Project::factory()->create(['owner_id' => $this->user->id]);

        $response = $this->deleteJson("/api/projects/{$project->id}");

        $response->assertOk()
            ->assertJson(['success' => true]);
    }
}
