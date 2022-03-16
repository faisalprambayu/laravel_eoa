<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\TeamController
 */
class TeamControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected()
    {
        $teams = Team::factory()->times(3)->create();

        $response = $this->get(route('team.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TeamController::class,
            'store',
            \App\Http\Requests\TeamStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves()
    {
        $Name = $this->faker->word;
        $Title = $this->faker->word;
        $Description = $this->faker->word;
        $Image = $this->faker->word;

        $response = $this->post(route('team.store'), [
            'Name' => $Name,
            'Title' => $Title,
            'Description' => $Description,
            'Image' => $Image,
        ]);

        $teams = Team::query()
            ->where('Name', $Name)
            ->where('Title', $Title)
            ->where('Description', $Description)
            ->where('Image', $Image)
            ->get();
        $this->assertCount(1, $teams);
        $team = $teams->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected()
    {
        $team = Team::factory()->create();

        $response = $this->get(route('team.show', $team));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TeamController::class,
            'update',
            \App\Http\Requests\TeamUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected()
    {
        $team = Team::factory()->create();
        $Name = $this->faker->word;
        $Title = $this->faker->word;
        $Description = $this->faker->word;
        $Image = $this->faker->word;

        $response = $this->put(route('team.update', $team), [
            'Name' => $Name,
            'Title' => $Title,
            'Description' => $Description,
            'Image' => $Image,
        ]);

        $team->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($Name, $team->Name);
        $this->assertEquals($Title, $team->Title);
        $this->assertEquals($Description, $team->Description);
        $this->assertEquals($Image, $team->Image);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with()
    {
        $team = Team::factory()->create();

        $response = $this->delete(route('team.destroy', $team));

        $response->assertNoContent();

        $this->assertDeleted($team);
    }
}
