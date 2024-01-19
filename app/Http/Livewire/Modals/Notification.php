<?php

namespace App\Http\Livewire\Modals;

use LivewireUI\Modal\ModalComponent;

class Notification extends ModalComponent
{
    public $data;

    /**
     * Get the view / contents that represent the component.
     */
    public static function modalMaxWidth(): string
    {
        return 'xl';
    }

    /**
     * Indicates if the modal should close when the escape key is pressed.
     */
    public static function closeModalOnEscape(): bool
    {
        return false;
    }

    /**
     * Indicates if the modal should close when the backdrop is clicked.
     */
    public static function closeModalOnClickAway(): bool
    {
        return false;
    }

    /**
     * Mount the component.
     */
    public function mount($data)
    {
        if (!is_array($data)) {
            throw new \Exception('Notification data must be an array');
        }

        $this->data = $data;
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('livewire.tenant.modals.notifications', $this->data);
    }
}
