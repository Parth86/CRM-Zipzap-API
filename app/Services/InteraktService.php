<?php

namespace App\Services;

use App\DTO\ComplaintDTO;
use App\DTO\CustomerDTO;
use App\DTO\EmployeeDTO;
use App\DTO\QueryDTO;
use App\DTO\UserDTO;
use Http;
use Illuminate\Http\Client\Response;

class InteraktService
{
    private string $url = 'https://api.interakt.ai/v1/public/';

    private string $apiToken;

    private string $websiteUrl;

    private string $adminPhone;

    public function __construct()
    {
        /** @var string $token */
        $token = config('services.interakt.api_key');
        $this->apiToken = $token;

        /** @var string $adminPhone */
        $adminPhone = config('services.interakt.admin_phone');
        $this->adminPhone = $adminPhone;

        $websiteUrl = config('app.frontend_url') . "/";
        $this->websiteUrl = $websiteUrl;
    }

    /**
     * @param  array<int, string|null>  $bodyValues
     * @param  array<int, array<int, string>>  $buttonValues
     */
    private function sendMessage(string $phone, string $templateName, array $bodyValues, array $buttonValues): Response
    {
        $endpoint = $this->url . 'message/';
        $data = [
            'fullPhoneNumber' => '91' . $phone,
            'callbackData' => 'some text here',
            'type' => 'Template',
            'template' => [
                'name' => $templateName,
                'languageCode' => 'en',
                'bodyValues' => $bodyValues,
                'buttonValues' => (object) $buttonValues,
            ],

        ];

        $res = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->apiToken,
            'Content-Type' => 'application/json',
        ])
            ->post($endpoint, $data);

        return $res;
    }

    public function sendNewComplaintCreatedMessageToAdmin(CustomerDTO $customer, ComplaintDTO $complaint): Response
    {
        $templateName = 'crm_new_complaint_created_admin_3m';
        $buttonUrl = $this->websiteUrl . 'complaintsView';

        return $this->sendMessage(
            phone: $this->adminPhone,
            templateName: $templateName,
            bodyValues: [
                $complaint->product,
                $customer->name,
                $customer->phone,
            ],
            buttonValues: [
                '1' => [
                    $buttonUrl,
                ],
            ]
        );
    }

    public function sendNewComplaintCreatedMessageToCustomer(ComplaintDTO $complaint, CustomerDTO $customer): Response
    {
        $templateName = 'crm_new_complaint_created_customer_ih';
        $buttonUrl = $this->websiteUrl . 'customerView';

        return $this->sendMessage(
            phone: $customer->alert_phone,
            templateName: $templateName,
            bodyValues: [
                $complaint->product,
            ],
            buttonValues: [
                '0' => [
                    $buttonUrl,
                ],
            ]
        );
    }

    public function sendComplaintClosedMesageToAdmin(ComplaintDTO $complaint, CustomerDTO $customer): Response
    {
        $templateName = 'crm_complaint_closed_admin';
        $buttonUrl = $this->websiteUrl . 'customerView';

        return $this->sendMessage(
            phone: $this->adminPhone,
            templateName: $templateName,
            bodyValues: [
                $complaint->product,
                $customer->name,
            ],
            buttonValues: [
                '1' => [
                    $buttonUrl,
                ],
            ]
        );
    }

    public function sendComplaintClosedMesageToCustomer(ComplaintDTO $complaint, CustomerDTO $customer): Response
    {

        $templateName = 'crm_complaint_closed_customer';
        $buttonUrl = $this->websiteUrl . 'complaintsView';

        return $this->sendMessage(
            phone: $customer->alert_phone,
            templateName: $templateName,
            bodyValues: [
                $complaint->product,
            ],
            buttonValues: [
                '1' => [
                    $buttonUrl,
                ],
            ]
        );
    }

    public function sendQueryClosedMesageToAdmin(QueryDTO $query, CustomerDTO $customer): Response
    {
        $templateName = 'crm_query_closed_admin';
        $buttonUrl = $this->websiteUrl . 'queryView';

        return $this->sendMessage(
            phone: $this->adminPhone,
            templateName: $templateName,
            bodyValues: [
                $query->product,
                $customer->name,
            ],
            buttonValues: [
                '1' => [
                    $buttonUrl,
                ],
            ]
        );
    }

    public function sendQueryClosedMesageToCustomer(QueryDTO $query, CustomerDTO $customer): Response
    {

        $templateName = 'crm_query_closed_customer';
        $buttonUrl = $this->websiteUrl . 'complaintsView';

        return $this->sendMessage(
            phone: $customer->alert_phone,
            templateName: $templateName,
            bodyValues: [
                $query->product,
            ],
            buttonValues: [
                '1' => [
                    $buttonUrl,
                ],
            ]
        );
    }

    public function sendNewqueryCreatedMessageToAdmin(CustomerDTO $customer, QueryDTO $query): Response
    {
        $templateName = 'crm_new_query_created_admin';
        $buttonUrl = $this->websiteUrl . 'queryView';

        return $this->sendMessage(
            phone: $this->adminPhone,
            templateName: $templateName,
            bodyValues: [
                $query->product,
                $customer->name,
                $customer->phone,
            ],
            buttonValues: [
                '1' => [
                    $buttonUrl,
                ],
            ]
        );
    }

    public function sendNewQueryCreatedMessageToCustomer(QueryDTO $query, CustomerDTO $customer): Response
    {
        $templateName = 'crm_new_query_created_customer';
        $buttonUrl = $this->websiteUrl . 'queryView';

        return $this->sendMessage(
            phone: $customer->alert_phone,
            templateName: $templateName,
            bodyValues: [
                $query->product,
            ],
            buttonValues: [
                '0' => [
                    $buttonUrl,
                ],
            ]
        );
    }

    public function sendNewAccountCreatedMessageToCustomer(CustomerDTO $customer): Response
    {
        $templateName = 'crm_account_created_customer';

        return $this->sendMessage(
            phone: $customer->alert_phone,
            templateName: $templateName,
            bodyValues: [
                $customer->phone,
                $customer->password,
            ],
            buttonValues: []
        );
    }

    public function sendNewAccountCreatedMessageToEmployee(EmployeeDTO $employee): Response
    {
        $templateName = 'crm_account_created_employee';

        return $this->sendMessage(
            phone: $employee->phone,
            templateName: $templateName,
            bodyValues: [
                $employee->phone,
                $employee->password,
            ],
            buttonValues: []
        );
    }

    public function sendOTPMessage(UserDTO $user): Response
    {
        $templateName = 'crm_otp';

        return $this->sendMessage(
            phone: $user->alert_phone,
            templateName: $templateName,
            bodyValues: [
                $user->otp,
            ],
            buttonValues: []
        );
    }

    public function sendComplaintAllocatedMessageToEmployee(
        CustomerDTO $customer,
        ComplaintDTO $complaint,
        EmployeeDTO $employee
    ) {
        $templateName = "crm_complaint_allocated_to_employee";

        $buttonUrl = $this->websiteUrl;

        return $this->sendMessage(
            phone: $employee->phone,
            templateName: $templateName,
            bodyValues: [
                $customer->name,
                $complaint->product,
            ],
            buttonValues: [
                '0' => [
                    $buttonUrl,
                ],
            ]
        );
    }
}
