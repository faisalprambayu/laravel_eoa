<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\RegistrationController
 */
class RegistrationControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected()
    {
        $registrations = Registration::factory()->times(3)->create();

        $response = $this->get(route('registration.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\RegistrationController::class,
            'store',
            \App\Http\Requests\RegistrationStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves()
    {
        $Name = $this->faker->word;
        $Email = $this->faker->word;
        $Handphone = $this->faker->numberBetween(-10000, 10000);
        $School_Origin = $this->faker->word;
        $Class = $this->faker->numberBetween(-10000, 10000);
        $Major = $this->faker->numberBetween(-10000, 10000);
        $Package = $this->faker->numberBetween(-10000, 10000);

        $response = $this->post(route('registration.store'), [
            'Name' => $Name,
            'Email' => $Email,
            'Handphone' => $Handphone,
            'School_Origin' => $School_Origin,
            'Class' => $Class,
            'Major' => $Major,
            'Package' => $Package,
        ]);

        $registrations = Registration::query()
            ->where('Name', $Name)
            ->where('Email', $Email)
            ->where('Handphone', $Handphone)
            ->where('School_Origin', $School_Origin)
            ->where('Class', $Class)
            ->where('Major', $Major)
            ->where('Package', $Package)
            ->get();
        $this->assertCount(1, $registrations);
        $registration = $registrations->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected()
    {
        $registration = Registration::factory()->create();

        $response = $this->get(route('registration.show', $registration));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\RegistrationController::class,
            'update',
            \App\Http\Requests\RegistrationUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected()
    {
        $registration = Registration::factory()->create();
        $Name = $this->faker->word;
        $Email = $this->faker->word;
        $Handphone = $this->faker->numberBetween(-10000, 10000);
        $School_Origin = $this->faker->word;
        $Class = $this->faker->numberBetween(-10000, 10000);
        $Major = $this->faker->numberBetween(-10000, 10000);
        $Package = $this->faker->numberBetween(-10000, 10000);

        $response = $this->put(route('registration.update', $registration), [
            'Name' => $Name,
            'Email' => $Email,
            'Handphone' => $Handphone,
            'School_Origin' => $School_Origin,
            'Class' => $Class,
            'Major' => $Major,
            'Package' => $Package,
        ]);

        $registration->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($Name, $registration->Name);
        $this->assertEquals($Email, $registration->Email);
        $this->assertEquals($Handphone, $registration->Handphone);
        $this->assertEquals($School_Origin, $registration->School_Origin);
        $this->assertEquals($Class, $registration->Class);
        $this->assertEquals($Major, $registration->Major);
        $this->assertEquals($Package, $registration->Package);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with()
    {
        $registration = Registration::factory()->create();

        $response = $this->delete(route('registration.destroy', $registration));

        $response->assertNoContent();

        $this->assertDeleted($registration);
    }
}
