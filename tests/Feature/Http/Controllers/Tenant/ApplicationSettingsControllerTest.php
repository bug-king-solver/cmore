<?php

namespace Tests\Feature\Http\Controllers\Tenant;

use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Tenant\ApplicationSettingsController
 */
class ApplicationSettingsControllerTest extends TestCase
{
    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $response = $this->get(route('tenant.settings.application'));

        $response->assertOk();
        $response->assertViewIs('tenant.settings.application');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function store_configuration_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $response = $this->post(route('tenant.settings.application.configuration'), [
            // TODO: send request data
        ]);

        $response->assertRedirect(back());

        // TODO: perform additional assertions
    }

    // test cases...
}