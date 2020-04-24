<?php

namespace Stacht\Tenancy\Models;

use Illuminate\Database\Eloquent\Model;
use Stacht\Tenancy\Contracts\Tenant as TenantContract;

class Tenant extends Model implements TenantContract
{
    protected $fillable = [
        'id',
        'name',
        'slug',
    ];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('stacht-tenancy.table'));
    }

    public function route($name, $parameters = [], $absolute = true)
    {
         return app('url')->route($name, array_merge([$this->slug], $parameters), $absolute);
    }

    public function temporarySignedRoute($name, $expiration, $parameters = [], $absolute = true)
    {
         return app('url')->temporarySignedRoute($name, $expiration, array_merge([$this->slug], $parameters), $absolute);
    }
}
