<?php

namespace Tests\Feature\Http\Livewire\Documents;

use App\Http\Livewire\Documents\Index;
use Livewire\Livewire;
use Tests\TenantTestCase;

/**
 * @see \App\Http\Livewire\Documents\Index
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
