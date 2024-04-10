<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class EmployeeResource extends JsonResource
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
            'phone' => $this->phone,
            'complaints' => [
                'current' => [
                    'total' => $this->complaints_count,
                    'closed' => $this->closed_complaints_count
                ],
                'overall' => [
                    'total' => $this->overall_complaints_count,
                    'closed' => $this->overall_closed_complaints_count
                ]
            ]
        ];
    }
}
