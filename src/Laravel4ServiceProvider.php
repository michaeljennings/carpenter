<?php

namespace Michaeljennings\Carpenter;

use Illuminate\Support\ServiceProvider;

class Laravel4ServiceProvider extends CarpenterServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('vendor/package', 'carpenter');
    }
}