<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Query extends Model
{
    use HasFactory, HasUuid;

    protected $guarded = ['id'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(QueryComment::class);
    }

    public function customerComments(): HasMany
    {
        return $this->hasMany(QueryComment::class)->where('by_customer', true);
    }

    public function adminComments(): HasMany
    {
        return $this->hasMany(QueryComment::class)->where('by_customer', false);
    }
}
