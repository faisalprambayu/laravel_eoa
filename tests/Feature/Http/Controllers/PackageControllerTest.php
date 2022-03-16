<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\PackageController
 */
class PackageControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected()
    {
        $packages = Package::factory()->times(3)->create();

        $response = $this->get(route('package.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PackageController::class,
            'store',
            \App\Http\Requests\PackageStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves()
    {
        $Name = $this->faker->word;
        $Price = $this->faker->numberBetween(-10000, 10000);
        $Discount = $this->faker->word;
        $Link = $this->faker->word;
        $Image = $this->faker->word;

        $response = $this->post(route('package.store'), [
            'Name' => $Name,
            'Price' => $Price,
            'Discount' => $Discount,
            'Link' => $Link,
            'Image' => $Image,
        ]);

        $packages = Package::query()
            ->where('Name', $Name)
            ->where('Price', $Price)
            ->where('Discount', $Discount)
            ->where('Link', $Link)
            ->where('Image', $Image)
            ->get();
        $this->assertCount(1, $packages);
        $package = $packages->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected()
    {
        $package = Package::factory()->create();

        $response = $this->get(route('package.show', $package));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PackageController::class,
            'update',
            \App\Http\Requests\PackageUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected()
    {
        $package = Package::factory()->create();
        $Name = $this->faker->word;
        $Price = $this->faker->numberBetween(-10000, 10000);
        $Discount = $this->faker->word;
        $Link = $this->faker->word;
        $Image = $this->faker->word;

        $response = $this->put(route('package.update', $package), [
            'Name' => $Name,
            'Price' => $Price,
            'Discount' => $Discount,
            'Link' => $Link,
            'Image' => $Image,
        ]);

        $package->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($Name, $package->Name);
        $this->assertEquals($Price, $package->Price);
        $this->assertEquals($Discount, $package->Discount);
        $this->assertEquals($Link, $package->Link);
        $this->assertEquals($Image, $package->Image);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with()
    {
        $package = Package::factory()->create();

        $response = $this->delete(route('package.destroy', $package));

        $response->assertNoContent();

        $this->assertDeleted($package);
    }
}
