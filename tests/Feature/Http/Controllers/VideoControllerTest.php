<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\VideoController
 */
class VideoControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected()
    {
        $videos = Video::factory()->times(3)->create();

        $response = $this->get(route('video.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\VideoController::class,
            'store',
            \App\Http\Requests\VideoStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves()
    {
        $Title = $this->faker->word;
        $Video = $this->faker->word;
        $Link = $this->faker->word;
        $Text1 = $this->faker->word;
        $Text2 = $this->faker->word;

        $response = $this->post(route('video.store'), [
            'Title' => $Title,
            'Video' => $Video,
            'Link' => $Link,
            'Text1' => $Text1,
            'Text2' => $Text2,
        ]);

        $videos = Video::query()
            ->where('Title', $Title)
            ->where('Video', $Video)
            ->where('Link', $Link)
            ->where('Text1', $Text1)
            ->where('Text2', $Text2)
            ->get();
        $this->assertCount(1, $videos);
        $video = $videos->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected()
    {
        $video = Video::factory()->create();

        $response = $this->get(route('video.show', $video));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\VideoController::class,
            'update',
            \App\Http\Requests\VideoUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected()
    {
        $video = Video::factory()->create();
        $Title = $this->faker->word;
        $Video = $this->faker->word;
        $Link = $this->faker->word;
        $Text1 = $this->faker->word;
        $Text2 = $this->faker->word;

        $response = $this->put(route('video.update', $video), [
            'Title' => $Title,
            'Video' => $Video,
            'Link' => $Link,
            'Text1' => $Text1,
            'Text2' => $Text2,
        ]);

        $video->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($Title, $video->Title);
        $this->assertEquals($Video, $video->Video);
        $this->assertEquals($Link, $video->Link);
        $this->assertEquals($Text1, $video->Text1);
        $this->assertEquals($Text2, $video->Text2);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with()
    {
        $video = Video::factory()->create();

        $response = $this->delete(route('video.destroy', $video));

        $response->assertNoContent();

        $this->assertDeleted($video);
    }
}
