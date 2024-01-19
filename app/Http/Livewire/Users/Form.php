<?php

namespace App\Http\Livewire\Users;

use App\Events\EnabledUser;
use App\Events\Registered;
use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Http\Livewire\Traits\HasCustomColumns;
use App\Http\Livewire\Traits\HasProductItem;
use App\Models\Enums\Users\Type;
use App\Models\Tenant\Role;
use App\Models\User;
use App\Notifications\NewUserByNonOwnerNotification;
use App\Rules\PasswordStrength;
use DateTime;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Form extends Component
{
    use AuthorizesRequests;
    use BreadcrumbsTrait;
    use HasCustomColumns;
    use WithPagination;
    use WithFileUploads;
    use HasProductItem;

    protected $feature = 'users';

    public User | int $user;

    public $rolesList;

    public $permissionsList;

    public $type;

    public $enabled;

    public $name;

    public $username;

    public $email;

    public $password;

    public $password_expires_at;

    public $locale;

    public $photo;

    public $photoPreview;

    public $roles;

    public $permissions;

    protected $photoValidation = ['nullable', 'image', 'max:512'];

    public $taggableList;

    public $password_force_change;

    public $taggableIds = [];

    protected bool $isEmailForLogin = true;

    public $userTypeList = [];

    public $passwordStrengthMessage;


    protected function rules()
    {
        // "! $this->user" means we are creating a new one, otherwise we are updating one
        $isEmailForLogin = tenant()->isEmailTheAuthenticationUsername;

        $email = !$this->user->exists
            ? 'unique:users'
            : 'unique:users,email,' . $this->user->id;

        $rules = [
            'enabled' => ['nullable', 'boolean'],
            'type' => ['nullable', Rule::in(Type::keys())],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', $email],
            'password' => ['nullable', 'string', new PasswordStrength()],
            'password_force_change' => ['nullable'],
            'locale' => ['required', Rule::in(config('app.locales'))],
            'photo' => $this->photoValidation,
            'roles' => ['nullable', 'exists:roles,id'],
            'permissions' => ['nullable', 'exists:permissions,id'],
            'type' => ['nullable'],
        ];

        if (!$isEmailForLogin) {
            $username = !$this->user->exists
                ? 'unique:users'
                : 'unique:users,username,' . $this->user->id;
            $rules['username'] = ['required', 'string', 'max:255', $username];
        }

        return $this->mergeCustomRules($rules);
    }

    public function updatedPhoto()
    {
        $this->validate([
            'photo' => $this->photoValidation,
        ]);
    }

    public function mount(?User $user)
    {
        $this->user = $user;
        $this->resource = $user;

        $this->authorize(!$this->user->exists ? 'users.create' : "users.update.{$this->user->id}");

        $this->rolesList = getRolesForSelect();
        $this->permissionsList = getPermissionsForSelect();

        $this->taggableList = getTagsForSelect();
        $this->userTypeList = userTypeList();

        $this->addBreadcrumb(__('Users'), route('tenant.users.index'));

        $passwordStrengthInstance = new PasswordStrength();
        $this->passwordStrengthMessage = $passwordStrengthInstance->message();

        if ($this->user->exists) {
            $this->addBreadcrumb($this->user->name);

            $this->enabled = $this->user->enabled;
            $this->type = $this->user->type;
            $this->name = $this->user->name;
            $this->username = $this->user->username;
            $this->email = $this->user->email;
            $this->locale = $this->user->locale;
            $this->photoPreview = $this->user->photo;
            $this->roles = $this->user->roles->pluck('id')->toArray();
            $this->permissions = $this->user->permissions->pluck('id')->toArray();
            $this->password_force_change = $this->user->password_force_change
                ? true
                : false;
            $this->type = $this->user->type;
            $this->customColumnsData = $this->user->only($this->customColumnsIds);
            $this->taggableIds = $this->user->tags
                ? $this->user->tags->pluck('id', null)->toArray()
                : [];
        }
    }

    public function render(): View
    {
        $this->isEmailForLogin = tenant()->isEmailTheAuthenticationUsername;
        return view('livewire.tenant.users.form');
    }

    // TODO :: Refactor
    public function save()
    {
        $this->authorize(!$this->user->exists ? 'users.create' : "users.update.{$this->user->id}");

        $data = $this->validate();

        $data['enabled'] = $data['enabled'] ?? false;
        $data['type'] = $data['type'] ?? tenant()->users_type_default;

        // Encrypt password on user creation and prevent to save an empty password on user updating
        if ($data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data = $this->addCustomData($data);
        $data['password_force_change'] = $data['password_force_change']
            ? true
            : false;
        $data['password_expires_at'] = $this->user->setPasswordExpiration();
        $data['type'] = $data['type'] ?? tenant()->usersTypeDefault;

        // Upload photo
        if ($this->photo) {
            // Remove previous photo
            if ($this->user->exists && $this->user->photo) {
                Storage::delete($this->user->photo);
            }

            $photoPath = $this->photo->store('users', 'public');
            $data['photo'] = $photoPath;
        } else {
            unset($data['photo']);
        }

        if (!$this->user->exists) {
            $data['created_by_user_id'] = auth()->user()->id;
            $data['email_verified_at'] = now();

            event(new Registered(
                $this->user = User::create($data),
                !tenant()->see_only_own_data || auth()->user()->isOwner()
                    ? [
                        'roles' => $data['roles'],
                        'permissions' => $data['permissions'],
                    ]
                    : []
            ));

            $authUser = auth()->user();
            if (!$authUser->isOwner()) {
                $owners = User::getOwners();
                Notification::send($owners, new NewUserByNonOwnerNotification(auth()->user(), $this->user));
            }
        } else {
            // Just notify if the user was enabled
            $notify = $data['enabled'] && !$this->user->enabled;

            $this->user->update($data);

            // It's required at least one super admin
            if ($this->user->hasRole('Super Admin') && Role::find(1)->users->count() === 1) {
                $data['roles'][] = 1;
            }

            $this->user->syncRoles($data['roles']);
            $this->user->syncPermissions($data['permissions']);

            if ($notify) {
                event(new EnabledUser($this->user));
            }
        }

        $this->user->assignTags($this->taggableIds, auth()->user());

        $this->emit('assignChanged');

        return redirect(route('tenant.users.index'));
    }
}
