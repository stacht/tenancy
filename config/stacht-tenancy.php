<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tenant Model
    |--------------------------------------------------------------------------
    |
    | Default tenant model
    |
    */
    'model' => \Statch\Tenancy\Models\Tenant::class,

     /*
    |--------------------------------------------------------------------------
    | Tenant table
    |--------------------------------------------------------------------------
    |
    | Default tenant table
    |
    */
    'table' => 'tenants',

    /*
    |--------------------------------------------------------------------------
    | Tenant Column
    |--------------------------------------------------------------------------
    |
    | Every model that needs to be scoped by tenant (company, user, etc.)
    | should have one column that reference the `id` of a tenant in the tenant
    | table.
    |
    | For example, if you are scoping by company, you should have a
    | `companies` table that stores all your companies, and your other tables
    | should each have a `company_id` column that references an `id` on the
    | `companies` table.
    |
    */
    'default_tenant_column' => 'tenant_id',
];
