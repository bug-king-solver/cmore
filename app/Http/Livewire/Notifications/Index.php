<?php

namespace App\Http\Livewire\Notifications;

use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $notifications;

    public function render(): View
    {
        $this->notifications = auth()->user()->notifications()->paginate(config('app.paginate.per_page'));

        return view(
            'livewire.tenant.notifications.index',
            [
                'notifications' => $this->notifications,
            ]
        );
    }

    public function markAsRead($id = null)
    {
        auth()->user()
            ->unreadNotifications
            ->when($id, function ($query) use ($id) {
                return $query->where('id', $id);
            })
            ->markAsRead();

        return response()->noContent();
    }

    public function markAsUnread($id = null)
    {
        auth()->user()
            ->readNotifications
            ->where('id', $id)
            ->markAsUnread();

        return response()->noContent();
    }

    public function delete($id = null)
    {
        auth()->user()
            ->notifications()
            ->when($id, function ($query) use ($id) {
                return $query->where('id', $id);
            })
            ->delete();

        return response()->noContent();
    }
}
