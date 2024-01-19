<?php

namespace App\Models\Traits;

use App\Models\Enums\Users\System as SystemUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Schema;

trait QueryBuilderScopes
{
    /**
     * Search for information from the created_by_user_id column
     * Se existe a coluna , entra no if e faz a busca pelo id do usuario logado.
     * senao existe a coluna, retorna o query.
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public static function scopeOnlyOwnData($query)
    {
        $tenant = tenant();

        $table = $query->getModel()->getTable();
        $arrayColums = Schema::getColumnListing($table);

        /** check if $query has attribute created_by_user_id */
        if (in_array('created_by_user_id', $arrayColums)) {
            $user = auth()->user();
            if (! $user->isOwner() && $tenant->see_only_own_data) {
                if (self::class === User::class) {
                    $query->where('created_by_user_id', $user->id);
                } else {
                    $query->whereHas('users', function (Builder $query) use ($user) {
                        $query->where('user_id', $user->id);
                    });
                }
            }
        }

        return $query;
    }

    /**
     * Scope a query to only include users that are not internal users.
     * And if the user is not the owner of the target, the owner will not be included in the list.
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $createdByUserId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function scopeAvailableUsers($query, $createdByUserId = null)
    {
        if ($user = auth()->user()) {
            // First validate if the user is not in the users list (Organization » Users)
            // But if he is there, check if he is not super admin (in this case he must see all users)
            if ((! $query->getModel() instanceof User) && $createdByUserId) {
                $query->where('created_by_user_id', $createdByUserId);
            }

            // $query->where(
            //     'id',
            //     '!=',
            //     $createdByUserId && $user->id != $createdByUserId && !$user->isOwner()
            //         ? $createdByUserId
            //         : $user->id
            // );
        }

        return $query->OnlyAppUsers();
    }

    /**
     * Scope a query to only include users that are not internal users.
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyAppUsers($query)
    {
        return $query->where('system', SystemUser::NO->value);
    }

    /**
     * Scope a query to only include users that are not internal users.
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlySystemUsers($query)
    {
        return $query->where('system', SystemUser::YES->value);
    }
}
