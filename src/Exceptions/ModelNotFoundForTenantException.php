<?php

namespace Statch\Tenancy\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class ModelNotFoundForTenantException extends ModelNotFoundException
{
    /**
     * @param string    $model
     * @param array|int $ids
     *
     * @return $this
     */
    public function setModel($model, $ids = [])
    {
        $this->model = $model;
        $this->message = "No query results for model [{$model}] when scoped by tenant.";

        return $this;
    }
}
