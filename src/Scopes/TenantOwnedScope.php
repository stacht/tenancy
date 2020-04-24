<?php

namespace Stacht\Tenancy\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Stacht\Tenancy\TenantManager;

class TenantOwnedScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $manager = app(TenantManager::class);
        $tenant = $manager->getTenant();

        if ($tenant && $tenant->id) {
            $builder->where(config('stacht-tenancy.default_tenant_column'), '=', $manager->getTenant()->id);
        }
    }

    public function extend(Builder $builder)
    {
        $this->addWithoutTenancy($builder);
    }

    protected function addWithoutTenancy(Builder $builder)
    {
        $builder->macro('withoutTenancy', function (Builder $builder) {
            return $builder->withoutGlobalScope($this);
        });
    }
}
