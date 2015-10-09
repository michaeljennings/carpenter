<?php

namespace Michaeljennings\Carpenter;

class Laravel4ServiceProvider extends CarpenterServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('michaeljennings/carpenter', 'carpenter');
    }
}