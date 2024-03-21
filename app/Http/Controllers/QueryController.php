<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Enums\UserRole;
use App\Http\Requests\AddQueryCommentsRequest;
use App\Http\Requests\StoreQueryRequest;
use App\Http\Resources\QueryCommentResource;
use App\Http\Resources\QueryResource;
use App\Models\Customer;
use App\Models\Query;
use App\Models\QueryComment;
use Auth;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class QueryController extends Controller
{
    public function create(StoreQueryRequest $request, Query $query = null): JsonResponse
    {

        DB::transaction(function () use ($request, &$query) {

            $customer = $request->user();

            $query = $customer->queries()->create([
                'product' => $request->validated('product'),
            ]);

            $comment = $query->comments()->create([
                'comments' => $request->validated('comments'),
                'by_customer' => true
            ]);

            if ($request->has('photo')) {
                $comment->addMedia($request->photo)->toMediaCollection();
            }
        });


        return $this->response(
            data: [
                'query' => $query->load('comments'),
            ],
            message: 'New Query Created'
        );
    }

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'customer_id' => ['sometimes', Rule::exists(Customer::class, 'uuid')]
        ]);
        $queries = Query::query()
            ->select(['id', 'uuid', 'product', 'created_at', 'customer_id'])
            ->when(
                $request->has('customer_id'),
                fn (Builder $query) => $query->where('customer_id', Customer::findIdByUuid($request->customer_id))
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

    public function addComments(AddQueryCommentsRequest $request, Query $query, QueryComment $comment = null): JsonResponse
    {

        $user = auth()->user();

        if ($user->role and $user->role == UserRole::ADMIN) {
            $comment = $query->comments()->create([
                'comments' => $request->validated('comments'),
                'by_customer' => false
            ]);
        } else if (!$user->role) {
            $comment = $query->comments()->create([
                'comments' => $request->validated('comments'),
                'by_customer' => true
            ]);
        } else {
            throw new Exception("Auth Failed");
        }

        if ($request->has('photo')) {
            $comment->addMedia($request->photo)->toMediaCollection();
        }

        return $this->response(
            data: [
                'query' => $query,
                'comment' => $comment
            ],
            message: 'New Query Comments Added'
        );
    }

    public function view(Query $query): JsonResponse
    {
        $comments = $query->comments()->with('media')->get();

        return $this->response(
            data: [
                'query' => QueryResource::make($query),
                'comments' => QueryCommentResource::collection($comments),
            ],
            message: 'Query Comments'
        );
    }
}
