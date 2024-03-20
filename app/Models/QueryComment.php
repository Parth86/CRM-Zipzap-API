<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QueryComment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'by_customer' => 'boolean'
    ];

    public function customerQuery(): BelongsTo
    {
        return $this->belongsTo(Query::class);
    }
}
