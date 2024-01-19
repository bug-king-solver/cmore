<?php

namespace App\Models;

use App\Models\Invoicing\Document;
use App\Models\Tenant\Filters\Company\DateFilter;
use App\Models\Tenant\Filters\DateBetweenFilter;
use App\Models\Tenant\Filters\Wallet\TypeFilter;
use App\Models\Tenant\Product;
use Bavix\Wallet\Internal\Service\MathServiceInterface;
use Bavix\Wallet\Models\Transaction as BaseTransaction;
use Bavix\Wallet\Services\CastServiceInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;
use Lacodix\LaravelModelFilter\Traits\HasFilters;
use Lacodix\LaravelModelFilter\Traits\IsSearchable;
use Laravel\Nova\Fields\MorphedByMany;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

class Transaction extends BaseTransaction
{
    use CentralConnection;
    use IsSearchable;
    use HasFilters;

    protected array $searchable = [
        'uuid',
        'meta->description'
    ];

    protected  $casts = [
        'meta' => 'array',
    ];

    protected array $filters = [
        TypeFilter::class,
        DateFilter::class,
        DateBetweenFilter::class,
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
        });

        self::deleted(function ($model) {
        });
    }


    /**
     * Get a list of the companies to use with paginate
     *
     * @return Collection
     */
    public static function list()
    {
        return tenant()
            ->walletTransactions()
            ->with('wallet')
            ->orderBy('created_at', 'desc')
            ->getQuery();
    }

    public function getFillable(): array
    {
        return array_merge($this->fillable, [
            'product_id',
            'user_type',
            'user_id',
        ]);
    }

    /**
     * Get all the payments for the transaction
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get all the payments for the transaction
     */
    public function invoicing_documents()
    {
        return $this->hasMany(Document::class);
    }

    public function parent(): MorphTo
    {
        return $this->wallet
            ? $this->wallet->holder()
            : $this->payable();
    }

    /**
     * Check if is a event (each user can see only what he created)
     */
    public function reference(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => \Illuminate\Support\Str::limit($this->uuid, 13, $end = ''),
        );
    }

    /**
     * Check if is a event (each user can see only what he created)
     */
    public function productUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                $product = $this->product;

                if (!$product || !isset($product->button_goto_route)) {
                    return null;
                }

                return route($product->button_goto_route, [$product->button_goto_route_param_name => $this->meta['productable_id']]);
            },
        );
    }

    /**
     * Return the product associated to the transaction
     */
    public function product()
    {
        return $this->setConnection('tenant')->belongsTo(Product::class);
    }

    /*
    public function product()
    {
        $id = $this->meta['productable_id'] ?? null;
        $type = $this->meta['productable_type'] ?? null;

        if (! $type) {
            return null;
        }

        $productable = Product::where('productable_type', $type);

        if ($id) {
            $productable->where('productable_id', $id);
        }

        return $productable->first();
    }
    */

    /**
     * Return the product associated to the transaction - the total of X operations
     * @param null|string $payableType
     * @param null|string $payableId
     * @param string $type - Deposit || Withdraw
     * @param int $days
     * @param bool $includeNotConfirmed
     * @return float
     */
    public function total(?string $payableType, ?string $payableId, $type, int $days = 0, bool $includeNotConfirmed = false): float
    {
        $query = self::where('type', $type)->with('wallet');

        if ($days) {
            $query->where(
                'created_at',
                '>',
                now()->subDays($days)->endOfDay()
            );
        }

        if ($payableType && $payableId) {
            $query->where('payable_type', $payableType)
                ->where('payable_id', $payableId);
        }


        if (!$includeNotConfirmed) {
            $query->where('confirmed', '=', true);
        }

        $amount = 0;
        foreach ($query->get() as $transaction) {
            $amount += $transaction->getAmountFloatAttribute();
        }

        return $amount;
    }
}
