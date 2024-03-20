<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\ComplaintStatusChange;

/**
 * @mixin ComplaintStatusChange
 */
class ComplaintStatusChangeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => $this->status->name,
            'time' => $this->created_at->format('h:i:s A d-m-y')
        ];
    }
}
