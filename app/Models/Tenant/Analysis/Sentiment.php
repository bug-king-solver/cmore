<?php

namespace App\Models\Tenant\Analysis;

use App\Models\Traits\QueryBuilderScopes;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Sentiment extends Model
{
    use LogsActivity;
    use QueryBuilderScopes;

    protected $casts = [
        'founded_at' => 'date',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'created_by_user_id',
        'parent_id',
        'business_sector_id',
        'name',
        'type',
        'country',
        'vat_country',
        'vat_number',
        'founded_at',
        'logo',
        'color',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Get the business sector that owns the company.
     */
    public function businessSector()
    {
        return $this->belongsTo(BusinessSector::class);
    }

    /**
     * Get the questionnaires for the company.
     */
    public function questionnaires()
    {
        return $this->hasMany(Questionnaire::class);
    }

    /**
     * Get the data for the company.
     */
    public function data()
    {
        return $this->hasMany(Data::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Interact with the company's type.
     */
    protected function type(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => explode(',', $value),
            set: fn ($value) => is_array($value) ? implode(',', $value) : $value,
        );
    }

    /**
     * Check if a company is a customer
     */
    public function isCustomer(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => in_array('customer', $this->type, true),
        );
    }

    /**
     * Check if a company is not a customer
     */
    public function isNotCustomer(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => ! $this->is_client,
        );
    }

    /**
     * Check if a company is a supplier
     */
    public function isSupplier(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => in_array('supplier', $this->type, true),
        );
    }

    /**
     * Check if a company is not a supplier
     */
    public function isNotSupplier(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => ! $this->is_supplier,
        );
    }

    /**
     * Get a list of the companies to use with paginate
     *
     * @return Collection
     */
    public static function list($user = null)
    {
        return self::OnlyOwnData()
            ->orderBy('name')
            ->with('businessSector');
    }
}
