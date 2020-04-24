<?php

namespace Statch\Tenancy;

use Illuminate\Support\Traits\Macroable;
use Illuminate\Validation\Rule;
use Statch\Tenancy\Contracts\Tenant as TenantContract;

class TenantManager
{
    use Macroable;

    private $tenant;

    public function setTenant(TenantContract $tenant)
    {
        $this->tenant = $tenant;

        return $this;
    }

    public function getTenant()
    {
        return $this->tenant;
    }

    public function loadTenant(string $identifier): bool
    {
        $tenant = app(config('statch-tenancy.model'))->query()->where('slug', '=', $identifier)->first();

        if ($tenant) {
            $this->setTenant($tenant);

            return true;
        }

        return false;
    }

    public static function unique($table, $column = 'NULL')
    {
        return (new Rules\Unique($table, $column))->where(config('statch-tenancy.default_tenant_column'), $this->getTenant()->id);
    }

    public static function exists($table, $column = 'NULL')
    {
        return (new Rules\Exists($table, $column))->where(config('statch-tenancy.default_tenant_column'), $this->getTenant()->id);
    }
}
