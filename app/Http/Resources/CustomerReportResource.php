<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerReportResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'address' => $this->address,
            'phone' => $this->phone,
            'alert_phone' => $this->alert_phone,
            'created_at' => $this->created_at->format('h:i:s A d-m-Y'),
            'complaints' => [
                'total' => $this->complaints_count,
                'closed' => $this->closed_complaints_count,
                'pending' => $this->complaints_count - $this->closed_complaints_count,
            ],
            'queries' => [
                'total' => $this->queries_count,
                'closed' => $this->closed_queries_count,
                'pending' => $this->queries_count - $this->closed_queries_count,
            ]
        ];
    }
}
