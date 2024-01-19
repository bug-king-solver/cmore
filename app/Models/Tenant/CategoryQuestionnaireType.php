<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryQuestionnaireType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'category_questionnaire_type';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'questionnaire_type_id', 'category_id'];

    public function questionnaireTypes()
    {
        return $this->belongsTo(QuestionnaireType::class);
    }

    public function categories()
    {
        return $this->belongsTo(Category::class);
    }
}
