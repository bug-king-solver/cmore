<?php

namespace Tests\Feature\Tenant;

use App\Models\User;
use Tests\TenantTestCase;

class ApplicationSettingsTest extends TenantTestCase
{
    protected $createStripeCustomer = true;

    /** @test */
    public function only_owner_can_view_application_settings()
    {
        $owner = User::first();
        $this->actingAs($owner)->get(route('tenant.settings.application'))
            ->assertSuccessful();

        $mortal = User::factory()->create();
        $mortal->enabled = '1';
        $mortal->save();
        $this->actingAs($mortal, 'web')->get(route('tenant.settings.application'))
            ->assertForbidden();
    }
}
