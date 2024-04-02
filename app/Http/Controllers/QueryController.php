<?php

namespace App\Http\Controllers;

use App\DTO\CustomerDTO;
use App\DTO\QueryDTO;
use App\Enums\QueryStatus;
use App\Enums\UserRole;
use App\Http\Requests\AddQueryCommentsRequest;
use App\Http\Requests\StoreQueryRequest;
use App\Http\Resources\QueryCommentResource;
use App\Http\Resources\QueryResource;
use App\Models\Customer;
use App\Models\Query;
use App\Models\QueryComment;
use App\Models\User;
use App\Services\InteraktService;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class QueryController extends Controller
{
    public function __construct(
        private InteraktService $service
    ) {
    }

    public function create(
        StoreQueryRequest $request,
        ?Query $query = null,
        ?Customer $customer = null,
    ): JsonResponse {

        $customer = $request->user();

        if (!$customer instanceof Customer) {
            throw new Exception('Auth Failed');
        }

        DB::transaction(function () use ($request, &$query, $customer) {

            $query = $customer->queries()->create([
                'product' => $request->validated('product'),
            ]);

            $comment = $query->comments()->create([
                'comments' => $request->validated('comments'),
                'by_customer' => true,
            ]);

            if ($request->has('photo')) {
                /** @var UploadedFile $uploadedPhoto */
                $uploadedPhoto = $request->validated('photo');
                $comment->addMedia($uploadedPhoto)->toMediaCollection();
            }
        });

        if (!$query) {
            return $this->response(
                data: [],
                message: 'New Query Failed',
                status: false,
                code: 400
            );
        }

        $res_admin = $this->service->sendNewqueryCreatedMessageToAdmin(
            CustomerDTO::fromModel($customer),
            QueryDTO::fromModel($query)
        );

        $res_customer = $this->service->sendNewQueryCreatedMessageToCustomer(
            QueryDTO::fromModel($query),
            CustomerDTO::fromModel($customer)
        );

        return $this->response(
            data: [
                'query' => $query->load('comments'),
                'api_response' => [
                    'admin' => $res_admin->body(),
                    'customer' => $res_customer->body(),
                ],
            ],
            message: 'New Query Created'
        );
    }

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'customer_id' => ['sometimes', Rule::exists(Customer::class, 'uuid')],
        ]);

        /** @var string $customerId */
        $customerId = $request->customer_id;

        $queries = Query::query()
            ->select(['id', 'uuid', 'product', 'created_at', 'customer_id', 'status'])
            ->when(
                $request->has('customer_id'),
                fn (Builder $query) => $query->where('customer_id', Customer::findIdByUuid($customerId))
            )
            ->when(
                !$request->has('customer_id'),
                fn (Builder $query) => $query->with('customer:id,uuid,name')
            )
            ->latest()
            ->get();

        return $this->response(
            data: [
                'queries' => QueryResource::collection($queries),
            ],
            message: 'List of Queries'
        );
    }

    public function addComments(AddQueryCommentsRequest $request, Query $query, ?QueryComment $comment = null): JsonResponse
    {

        $user = auth()->user();

        if ($query->isClosed()) {
            throw new Exception('Query is Closed');
        }

        if ($user instanceof User and $user->role == UserRole::ADMIN) {
            $comment = $query->comments()->create([
                'comments' => $request->validated('comments'),
                'by_customer' => false,
            ]);
        } elseif ($user instanceof Customer) {
            $comment = $query->comments()->create([
                'comments' => $request->validated('comments'),
                'by_customer' => true,
            ]);
        } else {
            throw new Exception('Auth Failed');
        }

        if ($request->has('photo')) {
            /** @var UploadedFile $uploadedPhoto */
            $uploadedPhoto = $request->validated('photo');
            $comment->addMedia($uploadedPhoto)->toMediaCollection();
        }

        return $this->response(
            data: [
                'query' => $query,
                'comment' => $comment,
            ],
            message: 'New Query Comments Added'
        );
    }

    public function view(Query $query): JsonResponse
    {
        $comments = $query->comments()->with('media')->latest()->get();

        return $this->response(
            data: [
                'query' => QueryResource::make($query),
                'comments' => QueryCommentResource::collection($comments),
            ],
            message: 'Query Comments'
        );
    }

    public function completeQuery(Query $query): JsonResponse
    {
        $query->update([
            'status' => QueryStatus::CLOSED,
        ]);

        $query->comments()->create([
            'comments' => 'CLOSED By Admin',
            'by_customer' => false,
        ]);

        $query->load('customer');

        if ($query->customer) {
            $queryDTO = QueryDTO::fromModel($query);
            $customerDTO = CustomerDTO::fromModel($query->customer);

            $this->service->sendQueryClosedMesageToAdmin(
                $queryDTO,
                $customerDTO
            );

            $this->service->sendQueryClosedMesageToCustomer(
                $queryDTO,
                $customerDTO
            );
        }

        return $this->response(
            data: [
                'query' => QueryResource::make($query->refresh()),
            ],
            message: 'Query Comments'
        );
    }
}
