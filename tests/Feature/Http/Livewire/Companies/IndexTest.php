<?php

namespace Tests\Feature\Http\Livewire\Companies;

use App\Http\Livewire\Companies\Index;
use Livewire\Livewire;
use Tests\TenantTestCase;

/**
 * @see \App\Http\Livewire\Companies\Index
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
