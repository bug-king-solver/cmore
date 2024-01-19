<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Indicator;
use App\Models\Tenant\Initiative;
use App\Models\Tenant\Question;
use App\Models\Tenant\Scopes\EnabledScope;
use App\Models\Tenant\Scopes\InstancesEnableScope;
use App\Models\Tenant\Sdg;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionOption extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new EnabledScope());
        static::addGlobalScope(new InstancesEnableScope());
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'enabled',
        'question_id',
        'question_option_id',
        'initiative_id',
        'sdg_id',
        'order',
        'weight',
        'children_action',
        'indicator_id',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function option(): BelongsTo
    {
        return $this->morphTo('option', 'question_option_type', 'question_option_id');
    }

    public function indicator()
    {
        return $this->belongsTo(Indicator::class);
    }

    public function initiative()
    {
        return $this->belongsTo(Initiative::class);
    }

    public function sdg()
    {
        return $this->belongsTo(Sdg::class);
    }
}
