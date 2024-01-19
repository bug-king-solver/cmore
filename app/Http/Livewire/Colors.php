<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Colors extends Component
{
    public $background;

    public $lettering;

    public $other;

    public function mount()
    {
        $userColors = tenant()->colors;
        $backgroundColors = [1, 2, 3, 4, 5, 6];
        $letteringColors = [24, 25, 26, 27, 28, 29];

        for ($i = 1; $i <= 29; $i++) {
            $name = 'esg' . $i;
            $value = $userColors[$name] ?? config("theme.default.colors.{$name}");

            if (in_array($i, $backgroundColors, false)) {
                $this->background[$name] = $value;
            } elseif (in_array($i, $letteringColors, false)) {
                $this->lettering[$name] = $value;
            } else {
                $this->other[$name] = $value;
            }
        }
    }

    public function render()
    {
        return view('livewire.tenant.colors');
    }

    public function update()
    {
        tenant()->update(['colors' => array_merge($this->background, $this->lettering, $this->other)]);

        return redirect()->route('tenant.settings.application');
    }
}
