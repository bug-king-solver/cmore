<?php

namespace App\Http\Livewire\Traits;

trait TabsTrait
{
    /**
     * NOTE: This trait will be use for the normal tabs. it will not redirect to anywhere just chage the tab withiout refresh page
     * Purpose and use of this trait is when we have a pagination in page and when we do the pagination hide the tab.
     * So, this trait will prevent and work smoothly.
     */
    public $tabList;
    public $activeTab;

    /**
     * Boot the component
     */
    public function bootTabsTrait()
    {
        if (!$this->listeners) {
            $this->listeners = [];
        }

        // merge the listeners
        $this->listeners += [
            'activateTab' => 'activateTab',
        ];
    }

    /**
     * Set the current tab
     */
    public function activateTab($value): void
    {
        $this->activeTab = $value;
    }
}
