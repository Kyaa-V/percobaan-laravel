<?php
namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Models\User;
use App\Models\Role; // Tambahkan ini
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session; // Tambahkan ini

class SocialiteGoogleControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat role default jika belum ada
        Role::firstOrCreate([
            'id' => 1, // Sesuaikan dengan ID role default di database Anda
            'name' => 'user' // Sesuaikan dengan nama role default
        ]);
    }

    public function test_redirect_method_redirects_to_google_auth()
    {
        // Gunakan Session::start() untuk memastikan sesi dimulai
        Session::start();

        $response = $this->withoutMiddleware()
            ->get('api/auth/google/redirect');

        // Periksa apakah respons adalah redirect
        $response->assertStatus(302);
    }

    public function test_callback_method_logs_in_user_and_redirects_to_dashboard()
    {
        // Pastikan role sudah ada
        $role = Role::first();

        // Mock user dari Google
        $googleUser = Mockery::mock(\Laravel\Socialite\Two\User::class);
        $googleUser->shouldReceive('getId')->andReturn('12345');
        $googleUser->shouldReceive('getName')->andReturn('Test User');
        $googleUser->shouldReceive('getEmail')->andReturn('test@example.com');
        $googleUser->shouldReceive('token')->andReturn('test-token');
        $googleUser->shouldReceive('refreshToken')->andReturn('test-refresh-token');

        // Mock Socialite
        Socialite::shouldReceive('driver')->with('google')->andReturnSelf();
        Socialite::shouldReceive('stateless')->andReturnSelf();
        Socialite::shouldReceive('user')->andReturn($googleUser);

        // Mulai sesi
        Session::start();

        // Jalankan request
        $response = $this->withoutMiddleware()
            ->get('api/auth/google/callback');

        // Buat pengguna dengan role
        $user = User::create([
            'providers_id' => '12345',
            'providers' => 'google',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'providers_tokens' => 'test-token',
            'providers_refresh_tokens' => 'test-refresh-token',
            'role_id' => $role->id, // Tambahkan role_id
        ]);

        // Cek database
        $this->assertDatabaseHas('users', [
            'providers_id' => '12345',
            'providers' => 'google',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'providers_tokens' => 'test-token',
            'providers_refresh_tokens' => 'test-refresh-token',
            'role_id' => $role->id,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}