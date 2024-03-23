<?php

namespace App\Http\Resources;

use App\Models\QueryComment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin QueryComment
 */
class QueryCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'by_customer' => boolval($this->by_customer),
            'comments' => $this->comments,
            'created_at' => $this->created_at?->format('h:i:s A d-m-Y'),
            'photo' => $this->whenLoaded('media', $this->media->first()?->getUrl()),
        ];
    }
}
