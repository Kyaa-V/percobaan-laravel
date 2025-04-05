<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Experience;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExperienceControllerTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class); 
    }
    protected function authenticate()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        return $user;
    }

    public function test_post_experience_success()
    {
        $user = $this->authenticate();

        $data = [
            'position' => 'Web Developer',
            'company' => 'PT Digital Nusantara',
            'location' => 'Jakarta',
            'status' => 'Internship',
            'your_skills' => 'Laravel, Vue.js',
            'users_id' => $user->id,
            'start_date' => '2023-01-01',
            'end_date' => '2023-06-01',
        ];

        $response = $this->postJson('/api/postExp', $data);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Berhasil Memasukkan data experience',
            ]);

        $this->assertDatabaseHas('experiences', [
            'company' => 'PT Digital Nusantara',
            'users_id' => $user->id,
        ]);
    }

    public function test_get_experience_by_user()
    {
        $user = $this->authenticate();

        Experience::factory()->create([
            'users_id' => $user->id,
            'company' => 'PT Test Get',
        ]);

        $response = $this->getJson("/api/getExpByAuthorId/{$user->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Berhasil mendaptkan data pengalaman',
            ]);
    }

    public function test_edit_experience()
    {
        $user = $this->authenticate();

        $experience = Experience::factory()->create([
            'users_id' => $user->id,
            'position' => 'Old Position',
        ]);

        $response = $this->patchJson("/api/editExpById/{$experience->id}", [
            'position' => 'Updated Position',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'message' => 'Komentar berhasil diperbarui',
                ]
            ]);

        $this->assertDatabaseHas('experiences', [
            'id' => $experience->id,
            'position' => 'Updated Position',
        ]);
    }

    public function test_delete_experience()
    {
        $user = $this->authenticate();

        $experience = Experience::factory()->create([
            'users_id' => $user->id,
        ]);

        $response = $this->deleteJson("/api/deleteExpById/{$experience->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'message' => 'Data berhasil dihapus',
                ]
            ]);

        $this->assertDatabaseMissing('experiences', [
            'id' => $experience->id,
        ]);
    }
}
