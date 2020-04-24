<?php

namespace Statch\Tenancy\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Statch\Tenancy\Exceptions\ModelNotFoundForTenantException;
use Statch\Tenancy\Scopes\TenantOwnedScope;
use Statch\Tenancy\TenantManager;

trait OwnedByTenant
{
    public static function bootOwnedByTenant()
    {
        static::addGlobalScope(new TenantOwnedScope());

        static::creating(function (Model $model) {
            if (!$model->{config('statch-tenancy.default_tenant_column')} && !$model->relationLoaded('tenant')) {
                $model->setRelation('tenant', app(TenantManager::class)->getTenant());
            }

            return $model;
        });
    }

    public function tenant(): BelongsTo
    {
        $this->belongsTo(Tenant::class, config('statch-tenancy.default_tenant_column'));
    }

    /**
     * Override the default findOrFail method so that we can re-throw
     * a more useful exception. Otherwise it can be very confusing
     * why queries don't work because of tenant scoping issues.
     *
     * @param mixed $id
     * @param array $columns
     *
     * @throws ModelNotFoundForTenantException
     *
     * @return \Illuminate\Database\Eloquent\Collection|Model
     */
    public static function findOrFail($id, $columns = ['*'])
    {
        try {
            return static::query()->findOrFail($id, $columns);
        } catch (ModelNotFoundException $e) {
            // If it DOES exist, just not for this tenant, throw a nicer exception
            if (null !== static::newQueryWithoutTenants()->find($id, $columns)) {
                throw (new ModelNotFoundForTenantException())->setModel(\get_called_class());
            }
            throw $e;
        }
    }
}
