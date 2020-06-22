<?php

namespace Tests\Feature;

use App\User;
use App\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Tests\TestCase;

class SubmitApiTest extends TestCase
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
    public function testShould_add_event()
    {
        $data = [
         'name' => 'sample event',
         'color' => 'blue',
         'details' => 'sample details',
         'start' => Carbon::now(),
         'end' => Carbon::tomorrow(),
        ];

        $response = $this->actingAs($this->user)
            ->json('POST', route('event.create'), $data);

        $events = Event::all();

        $response->assertStatus(201);

        $this->assertEquals(1, $events->count());
    }

    public function testShould_return_patch_event()
    {
        factory(Event::class)->create();

        $newEventDetails = ['details' => 'newEventDetails'];

        $response = $this->actingAs($this->user)
            ->json('PATCH', route('event.update', ['id' => 1]), $newEventDetails);

        $event = Event::first();

        $response->assertJson(['newEventDetails' => $event->details]);
    }
}
