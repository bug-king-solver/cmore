<?php

namespace App\Http\Livewire\Users\Modals;

use App\Http\Livewire\Traits\HasCustomColumns;
use Closure;
use LivewireUI\Modal\ModalComponent;
use PragmaRX\Google2FA\Google2FA;

class TwoFa extends ModalComponent
{
    use HasCustomColumns;

    protected $feature = 'users';

    public $secret;

    public $qr_image;

    public $otp;

    public function render()
    {
        return view('livewire.tenant.users.twofa');
    }

    protected function rules()
    {
        return $this->mergeCustomRules([
            'secret' => ['required', 'string', 'min:8'],
            'otp' => [
                'required',
                'string',
                function (string $attribute, mixed $value, Closure $fail) {
                    $secret = $this->secret;
                    $google2fa = new Google2FA();
                    $valid = $google2fa->verify($value, $secret);

                    if (! $valid) {
                        $fail('Code is invalid.');
                    }
                },
            ],
        ]);
    }

    public function mount()
    {
        if (! auth()->user()->is2FAApplicationEnabled()) {
            $google2fa = app('pragmarx.google2fa');

            $google2fa_secret = $google2fa->generateSecretKey();

            $qr_image = $google2fa->getQRCodeInline(
                config('app.name'),
                auth()->user()->email,
                $google2fa_secret
            );
            $this->qr_image = $qr_image;
            $this->secret = $google2fa_secret;
        }
    }

    public function save()
    {
        $this->rules();
        $data = $this->validate();
        /** @var User $user */
        $user = auth()->user();

        $data_to_update = [
            'google2fa_secret' => $data['secret'],
        ];

        if (empty($user->getRecoveryTokens())) {
            $data_to_update['recovery_codes'] = $user->generateRecoveryCodes()->toArray();
        } elseif ((isset($data_to_update['use_2fa']) && ! $data_to_update['use_2fa'])
            && (isset($data_to_update['send_2fa_to_phone']) && ! $data_to_update['send_2fa_to_phone'])) {
            $data_to_update['recovery_codes'] = [];
        }

        $user->update($data_to_update);

        $this->closeModal();
    }

    public function delete()
    {
        /** @var User $user */
        $user = auth()->user();

        $data_to_update = [
            'google2fa_secret' => null,
        ];

        if (! $user->is2FAEnabled()) {
            $data_to_update['recovery_codes'] = [];
        }

        $user->update($data_to_update);

        $this->closeModal();
    }
}
