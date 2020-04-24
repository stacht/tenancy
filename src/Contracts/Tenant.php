<?php

namespace Stacht\Tenancy\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface Tenant
{
    public function route($name, $parameters = [], $absolute = true);
    public function temporarySignedRoute($name, $expiration, $parameters = [], $absolute = true);
}
