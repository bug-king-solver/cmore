<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Tenant\Company;
use App\Models\Tenant\BusinessSector;

class CompaniesBusinessSector extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'companies_business_sectors';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'company_id', 'business_sector_id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the business sector that owns the company.
     */
    public function business_sector()
    {
        return $this->belongsTo(BusinessSector::class);
    }
}
