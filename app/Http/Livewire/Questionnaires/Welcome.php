<?php

namespace App\Http\Livewire\Questionnaires;

use App\Models\Tenant\Questionnaire;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Component;

class Welcome extends Component
{
    public Questionnaire | int $questionnaire;

    public Collection $categories;

    public $notshow;

    /**
     * Mount the component.
     */
    public function mount(?Questionnaire $questionnaire)
    {
        if (! $this->questionnaire->is_ready) {
            return false;
        } elseif (! $this->questionnaire->canBeAccessedBy(auth()->user())) {
            abort(403);
        }

        $this->questionnaire = $questionnaire;
        $this->categories = $this->questionnaire->categoriesList()->whereNull('parent_id');

        // Don't show the welcome page if the questionnaire doesn't have categories
        // Or if just have one category/sub-category
        if (
            !$this->categories->count() ||
            ($this->categories->count() === 1 && $this->categories->first()->children->count() < 2) ||
            $this->questionnaire->welcomepage_enable === false
        ) {
            return redirect(route(
                'tenant.questionnaires.show',
                ['questionnaire' => $this->questionnaire->id]
            ));
        }
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        if (!$this->questionnaire->is_ready) {
            return view('livewire.tenant.questionnaires.not-ready');
        }

        return tenantView('livewire.tenant.questionnaires.welcome');
    }

    /**
     * Update welcome page field status
     */
    public function updatedNotshow($value)
    {
        $this->questionnaire->welcomepage_enable = !$value;
        $this->questionnaire->save();
    }
}
