<?php

namespace App\Http\Resources;

use App\Models\ComplaintStatusChange;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'time' => $this->created_at->format('h:i:s A d-m-y'),
            'employee' => UserResource::make($this->whenLoaded('employee')),
        ];
    }
}
