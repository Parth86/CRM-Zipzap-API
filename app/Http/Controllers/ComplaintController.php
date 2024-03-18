<?php

namespace App\Http\Controllers;

use App\Enums\ComplaintStatus;
use App\Enums\UserRole;
use App\Http\Requests\StoreComplaintRequest;
use App\Http\Resources\ComplaintIndexResource;
use App\Http\Resources\ComplaintResource;
use App\Models\Complaint;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ComplaintController extends Controller
{
    public function create(StoreComplaintRequest $request): JsonResponse
    {
        $customer = Customer::findByUuid($request->customer_id);

        $complaint = $customer->complaints()->create([
            'comments' => $request->validated('comments'),
            'product' => $request->validated('product'),
            'status' => ComplaintStatus::PENDING,
        ]);

        if ($request->has('photo')) {
            $complaint->addMedia($request->photo)->toMediaCollection();
        }

        return $this->response(
            data: [
                'complaint' => ComplaintResource::make($complaint->load('media')),
            ],
            message: 'New Complaint Created'
        );
    }

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'employee_id' => ['sometimes', Rule::exists(User::class, 'uuid')->where('role', UserRole::EMPLOYEE)],
            'customer_id' => ['sometimes', Rule::exists(Customer::class, 'uuid')]
        ]);
        $complaints = Complaint::query()
            ->with('media')
            ->select(['id', 'uuid', 'comments', 'admin_comments', 'status', 'product', 'created_at', 'customer_id', 'employee_id'])
            ->when(
                $request->has('employee_id'),
                fn (Builder $query) => $query->where('employee_id', User::findIdByUuid($request->employee_id))
            )
            ->when(
                $request->has('customer_id'),
                fn (Builder $query) => $query->where('customer_id', Customer::findIdByUuid($request->customer_id))
            )
            ->when($request->has('customer_id'), fn (Builder $query) => $query->with('employee:id,uuid,name'))
            ->when($request->has('employee_id'), fn (Builder $query) => $query->with('customer:id,uuid,name'))
            ->when(
                !$request->has('customer_id') and !$request->has('employee_id'),
                fn (Builder $query) => $query->with('customer:id,uuid,name')->with('customer:id,uuid,name')
            )
            ->latest()
            ->get();

        // dd($complaints->first());

        return $this->response(
            data: [
                'complaints' => ComplaintIndexResource::collection($complaints),
            ],
            message: 'List of Complaints'
        );
    }

    public function allocateToEmployee(Request $request, Complaint $complaint): JsonResponse
    {
        $request->validate([
            'employee_id' => ['required', Rule::exists(User::class, 'uuid')->where('role', UserRole::EMPLOYEE)],
            'comments' => ['sometimes', 'nullable', 'string'],
        ]);

        $complaint->update([
            'employee_id' => User::findIdByUuid($request->employee_id),
            'admin_comments' => $request->comments,
            'status' => ComplaintStatus::ALLOCATED,
        ]);

        $complaint->statusChanges()->create([
            'status' => ComplaintStatus::ALLOCATED,
            'created_at' => now()
        ]);

        return $this->response(
            data: [
                'complaint' => ComplaintIndexResource::make($complaint->refresh()),
            ],
            message: 'New Complaint Created'
        );
    }

    public function completeComplaint(Complaint $complaint, Request $request): JsonResponse
    {
        $request->validate([
            'status' => ['required', Rule::in([ComplaintStatus::CLOSED->name, ComplaintStatus::REOPENED->name])]
        ]);

        $status = ComplaintStatus::createFromName($request->status);

        $complaint->update([
            'status' => $status,
        ]);

        $complaint->statusChanges()->create([
            'status' => $status,
            'created_at' => now()
        ]);

        return $this->response(
            data: [
                'complaint' => ComplaintIndexResource::make($complaint->refresh()),
            ],
            message: 'Complaint Completed'
        );
    }
}
