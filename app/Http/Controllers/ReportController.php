<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerReportResource;
use App\Http\Resources\EmployeeResource;
use App\Models\Customer;
use App\Models\User;

class ReportController extends Controller
{
    public function customers()
    {
        $customers =  Customer::query()
            ->withCount([
                'complaints',
                'closedComplaints',
                'queries',
                'closedQueries'
            ])
            ->get();

        return $this->response(
            data: [
                'customers' => CustomerReportResource::collection($customers)
            ],
            message: "Customers Report"
        );
    }

    public function employees()
    {
        $employees =  User::query()
            ->isemployee()
            ->withCount([
                'complaints',
                'closedComplaints',
                'overallComplaints',
                'overallClosedComplaints'
            ])
            ->get();

        return $this->response(
            data: [
                'employees' => EmployeeResource::collection($employees)
                // 'employees' => ($employees)
            ],
            message: "Employees Report"
        );
    }
}
