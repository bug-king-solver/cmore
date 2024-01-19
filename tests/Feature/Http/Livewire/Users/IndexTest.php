<?php

namespace Tests\Feature\Http\Livewire\Users;

use App\Http\Livewire\Users\Index;
use Livewire\Livewire;
use Tests\TenantTestCase;

/**
 * @see \App\Http\Livewire\Users\Index
 */
class IndexTest extends TenantTestCase
{
    protected $createStripeCustomer = true;

    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(Index::class);

        $component->assertStatus(200);
    }
}
