<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @method string getUrl()
 */
class QueryComment extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = ['id'];

    protected $casts = [
        'by_customer' => 'boolean',
    ];

    /**
     * Define a relationship to the customer's query.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Query,QueryComment>
     */
    public function customerQuery(): BelongsTo
    {
        return $this->belongsTo(Query::class);
    }
}
