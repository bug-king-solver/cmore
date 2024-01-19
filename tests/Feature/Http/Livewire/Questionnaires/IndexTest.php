<?php

namespace Tests\Feature\Http\Livewire\Questionnaires;

use App\Http\Livewire\Questionnaires\Index;
use Livewire\Livewire;
use Tests\TenantTestCase;

/**
 * @see \App\Http\Livewire\Questionnaires\Index
 */
class IndexTest extends TenantTestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(Index::class);

        $component->assertStatus(200);
    }
}
