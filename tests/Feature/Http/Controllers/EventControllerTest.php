<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\EventController
 */
class EventControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected()
    {
        $events = Event::factory()->times(3)->create();

        $response = $this->get(route('event.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\EventController::class,
            'store',
            \App\Http\Requests\EventStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves()
    {
        $Name = $this->faker->word;
        $Image = $this->faker->word;

        $response = $this->post(route('event.store'), [
            'Name' => $Name,
            'Image' => $Image,
        ]);

        $events = Event::query()
            ->where('Name', $Name)
            ->where('Image', $Image)
            ->get();
        $this->assertCount(1, $events);
        $event = $events->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected()
    {
        $event = Event::factory()->create();

        $response = $this->get(route('event.show', $event));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\EventController::class,
            'update',
            \App\Http\Requests\EventUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected()
    {
        $event = Event::factory()->create();
        $Name = $this->faker->word;
        $Image = $this->faker->word;

        $response = $this->put(route('event.update', $event), [
            'Name' => $Name,
            'Image' => $Image,
        ]);

        $event->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($Name, $event->Name);
        $this->assertEquals($Image, $event->Image);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with()
    {
        $event = Event::factory()->create();

        $response = $this->delete(route('event.destroy', $event));

        $response->assertNoContent();

        $this->assertDeleted($event);
    }
}
