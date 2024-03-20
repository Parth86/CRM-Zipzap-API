<?php

namespace App\Models;

use App\Enums\ComplaintStatus;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

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

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function statusChanges(): HasMany
    {
        return $this->hasMany(ComplaintStatusChange::class);
    }

    public function isAllocatedToEmployee(): bool
    {
        return is_null($this->employee_id);
    }

    public function isPending(): bool
    {
        return $this->status->isPending();
    }
}
