<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PlanUpgrade implements Rule
{
    protected $currentPlan;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($currentPlan)
    {
        $this->currentPlan = $currentPlan;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $plansPriority = config('saas.priority');

        return is_null($this->currentPlan)
            || array_search($value, $plansPriority, false) >= array_search($this->currentPlan, $plansPriority, false);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You cannot downgrade the plan.';
    }
}
