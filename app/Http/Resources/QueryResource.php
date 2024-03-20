<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Query;


/**
 * @mixin Query
 */
class QueryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'product' => $this->product,
            'created_at' => $this->created_at->format('h:i:s A d-m-y'),
            'customer' => CustomerResource::make($this->whenLoaded('customer'))
        ];
    }
}
