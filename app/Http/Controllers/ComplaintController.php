<?php

namespace App\Http\Controllers;

use App\DTO\ComplaintDTO;
use App\DTO\CustomerDTO;
use App\Enums\ComplaintStatus;
use App\Enums\UserRole;
use App\Http\Requests\StoreComplaintRequest;
use App\Http\Resources\ComplaintIndexResource;
use App\Http\Resources\ComplaintResource;
use App\Models\Complaint;
use App\Models\Customer;
use App\Models\User;
use App\Services\InteraktService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ComplaintController extends Controller
{
    public function __construct(
        private InteraktService $service
    ) {
    }

    public function create(StoreComplaintRequest $request): JsonResponse
    {
        /** @var string $customerId */
        $customerId = $request->validated('customer_id');
        $customer = Customer::findByUuid($customerId);

        $complaint = $customer->complaints()->create([
            'comments' => $request->validated('comments'),
            'product' => $request->validated('product'),
            'status' => ComplaintStatus::PENDING,
        ]);

        if ($request->has('photo')) {
            /** @var UploadedFile $uploadedPhoto */
            $uploadedPhoto = $request->validated('photo');
            $complaint->addMedia($uploadedPhoto)->toMediaCollection();
        }

        $res_admin = $this->service->sendNewComplaintCreatedMessageToAdmin(
            CustomerDTO::fromModel($customer),
            ComplaintDTO::fromModel($complaint)
        );

        $res_customer = $this->service->sendNewComplaintCreatedMessageToCustomer(
            ComplaintDTO::fromModel($complaint),
            CustomerDTO::fromModel($customer)
        );

        return $this->response(
            data: [
                'complaint' => ComplaintResource::make($complaint->load('media')),
                'api_response' => [
                    'admin' => $res_admin->body(),
                    'customer' => $res_customer->body(),
                ],
            ],
            message: 'New Complaint Created'
        );
    }

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'employee_id' => ['sometimes', 'string', Rule::exists(User::class, 'uuid')->where('role', UserRole::EMPLOYEE)],
            'customer_id' => ['sometimes', 'string', Rule::exists(Customer::class, 'uuid')],
        ]);

        /** @var string $employeeId */
        $employeeId = $request->employee_id;

        /** @var string $customerId */
        $customerId = $request->customer_id;

        $complaints = Complaint::query()
            ->with('media')
            ->with([
                'statusChanges' => fn (HasMany $statusChanges) => $statusChanges->with('employee')->orderByDesc('created_at'),
            ])
            ->select(['id', 'uuid', 'comments', 'admin_comments', 'status', 'product', 'created_at', 'customer_id', 'employee_id'])
            ->when(
                $request->has('employee_id'),
                fn (Builder $query) => $query->where('employee_id', User::findIdByUuid($employeeId))
            )
            ->when(
                $request->has('customer_id'),
                fn (Builder $query) => $query->where('customer_id', Customer::findIdByUuid($customerId))
            )
            ->when($request->has('customer_id'), fn (Builder $query) => $query->with('employee:id,uuid,name'))
            ->when($request->has('employee_id'), fn (Builder $query) => $query->with('customer:id,uuid,name'))
            ->when(
                !$request->has('customer_id') and !$request->has('employee_id'),
                fn (Builder $query) => $query->with('customer:id,uuid,name')->with('employee:id,uuid,name')
            )
            ->latest()
            ->get();

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

        if ($complaint->status->isClosed()) {
            return $this->response(
                data: [],
                message: 'Complaint is Closed so cannot be allocated to an employee',
                status: false,
                code: 400
            );
        }

        if ($complaint->isPending()) {
            $complaintStatus = ComplaintStatus::ALLOCATED;
        } else {
            $complaintStatus = ComplaintStatus::REALLOCATED;
        }

        /** @var string $employeeId */
        $employeeId = $request->employee_id;

        $employeeId = User::findIdByUuid($employeeId);

        $complaint->update([
            'employee_id' => $employeeId,
            'admin_comments' => $request->comments,
            'status' => $complaintStatus,
        ]);

        $complaint->statusChanges()->create([
            'status' => $complaintStatus,
            'employee_id' => $employeeId,
            'created_at' => now(),
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
            'status' => ['required', Rule::in([ComplaintStatus::CLOSED->name, ComplaintStatus::REOPENED->name])],
        ]);

        /** @var string $status */
        $status = $request->status;

        $status = ComplaintStatus::createFromName($status);

        $complaint->update([
            'status' => $status,
        ]);

        /** @var User $user */
        $user = $request->user();

        $complaint->statusChanges()->create([
            'status' => $status,
            'employee_id' => $user->id,
            'created_at' => now(),
        ]);

        $complaint->load('customer');

        if ($status and $status->isClosed() and $complaint->customer) {
            $complaintDTO = ComplaintDTO::fromModel($complaint);
            $customerDTO = CustomerDTO::fromModel($complaint->customer);

            $this->service->sendComplaintClosedMesageToAdmin(
                $complaintDTO,
                $customerDTO
            );

            $this->service->sendComplaintClosedMesageToCustomer(
                $complaintDTO,
                $customerDTO
            );
        }

        return $this->response(
            data: [
                'complaint' => ComplaintIndexResource::make($complaint->refresh()),
            ],
            message: 'Complaint Completed'
        );
    }
}
