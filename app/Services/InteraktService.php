<?php

namespace App\Services;

use App\Models\Complaint;
use App\Models\Customer;
use App\Models\Query;
use Http;

class InteraktService
{
    private string $url = "https://api.interakt.ai/v1/public/";

    private string $apiToken;

    private string $websiteUrl = "https://crm.zipzap.in/";

    private string $adminPhone = "7986771957";

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->apiToken = config('services.interakt.api_key');
    }

    private function sendMessage(string $endpoint, string $phone, string $templateName, array $bodyValues, array $buttonValues)
    {
        $data =  [
            "fullPhoneNumber" =>  "91" . $phone,
            "callbackData" => "some text here",
            "type" =>  "Template",
            "template" =>  [
                "name" =>  $templateName,
                "languageCode" => "en",
                "bodyValues" => $bodyValues,
                "buttonValues" => (object) $buttonValues
            ]

        ];

        $res =  Http::withHeaders([
            'Authorization' => "Basic " . $this->apiToken,
            "Content-Type" => "application/json"
        ])
            ->post($endpoint, $data);

        return $res;
    }

    public function sendNewComplaintCreatedMessageToAdmin(Customer $customer, Complaint $complaint)
    {
        $endpoint = $this->url . "message/";
        $templateName = "crm_new_complaint_created_admin_3m";
        $buttonUrl = $this->websiteUrl . "complaintsView";

        return $this->sendMessage(
            phone: $this->adminPhone,
            endpoint: $endpoint,
            templateName: $templateName,
            bodyValues: [
                $complaint->product,
                $customer->name,
                $customer->phone
            ],
            buttonValues: [
                "1" =>  [
                    $buttonUrl
                ]
            ]
        );
    }

    public function sendNewComplaintCreatedMessageToCustomer(Complaint $complaint, Customer $customer)
    {
        $endpoint = $this->url . "message/";
        $templateName = "crm_new_complaint_created_customer_ih";
        $buttonUrl = $this->websiteUrl . "customerView";

        return $this->sendMessage(
            phone: $customer->phone,
            endpoint: $endpoint,
            templateName: $templateName,
            bodyValues: [
                $complaint->product,
            ],
            buttonValues: [
                "0" =>  [
                    $buttonUrl
                ]
            ]
        );
    }

    public function sendNewqueryCreatedMessageToAdmin(Customer $customer, Query $query)
    {
        $endpoint = $this->url . "message/";
        $templateName = "crm_new_query_created_admin";
        $buttonUrl = $this->websiteUrl . "queryView";

        return $this->sendMessage(
            phone: $this->adminPhone,
            endpoint: $endpoint,
            templateName: $templateName,
            bodyValues: [
                $query->product,
                $customer->name,
                $customer->phone
            ],
            buttonValues: [
                "1" =>  [
                    $buttonUrl
                ]
            ]
        );
    }
    public function sendNewQueryCreatedMessageToCustomer(Query $query, Customer $customer)
    {
        $endpoint = $this->url . "message/";
        $templateName = "crm_new_query_created_customer";
        $buttonUrl = $this->websiteUrl . "queryView";

        return $this->sendMessage(
            phone: $customer->phone,
            endpoint: $endpoint,
            templateName: $templateName,
            bodyValues: [
                $query->product,
            ],
            buttonValues: [
                "0" =>  [
                    $buttonUrl
                ]
            ]
        );
    }
}
