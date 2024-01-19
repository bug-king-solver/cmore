<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Compliance\DocumentAnalysis\Domain;
use Illuminate\Database\Eloquent\Model;

class MediaType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
    ];

    public function domains()
    {
        return $this->hasMany(Domain::class, 'document_analysis_type_id', 'id');
    }
}
