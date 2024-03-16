<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Complaint;

/**
 * @mixin Complaint
 */
class ComplaintIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'product' => $this->product,
            'comments' => $this->comments,
            'admin_comments' => $this->admin_comments,
            'created_at' => $this->created_at->format('h:i:s A d-m-Y'),
            'status' => $this->status->label(),
            'photo' => $this->whenLoaded('media', $this->media->first()?->original_url),
            'customer' => $this->whenLoaded('customer', [
                'id' => $this->customer->uuid,
                'name' => $this->customer->name
            ]),
            'employee' => $this->whenLoaded(
                'employee',
                $this->employee ?
                    [
                        'id' => $this->employee->uuid,
                        'name' => $this->employee->name
                    ] : null
            )
        ];
    }
}
