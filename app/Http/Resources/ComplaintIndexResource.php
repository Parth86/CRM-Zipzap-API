<?php

namespace App\Http\Resources;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Complaint
 */
class ComplaintIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $response = [
            'id' => $this->uuid,
            'product' => $this->product,
            'comments' => $this->comments,
            'created_at' => $this->created_at?->format('h:i:s A d-m-Y'),
            'status' => $this->status->label(),
            'photo' => $this->whenLoaded('media', $this->media->first()?->getUrl()),
            'customer' => CustomerResource::make($this->whenLoaded('customer')),
            'employee' => EmployeeResource::make($this->whenLoaded('employee')),
            'statusChanges' => ComplaintStatusChangeResource::collection($this->whenLoaded('statusChanges')),
        ];

        if (!request()->has('customer_id')) {
            $response['admin_comments'] = $this->admin_comments;
        }

        return $response;
    }
}
