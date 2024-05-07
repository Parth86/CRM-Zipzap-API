<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddComplaintCommentsRequest;
use App\Models\Complaint;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ComplaintCommentController extends Controller
{
    public function create(AddComplaintCommentsRequest $request, Complaint $complaint): JsonResponse
    {

        $user = auth()->user();

        if ($complaint->isClosed()) {
            throw new Exception('Complaint is Closed');
        }

        if (!$user instanceof User) {
            throw new Exception('Auth Failed');
        }

        $comment = $complaint->comments()->create([
            'comments' => $request->validated('comments'),
            'created_by_id' => $user->id,
        ]);

        if ($request->has('photo')) {
            /** @var UploadedFile $uploadedPhoto */
            $uploadedPhoto = $request->validated('photo');
            $comment->addMedia($uploadedPhoto)->toMediaCollection();
        }

        return $this->response(
            data: [
                'complaint' => $complaint,
                'comment' => $comment,
            ],
            message: 'New Complaint Comments Added'
        );
    }
}
