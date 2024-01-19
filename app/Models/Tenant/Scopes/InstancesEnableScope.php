<?php

namespace App\Models\Tenant\Scopes;

use App\Models\Tenant\BusinessSectorType;
use App\Models\Tenant\QuestionnaireType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class InstancesEnableScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $enabledQuestionnaireTypes = env('ENABLED_QUESTIONNAIRE_TYPES', false);
        $enabledBusinessSectorTypes = env('ENABLED_BUSINESS_SECTOR_TYPES', false);

        if ($enabledQuestionnaireTypes && $model instanceof QuestionnaireType) {
            $builder->whereIn('id', explode(',', $enabledQuestionnaireTypes));
        } elseif ($enabledBusinessSectorTypes && $model instanceof BusinessSectorType) {
            $builder->whereIn('id', explode(',', $enabledBusinessSectorTypes));
        }
    }
}
