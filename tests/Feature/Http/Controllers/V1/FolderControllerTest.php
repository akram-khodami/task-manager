<?php

namespace Tests\Feature\Http\Controllers\V1;

use App\Models\Folder;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FolderControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Project $project;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->actingAs($this->user, 'sanctum');

        $this->project = Project::factory()->create(['owner_id' => $this->user->id]);
    }

    /*******************
     *** test index ***
     *******************/
    public function test_index_returns_folders()
    {
        Folder::factory()->count(3)->create([
            'project_id' => $this->project->id,
        ]);

        $response = $this->getJson('/api/folders');

        $response->assertOk()
            ->assertJsonStructure(['data', 'links', 'meta']);
    }

    /*******************
     *** test store ***
     *******************/
    public function test_store_creates_folder()
    {
        $data = [
            'name' => 'My Folder',
            'project_id' => $this->project->id,
        ];

        $response = $this->postJson('/api/folders', $data);

        $response->assertCreated()
            ->assertJsonPath('data.name', 'My Folder');
    }

    public function test_store_validation_fails_with_missing_fields()
    {
        $response = $this->postJson('/api/folders', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'project_id']);
    }

    /*******************
     *** test show ***
     *******************/
    public function test_show_returns_folder()
    {
        $folder = Folder::factory()->create(['project_id' => $this->project->id,]);

        $response = $this->getJson("/api/folders/{$folder->id}");

        $response->assertOk()
            ->assertJsonPath('data.id', $folder->id);
    }

    public function test_user_cannot_view_others_folder()
    {
        $otherUser = User::factory()->create();
        $otherProject = Project::factory()->create(['owner_id' => $otherUser->id]);
        $folder = Folder::factory()->create(['project_id' => $otherProject->id]);

        $response = $this->getJson("/api/folders/{$folder->id}");

        $response->assertForbidden();
    }

    /*******************
     *** test update ***
     *******************/

    public function test_update_folder()
    {
        $folder = Folder::factory()->create(['project_id' => $this->project->id]);

        $response = $this->putJson("/api/folders/{$folder->id}", [
            'name' => 'Updated Folder',
            'project_id' => $this->project->id
        ]);

        $response->assertOk()
            ->assertJsonPath('data.name', 'Updated Folder');
    }

    public function test_update_validation_fails_with_missing_fields()
    {
        $folder = Folder::factory()->create(['project_id' => $this->project->id]);

        $response = $this->putJson("/api/folders/{$folder->id}", []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_user_cannot_update_others_folder()
    {
        $otherUser = User::factory()->create();
        $otherProject = Project::factory()->create(['owner_id' => $otherUser->id]);
        $folder = Folder::factory()->create(['project_id' => $otherProject->id]);

        $response = $this->putJson("/api/folders/{$folder->id}",
            [
                'name' => 'Hacked Folder',
                'parent_id' => NULL,//or other folder
//                'project_id' => $this->project->id,//user can not changer project_id in update
            ]);

        $response->assertForbidden();
    }

    /*******************
     *** test delete ***
     *******************/

    public function test_destroy_folder()
    {
        $folder = Folder::factory()->create(['project_id' => $this->project->id]);

        $response = $this->deleteJson("/api/folders/{$folder->id}");

        $response->assertOk()
            ->assertJson(['success' => true]);
    }

    public function test_user_cannot_destroy_others_folder()
    {
        $otherUser = User::factory()->create();
        $otherProject = Project::factory()->create(['owner_id' => $otherUser->id]);
        $folder = Folder::factory()->create(['project_id' => $otherProject->id]);

        $response = $this->deleteJson("/api/folders/{$folder->id}");

        $response->assertForbidden();
    }

    public function test_destroy_fails_if_folder_has_children()
    {
        $folder = Folder::factory()->create(['project_id' => $this->project->id]);

        Folder::factory()->create([
            'parent_id' => $folder->id,
            'project_id' => $this->project->id
        ]);

        $response = $this->deleteJson("/api/folders/{$folder->id}");

        $response->assertStatus(409)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_destroy_fails_if_folder_has_tasks()
    {
        $folder = Folder::factory()->create(['project_id' => $this->project->id]);

        Task::factory()->create(['folder_id' => $folder->id]);

        $response = $this->deleteJson("/api/folders/{$folder->id}");

        $response->assertStatus(409)
            ->assertJson([
                'success' => false,
            ]);
    }
}
