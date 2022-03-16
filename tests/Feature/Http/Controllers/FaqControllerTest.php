<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Faq;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\FaqController
 */
class FaqControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected()
    {
        $faqs = Faq::factory()->times(3)->create();

        $response = $this->get(route('faq.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\FaqController::class,
            'store',
            \App\Http\Requests\FaqStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves()
    {
        $Question = $this->faker->word;
        $Answer = $this->faker->word;

        $response = $this->post(route('faq.store'), [
            'Question' => $Question,
            'Answer' => $Answer,
        ]);

        $faqs = Faq::query()
            ->where('Question', $Question)
            ->where('Answer', $Answer)
            ->get();
        $this->assertCount(1, $faqs);
        $faq = $faqs->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected()
    {
        $faq = Faq::factory()->create();

        $response = $this->get(route('faq.show', $faq));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\FaqController::class,
            'update',
            \App\Http\Requests\FaqUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected()
    {
        $faq = Faq::factory()->create();
        $Question = $this->faker->word;
        $Answer = $this->faker->word;

        $response = $this->put(route('faq.update', $faq), [
            'Question' => $Question,
            'Answer' => $Answer,
        ]);

        $faq->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($Question, $faq->Question);
        $this->assertEquals($Answer, $faq->Answer);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with()
    {
        $faq = Faq::factory()->create();

        $response = $this->delete(route('faq.destroy', $faq));

        $response->assertNoContent();

        $this->assertDeleted($faq);
    }
}
