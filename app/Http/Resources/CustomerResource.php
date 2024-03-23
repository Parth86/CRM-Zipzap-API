<?php

namespace App\Http\Resources;

use App\Enums\Role;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Customer
 */
class CustomerResource extends JsonResource
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
            'password' => $this->when(boolval($this->original_password), $this->original_password),
            'address' => $this->address,
            'phone' => $this->phone,
            'alert_phone' => $this->alert_phone,
            'role' => Role::CUSTOMER,
        ];
    }
}
