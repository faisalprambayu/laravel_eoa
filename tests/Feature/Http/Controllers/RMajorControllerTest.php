<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\RMajor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\RMajorController
 */
class RMajorControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected()
    {
        $rMajors = RMajor::factory()->times(3)->create();

        $response = $this->get(route('r-major.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\RMajorController::class,
            'store',
            \App\Http\Requests\RMajorStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves()
    {
        $Name = $this->faker->word;

        $response = $this->post(route('r-major.store'), [
            'Name' => $Name,
        ]);

        $rMajors = RMajor::query()
            ->where('Name', $Name)
            ->get();
        $this->assertCount(1, $rMajors);
        $rMajor = $rMajors->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected()
    {
        $rMajor = RMajor::factory()->create();

        $response = $this->get(route('r-major.show', $rMajor));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\RMajorController::class,
            'update',
            \App\Http\Requests\RMajorUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected()
    {
        $rMajor = RMajor::factory()->create();
        $Name = $this->faker->word;

        $response = $this->put(route('r-major.update', $rMajor), [
            'Name' => $Name,
        ]);

        $rMajor->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($Name, $rMajor->Name);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with()
    {
        $rMajor = RMajor::factory()->create();

        $response = $this->delete(route('r-major.destroy', $rMajor));

        $response->assertNoContent();

        $this->assertDeleted($rMajor);
    }
}
