<?php namespace Michaeljennings\Carpenter\Store\Laravel4; 

use Michaeljennings\Carpenter\Contracts\Store;
use Michaeljennings\Carpenter\Store\EloquentStore as Laravel5Store;

class EloquentStore extends Laravel5Store implements Store {

    /**
     * Return the total results.
     *
     * @return integer
     */
    public function count()
    {
        return $this->model->paginate(1, $this->select)->getTotal();
    }

}