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
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ComplaintController extends Controller
{
    public function create(StoreComplaintRequest $request)
    {
        $customer = Customer::findByUuid($request->customer_id);

        $complaint = $customer->complaints()->create([
            'comments' => $request->validated('comments'),
            'product' => $request->validated('product'),
            'status' => ComplaintStatus::PENDING
        ]);

        if ($request->has('photo')) {
            $complaint->addMedia($request->photo)->toMediaCollection();
        }

        return $this->response(
            data: [
                'complaint' => ComplaintResource::make($complaint->load('media'))
            ],
            message: "New Complaint Created"
        );
    }

    public function index(Request $request)
    {
        $complaints = Complaint::query()
            ->with('customer:id,uuid,name')
            ->with('employee:id,uuid,name')
            ->select(['id', 'uuid', 'comments', 'admin_comments', 'status', 'product', 'created_at', 'customer_id', 'employee_id'])
            ->when(
                $request->has('employee_id'),
                fn (Builder $query) => $query->whereBelongsTo(User::findByUuid($request->employee_id), 'employee')
            )
            ->latest()
            ->get();

        return $this->response(
            data: [
                'complaints' => ComplaintIndexResource::collection($complaints)
            ],
            message: "List of Complaints"
        );
    }

    public function allocateToEmployee(Request $request, Complaint $complaint)
    {
        $request->validate([
            'employee_id' => ['required', Rule::exists(User::class, 'uuid')->where('role', UserRole::EMPLOYEE)],
            'comments' => ['sometimes', 'nullable', 'string']
        ]);

        $employee = User::findByUuid($request->employee_id);

        $complaint->update([
            'employee_id' => $employee->id,
            'admin_comments' => $request->comments,
            'status' => ComplaintStatus::ALLOCATED
        ]);

        return $this->response(
            data: [
                'complaint' => ComplaintIndexResource::make($complaint->refresh())
            ],
            message: "New Complaint Created"
        );
    }
}
