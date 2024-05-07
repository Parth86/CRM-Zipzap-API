<?php

namespace App\Http\Controllers;

use App\DTO\CustomerDTO;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerIndexResource;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Services\InteraktService;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    public function __construct(private InteraktService $service)
    {
    }

    public function create(StoreCustomerRequest $request): JsonResponse
    {
        $customer = Customer::query()->create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'phone' => $request->validated('phone'),
            'alert_phone' => $request->validated('alert_phone'),
            'address' => $request->validated('address'),
            'created_by_id' => auth()->id()
        ]);

        $res = $this->service->sendNewAccountCreatedMessageToCustomer(
            CustomerDTO::fromModel($customer)
        );

        return $this->response(
            data: [
                'customer' => CustomerResource::make($customer),
                'res' => $res->body(),
            ],
            message: 'New Customer Created',
            status: true,
            code: 200
        );
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): JsonResponse
    {
        $customer->update([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'phone' => $request->validated('phone'),
            'alert_phone' => $request->validated('alert_phone'),
            'address' => $request->validated('address'),
        ]);

        // $res = $this->service->sendNewAccountCreatedMessageToCustomer(
        //     CustomerDTO::fromModel($customer)
        // );

        return $this->response(
            data: [
                'customer' => CustomerResource::make($customer),
            ],
            message: 'Customer Updated',
            status: true,
            code: 200
        );
    }

    public function index(): JsonResponse
    {
        $customers = Customer::query()
            ->select('uuid', 'name')
            ->orderBy('name')
            ->get();

        return $this->response(
            data: [
                'customers' => CustomerIndexResource::collection($customers),
            ],
            message: 'List of Customers'
        );
    }
}
