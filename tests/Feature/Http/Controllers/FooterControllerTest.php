<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Footer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\FooterController
 */
class FooterControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected()
    {
        $footers = Footer::factory()->times(3)->create();

        $response = $this->get(route('footer.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\FooterController::class,
            'store',
            \App\Http\Requests\FooterStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves()
    {
        $Logo = $this->faker->word;
        $Link = $this->faker->word;

        $response = $this->post(route('footer.store'), [
            'Logo' => $Logo,
            'Link' => $Link,
        ]);

        $footers = Footer::query()
            ->where('Logo', $Logo)
            ->where('Link', $Link)
            ->get();
        $this->assertCount(1, $footers);
        $footer = $footers->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected()
    {
        $footer = Footer::factory()->create();

        $response = $this->get(route('footer.show', $footer));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\FooterController::class,
            'update',
            \App\Http\Requests\FooterUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected()
    {
        $footer = Footer::factory()->create();
        $Logo = $this->faker->word;
        $Link = $this->faker->word;

        $response = $this->put(route('footer.update', $footer), [
            'Logo' => $Logo,
            'Link' => $Link,
        ]);

        $footer->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($Logo, $footer->Logo);
        $this->assertEquals($Link, $footer->Link);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with()
    {
        $footer = Footer::factory()->create();

        $response = $this->delete(route('footer.destroy', $footer));

        $response->assertNoContent();

        $this->assertDeleted($footer);
    }
}
