<?php

namespace App\Http\Livewire\Api\Tokens\Modals;

use App\Models\Enums\SanctumAbilities;
use App\Models\Tenant\Api\ApiTokens;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use LivewireUI\Modal\ModalComponent;
use Livewire\WithPagination;

class Form extends ModalComponent
{
    use AuthorizesRequests;
    use WithPagination;

    public ApiTokens | int $token;

    public $created = false;

    public $name;

    public $abilities;

    public $abilitiesList;

    public $userId;

    public $usersList;

    public $notificationData;

    /**
     * Validation rules.
     */
    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:personal_access_tokens,name,' . $this->token->id . ',id,tokenable_id,' . $this->userId
            ],
            'abilities' => ['required', 'array'],
            'abilities.*' => ['required', Rule::in(SanctumAbilities::keys())],
            'userId' => ['required'],
        ];
    }

    /**
     * Modal max width.
     */
    public static function modalMaxWidth(): string
    {
        return '3xl';
    }

    /**
     * Mount the component.
     */
    public function mount(?ApiTokens $token)
    {
        $this->token = $token;

        $this->authorize(!$this->token->exists ? 'api-tokens.create' : "api-tokens.update.{$this->token->id}");

        $this->abilitiesList = parseKeyValueForSelect(SanctumAbilities::toArray());

        $this->usersList = parseDataForSelect(User::OnlyAppUsers()->get()->toArray(), 'id', 'name');

        if ($this->token->exists) {
            $this->name = $this->token->name;
            $this->abilities = json_decode($this->token->abilities, true);
            $this->userId = $this->token->tokenable_id;
        }
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        return view(
            $this->created
                ? 'livewire.tenant.modals.notification-api'
                : 'livewire.tenant.api.tokens.form'
        );
    }

    /**
     * Save the token.
     */
    public function save()
    {
        $this->rules();

        $this->authorize(!$this->token->exists ? 'api-tokens.create' : "api-tokens.update.{$this->token->id}");

        $data = $this->validate();

        if (!$this->token->exists) {
            $this->created = true;
            $user = User::find($data['userId']);
            $plainTextToken = $user->createToken($data['name'], $data['abilities'])->plainTextToken;
            $this->notificationData = [
                'title' => __('You have created a new API token!'),
                'message' => __('Please keep it safe and do not share it with anyone!') . '<br>' . __('You can revoke this token at any time by deleting it from your profile page.'),
                'token' => $plainTextToken,
            ];
        } else {
            $this->token->update($data);
            $this->notificationData = [
                'title' => __('You have updated the API token'),
                'message' => __('Please keep it safe and do not share it with anyone! \n You can revoke this token at any time by deleting it from your profile page'),
            ];
        }

        $this->emit('apiTokenChanged');
    }
}
