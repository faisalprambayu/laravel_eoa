<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\RPackage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\RPackageController
 */
class RPackageControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected()
    {
        $rPackages = RPackage::factory()->times(3)->create();

        $response = $this->get(route('r-package.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\RPackageController::class,
            'store',
            \App\Http\Requests\RPackageStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves()
    {
        $Name = $this->faker->word;

        $response = $this->post(route('r-package.store'), [
            'Name' => $Name,
        ]);

        $rPackages = RPackage::query()
            ->where('Name', $Name)
            ->get();
        $this->assertCount(1, $rPackages);
        $rPackage = $rPackages->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected()
    {
        $rPackage = RPackage::factory()->create();

        $response = $this->get(route('r-package.show', $rPackage));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\RPackageController::class,
            'update',
            \App\Http\Requests\RPackageUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected()
    {
        $rPackage = RPackage::factory()->create();
        $Name = $this->faker->word;

        $response = $this->put(route('r-package.update', $rPackage), [
            'Name' => $Name,
        ]);

        $rPackage->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($Name, $rPackage->Name);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with()
    {
        $rPackage = RPackage::factory()->create();

        $response = $this->delete(route('r-package.destroy', $rPackage));

        $response->assertNoContent();

        $this->assertDeleted($rPackage);
    }
}
