<?php

namespace App\Models;

use App\Enums\ComplaintStatus;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @method string getUrl()
 */
class Complaint extends Model implements HasMedia
{
    use HasFactory, HasUuid, InteractsWithMedia;

    protected $guarded = ['id'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'status' => ComplaintStatus::class,
    ];

    /**
     * @return BelongsTo<Customer,Complaint>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return BelongsTo<User,Complaint>
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    /**
     * @return BelongsTo<User,Complaint>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /**
     * @return HasMany<ComplaintStatusChange>
     */
    public function statusChanges(): HasMany
    {
        return $this->hasMany(ComplaintStatusChange::class);
    }

    /**
     * @return HasOne<ComplaintStatusChange>
     */
    public function statusChangedClosed(): HasOne
    {
        return $this->hasOne(ComplaintStatusChange::class)->where('status', ComplaintStatus::CLOSED)->latestOfMany();
    }

    public function isAllocatedToEmployee(): bool
    {
        return is_null($this->employee_id);
    }

    public function isPending(): bool
    {
        return $this->status->isPending();
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder<Complaint>  $query
     * @return \Illuminate\Database\Eloquent\Builder<Complaint>
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->whereNot('status', ComplaintStatus::CLOSED);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder<Complaint>  $query
     * @return \Illuminate\Database\Eloquent\Builder<Complaint>
     */
    public function scopeClosed(Builder $query): Builder
    {
        return $query->where('status', ComplaintStatus::CLOSED);
    }
}
