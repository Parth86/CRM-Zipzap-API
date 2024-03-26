<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Query;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $totalComplaints = Complaint::query()->count();
        $pendingComplaints = Complaint::query()->pending()->count();
        $closedComplaints = $totalComplaints - $pendingComplaints;
        $complaintsPerDay = Complaint::query()
            ->selectRaw('count(*) as count, date(created_at) as date')
            ->groupByRaw('date(created_at)')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date');

        $totalQueries = Query::query()->count();
        $pendingQueries = Query::query()->pending()->count();
        $closedQueries = $totalQueries - $pendingQueries;
        $queriesPerDay = Query::query()
            ->selectRaw('count(*) as count, date(created_at) as date')
            ->groupByRaw('date(created_at)')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date');

        return $this->response(
            data: [
                'complaints' => [
                    'total' => $totalComplaints,
                    'pending' => $pendingComplaints,
                    'closed' => $closedComplaints,
                    'perDay' => $complaintsPerDay,
                ],
                'queries' => [
                    'total' => $totalQueries,
                    'pending' => $pendingQueries,
                    'closed' => $closedQueries,
                    'perDay' => $queriesPerDay,
                ],
            ],
            message: 'Dashboard'
        );
    }
}
