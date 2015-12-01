<?php

namespace Michaeljennings\Carpenter\Store\Laravel4;

use Michaeljennings\Carpenter\Contracts\Store;
use Michaeljennings\Carpenter\Exceptions\ModelNotSetException;
use Michaeljennings\Carpenter\Store\Eloquent as Laravel5Store;

class Eloquent extends Laravel5Store implements Store
{
    /**
     * Return the total results of the query.
     *
     * @return integer
     * @throws ModelNotSetException
     */
    public function count()
    {
        if ( ! $this->model) {
            throw new ModelNotSetException('You must set a model to be used by the eloquent driver.');
        }

        $model = clone $this->model;

        return $model->paginate(1, $this->select)->getTotal();
    }
}