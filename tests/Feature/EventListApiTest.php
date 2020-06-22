<?php

namespace Tests\Feature;

use App\Event;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EventListApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testShould_return_right_json()
    {
        factory(Event::class)->create();

        $response = $this->json('GET', route('event.index'));

        $event = Event::first();



        $response->assertStatus(200)
            ->assertJsonFragment([
              'id' => 1,
              'name' => $event->name,
              'color' => $event->color,
              'details' => $event->details,
              'start' => $event->start,
              'end' => $event->end,
            ]);
    }
}
