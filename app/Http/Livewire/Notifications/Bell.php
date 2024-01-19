<?php

namespace App\Http\Livewire\Notifications;

use Illuminate\View\View;

class Bell extends Index
{
    public function render(): View
    {
        $this->notifications = auth()->user()->unreadnotifications()->paginate(config('app.paginate.per_page'));

        return view(
            'livewire.tenant.notifications.bell',
            [
                'notifications' => $this->notifications,
            ]
        )
        ->layoutData(
            [
                'mainBgColor' => 'bg-esg6',
            ]
        );
    }
}
