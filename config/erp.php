<?php

return [

    'connection' => env('ERP_DB_CONNECTION', 'supabase'),

    'customers_table' => env('ERP_CUSTOMERS_TABLE', 'erp_customers'),

    'customer_columns' => [
        'code' => env('ERP_CUSTOMER_CODE_COLUMN', 'cus_code'),
        'name' => env('ERP_CUSTOMER_NAME_COLUMN', 'cus_name'),
        'phone' => env('ERP_CUSTOMER_PHONE_COLUMN', 'tel'),
        'email' => env('ERP_CUSTOMER_EMAIL_COLUMN', 'email'),
        'address' => env('ERP_CUSTOMER_ADDRESS_COLUMN', 'address'),
        'town' => env('ERP_CUSTOMER_TOWN_COLUMN', 'town'),
        'state' => env('ERP_CUSTOMER_STATE_COLUMN', 'state'),
        'modified_at' => env('ERP_CUSTOMER_MODIFIED_COLUMN', 'date_time_modified'),
        'status_bad' => env('ERP_CUSTOMER_STATUS_BAD_COLUMN', 'status_bad_yn'),
    ],

    'skip_bad_customers' => env('ERP_SKIP_BAD_CUSTOMERS', true),

    'sync_schedule' => env('ERP_CUSTOMER_SYNC_SCHEDULE', '0 * * * *'),

];
