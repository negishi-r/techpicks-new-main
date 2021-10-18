<?php

namespace Tests\Feature\ArticleController;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    private $user;

    public function setUp(): void
    {
        parent::setUp();

//        $this->user = factory(User::class)->create();
        $this->user = User::factory()->create([
            'email_verified_at' => null,
        ]);

    }


    public function test_getメソッドでアクセスできる()
    {
        $this->actingAs($this->user);
        $response = $this->get('/article/index');

        $response->assertStatus(200);
//        $response = $this->get('/login');
//
//        $response->assertStatus(200);

    }

}
