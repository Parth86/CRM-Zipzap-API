<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Resources\CustomerIndexResource;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function create(StoreCustomerRequest $request)
    {
        $customer = Customer::query()->create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'phone' => $request->validated('phone'),
            'alert_phone' => $request->validated('alert_phone'),
            'address' => $request->validated('address')
        ]);

        return $this->response(
            data: [
                "customer" => CustomerResource::make($customer)
            ],
            message: "New Customer Created",
            status: true,
            code: 200
        );
    }

    public function index()
    {
        $customers = Customer::query()->select('uuid', 'name')->get();

        return $this->response(
            data: [
                'customers' => CustomerIndexResource::collection($customers)
            ],
            message: "List of Customers"
        );
    }
}
