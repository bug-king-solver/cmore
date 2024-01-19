<?php

namespace App\Models\Tenant\Compliance\DocumentAnalysis;

use App\Models\Tenant\Compliance\DocumentAnalysis\Result;
use App\Models\Tenant\MediaType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Domain extends Model
{
    use HasTranslations;
    use HasFactory;

    protected $table = 'document_analysis_domains';

    protected $fillable = [
        'enabled', 'document_analysis_type_id',
        'title', 'description', 'terms_base',
        'terms_prefixes', 'terms_suffixes', 'terms_both',
    ];

    /**
     * Translatable columns
     */
    public $translatable = [
        'title', 'description', 'terms_base', 'terms_prefixes', 'terms_suffixes', 'terms_both',
    ];

    public function type()
    {
        return $this->belongsTo(MediaType::class, 'document_analysis_type_id');
    }

    public function results()
    {
        return $this->hasMany(Result::class, 'document_analysis_domain_id');
    }

    public function snippets()
    {
        return $this->hasMany(Snippet::class, 'document_analysis_domain_id', 'id');
    }
}
