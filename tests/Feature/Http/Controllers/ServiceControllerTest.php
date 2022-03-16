<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ServiceController
 */
class ServiceControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected()
    {
        $services = Service::factory()->times(3)->create();

        $response = $this->get(route('service.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ServiceController::class,
            'store',
            \App\Http\Requests\ServiceStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves()
    {
        $Title = $this->faker->word;
        $Image = $this->faker->word;

        $response = $this->post(route('service.store'), [
            'Title' => $Title,
            'Image' => $Image,
        ]);

        $services = Service::query()
            ->where('Title', $Title)
            ->where('Image', $Image)
            ->get();
        $this->assertCount(1, $services);
        $service = $services->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected()
    {
        $service = Service::factory()->create();

        $response = $this->get(route('service.show', $service));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ServiceController::class,
            'update',
            \App\Http\Requests\ServiceUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected()
    {
        $service = Service::factory()->create();
        $Title = $this->faker->word;
        $Image = $this->faker->word;

        $response = $this->put(route('service.update', $service), [
            'Title' => $Title,
            'Image' => $Image,
        ]);

        $service->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($Title, $service->Title);
        $this->assertEquals($Image, $service->Image);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with()
    {
        $service = Service::factory()->create();

        $response = $this->delete(route('service.destroy', $service));

        $response->assertNoContent();

        $this->assertDeleted($service);
    }
}
