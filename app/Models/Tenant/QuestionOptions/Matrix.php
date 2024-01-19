<?php

namespace App\Models\Tenant\QuestionOptions;

use App\Models\Tenant\QuestionOption;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Matrix extends Model
{
    use HasTranslations;
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'question_option_matrices';

    /**
     * Translatable columns
     */
    public $translatable = ['label', 'x', 'y'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['label', 'x', 'y'];

    /**
     * Get all of the question options for the simple option.
     */
    public function questionOptions()
    {
        return $this->morphMany(QuestionOption::class, 'question_optionable')
            ->where('question_optionable_type', __CLASS__);
    }
}
