<?php

namespace Tests\Feature\Http\Controllers\Central;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Central\LoginTenantController
 */
class LoginTenantControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function submit_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $tenant = \App\Models\Tenant::factory()->create();

        $response = $this->post(route('central.tenants.login.submit'), [
            // TODO: send request data
        ]);

        $response->assertRedirect($tenant->route('tenant.login', ['email' => $email]));

        // TODO: perform additional assertions
    }

    // test cases...
}