<?php

namespace App\Http\Livewire\Questionnaires\Taxonomy\Modals;

use App\Models\Tenant\Questionnaires\Taxonomy\Taxonomy;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cookie;
use Illuminate\View\View;
use LivewireUI\Modal\ModalComponent;

class Submit extends ModalComponent
{
    use AuthorizesRequests;

    /**
     * Modal max width.
     * @return string
     */
    public static function modalMaxWidth(): string
    {
        return 'xl';
    }

    /**
     * Modal the component.
     */
    public function mount(Taxonomy $taxonomy)
    {
        $this->taxonomy = $taxonomy;
    }

    /**
     * Render the component.
     * @return \Illuminate\View\View
     */
    public function render(): View
    {
        return view('livewire.tenant.taxonomy.modals.submit');
    }

    /**
     * Save the taxonomy.
     * @return void
     */
    public function save()
    {
        $questionnaire = $this->taxonomy->questionnaire;
        $questionnaire->completed_at = carbon()->now();
        $questionnaire->submitted_at = carbon()->now();
        $questionnaire->update();

        $this->taxonomy->completed = true;
        $this->taxonomy->completed_at = now();
        $this->taxonomy->save();

        $questionnaire->submit();

        session()->flash(
            'messageText',
            __('The taxonomy questionnaire has been completed successfully')
        );

        session()->flash('messageType', 'success');
        session()->flash('messageTitle', __('Success'));
        return redirect()->route('tenant.questionnaires.panel');
    }
}
