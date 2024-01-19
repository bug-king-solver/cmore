<?php

namespace App\Http\Requests\Api;

use Exception;
use Illuminate\Foundation\Http\FormRequest;

class BaseFormRequest extends FormRequest
{
    /**
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = false;
    protected $availableAbilities = ['write'];

    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize(): bool
    {
        return request()->user()->tokenCan(\implodeArray($this->availableAbilities, ','));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $currentMethod = $this->route()->getActionMethod();
        $resourceActions = ['store', 'update', 'destroy'];

        if (isset($currentMethod) && !in_array($currentMethod, $resourceActions)) {
            /** check if this current method exists  */
            if (method_exists($this, $currentMethod)) {
                return $this->$currentMethod();
            }
        }

        return match ($this->method()) {
            'POST' => $this->store(),
            'PUT', 'PATCH' => $this->update(),
            'DELETE' => $this->destroy()
        };
    }

    /**
     * Get the validation rules that apply to the store request.
     *
     * @return array
     */
    public function store(): array
    {
        return [];
    }

    /**
     * Get the validation rules that apply to the update request.
     *
     * @return array
     */
    public function update(): array
    {
        return [];
    }

    /**
     * Get the validation rules that apply to the delete request.
     *
     * @return array
     */
    public function destroy(): array
    {
        if (isset($this->user->id)) {
            if ($this->user->id === request()->user()->id) {
                abort(403, __('You can not delete yourself.'));
            }
        }

        return [];
    }
}
