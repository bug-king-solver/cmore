<?php

namespace App\Models\Tenant\QuestionOptions;

use App\Models\Tenant\QuestionOption;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Simple extends Model
{
    use HasTranslations;
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'question_option_simples';

    /**
     * Translatable columns
     */
    public $translatable = ['label'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'label', 'value'];

    protected $casts = [
        'id' => 'integer',
        'label' => 'string',
        'value' => 'string',
    ];

    protected $appends = ['shouldShow', 'is_co2_equivalent'];

    /**
     * Get all of the question options for the simple option.
     */
    public function questionOptions()
    {
        return $this->morphMany(QuestionOption::class, 'question_optionable')
            ->where('question_optionable_type', __CLASS__);
    }

    /**
     * Get the is_co2_equivalent attribute.
     */
    public function getIsCo2EquivalentAttribute()
    {
        return preg_match('/-co2eq/i', $this->value ?? '');
    }

    /**
     * Control, based in others conditions, if the option is hidden by default.
     * This attribute is used in the frontend to hide the option , passe from the $appends property.
     * @return bool
     */
    public function getShouldShowAttribute(): bool
    {
        $canShow = true;
        if ($this->is_co2_equivalent) {
            $canShow = false;
        }
        return $canShow
            ? 1
            : 0;
    }
}
