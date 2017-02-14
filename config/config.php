<?php

return array(

    'store' => array(

        /**
         * Set the store driver.
         *
         * Supported: eloquent, laravel4, illuminate, codeigniter, array.
         */
        'driver' => 'array',

    ),

    'paginator' => array(

        /**
         * Set the pagination driver.
         *
         * Supported: illuminate, laravel53, laravel4, native.
         */
        'driver' => 'native',

    ),

    'session' => array(

        /**
         * Set the session driver.
         *
         * Supported: illuminate, codeigniter, native.
         */
        'driver' => 'native',

        /**
         * Set the key to store session variables under.
         */
        'key' => 'michaeljennings.carpenter'

    ),

    'view' => array(

        /**
         * Set the view driver.
         *
         * Supported: illuminate, laravel4, codeigniter, native.
         */
        'driver' => 'native',

        'views' => array(

            /**
             * Set the path to the default table template.
             */
            'template' => __DIR__ . '/../views/bootstrap/table.php',

        )

    )
);