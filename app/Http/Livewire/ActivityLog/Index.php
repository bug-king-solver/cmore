<?php

namespace App\Http\Livewire\ActivityLog;

use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

class Index extends Component
{
    use WithPagination;

    protected $listeners = [
        'activitylogChanged' => '$refresh',
    ];

    public function render(): View
    {
        $activities = Activity::with('causer')->orderBy('id', 'desc')->paginate(config('app.paginate.per_page'));

        return view(
            'livewire.tenant.activitylog.index',
            [
                'activities' => $activities,
            ]
        );
    }
}
