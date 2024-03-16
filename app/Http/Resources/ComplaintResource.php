<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Complaint;

/**
 * @mixin Complaint
 */
class ComplaintResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'comments' => $this->comments,
            'product' => $this->product,
            'id' => $this->uuid,
            'photo' => $this->whenLoaded('media', $this->media->first()?->original_url)
        ];
    }
}
