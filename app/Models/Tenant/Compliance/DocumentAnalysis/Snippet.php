<?php

namespace App\Models\Tenant\Compliance\DocumentAnalysis;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Snippet extends Model
{
    use HasFactory;

    protected $table = 'document_analysis_snippets';

    protected $fillable = [
        'document_analysis_result_id',
        'document_analysis_domain_id',
        'prefix',
        'term',
        'suffix',
        'page',
    ];

    public function result()
    {
        return $this->belongsTo(Result::class, 'document_analysis_results');
    }

    /**
     * Get snippet full text
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function text(): Attribute
    {
        return Attribute::make(
            get: fn (
                $value,
                $attributes
            ) => "... {$attributes['prefix']} <span class=\"px-1 rounded bg-esg30\">
            {$attributes['term']}</span> {$attributes['suffix']} ...",
        );
    }
}
