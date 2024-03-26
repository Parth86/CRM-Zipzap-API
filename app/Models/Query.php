<?php

namespace App\Models;

use App\Enums\QueryStatus;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Query extends Model
{
    use HasFactory, HasUuid;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => QueryStatus::class,
    ];

    public function isClosed(): bool
    {
        return $this->status->isClosed();
    }
    /**
     *
     * @param  \Illuminate\Database\Eloquent\Builder<Query>  $query
     * @return \Illuminate\Database\Eloquent\Builder<Query>
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', QueryStatus::OPEN);
    }
    /**
     *
     * @param  \Illuminate\Database\Eloquent\Builder<Query>  $query
     * @return \Illuminate\Database\Eloquent\Builder<Query>
     */
    public function scopeClosed(Builder $query): Builder
    {
        return $query->where('status', QueryStatus::CLOSED);
    }

    /**
     * @return BelongsTo<Customer,Query>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return HasMany<QueryComment>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(QueryComment::class);
    }

    /**
     * @return HasMany<QueryComment>
     */
    public function customerComments(): HasMany
    {
        return $this->hasMany(QueryComment::class)->where('by_customer', true);
    }

    /**
     * @return HasMany<QueryComment>
     */
    public function adminComments(): HasMany
    {
        return $this->hasMany(QueryComment::class)->where('by_customer', false);
    }
}
