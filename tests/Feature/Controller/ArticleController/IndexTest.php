<?php

namespace Tests\Feature\Controller\ArticleController;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

    }

    public function test_getメソッドでアクセスできる()
    {
        $this->actingAs($this->user);
        $response = $this->get('article/index');

        $response->assertStatus(200);

    }

}
