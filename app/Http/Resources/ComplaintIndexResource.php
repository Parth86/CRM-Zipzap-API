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
            'employee' => EmployeeIndexResource::make($this->whenLoaded('employee')),
            'statusChanges' => ComplaintStatusChangeResource::collection($this->whenLoaded('statusChanges')),
            'age' => (int) $this->created_at->diffInDays(now())
        ];

        if ($this->relationLoaded('customer')) {
            $response['customer'] = $this->customer->only('name', 'phone');
        }

        if ($this->relationLoaded('user') and $this->user) {
            $response['created_by'] = $this->user->name . " ({$this->user->role->label()})";
        } else {
            $response['created_by'] = $this->customer?->name . ' (Customer)';
        }

        if (!request()->has('customer_id')) {
            $response['admin_comments'] = $this->admin_comments;
        }

        $response['closed_at'] = null;
        $response['closing_duration'] = null;

        if ($this->status->isClosed() and $this->statusChangedClosed) {
            $response['closed_at'] = $this->statusChangedClosed->created_at->format('h:i:s A d-m-Y');
            $response['closing_duration'] = [
                'words' => $this->statusChangedClosed->created_at->diffForHumans($this->created_at),
                'seconds' => $this->created_at->diffInSeconds($this->statusChangedClosed->created_at),
                'days' => $this->created_at->diffInDays($this->statusChangedClosed->created_at),
            ];
        }

        if ($this->relationLoaded('media')) {
            $response['photo'] = $this->media->first()?->getUrl();
        }

        return $response;
    }
}
