<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testShould_auth_user()
    {
        $response = $this->actingAs($this->user)->json('GET', route('user'));

        $response
            ->assertStatus(200)
            ->assertJson(['name' => $this->user->name,]);
    }

    public function testShould_return_null_if_not_login()
    {
        $response = $this->json('GET', route('user'));

        $response->assertStatus(200);
        $this->assertEquals("", $response->content());
    }
}
