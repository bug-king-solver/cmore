<?php

namespace App\Http\Livewire\Questionnaires;

use App\Models\Tenant\Answer;
use Livewire\Component;

class QuestionComment extends Component
{
    public $answer;
    public $comment;

    public function mount()
    {
        $this->comment = $this->answer->comment;
    }

    public function updatedComment()
    {
        $this->answer->update([
            'comment' => $this->comment,
        ]);
    }

    public function render()
    {
        return view('livewire.tenant.questionnaires.question-comment');
    }
}
