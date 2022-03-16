<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\RClass;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\RClassController
 */
class RClassControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected()
    {
        $rClasses = RClass::factory()->times(3)->create();

        $response = $this->get(route('r-class.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\RClassController::class,
            'store',
            \App\Http\Requests\RClassStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves()
    {
        $Name = $this->faker->word;

        $response = $this->post(route('r-class.store'), [
            'Name' => $Name,
        ]);

        $rClasses = RClass::query()
            ->where('Name', $Name)
            ->get();
        $this->assertCount(1, $rClasses);
        $rClass = $rClasses->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected()
    {
        $rClass = RClass::factory()->create();

        $response = $this->get(route('r-class.show', $rClass));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\RClassController::class,
            'update',
            \App\Http\Requests\RClassUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected()
    {
        $rClass = RClass::factory()->create();
        $Name = $this->faker->word;

        $response = $this->put(route('r-class.update', $rClass), [
            'Name' => $Name,
        ]);

        $rClass->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($Name, $rClass->Name);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with()
    {
        $rClass = RClass::factory()->create();

        $response = $this->delete(route('r-class.destroy', $rClass));

        $response->assertNoContent();

        $this->assertDeleted($rClass);
    }
}
