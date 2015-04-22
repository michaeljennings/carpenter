<?php

return array(

    'store' => array(

        /**
         * Set the store driver.
         *
         * Supported: eloquent.
         */
        'driver' => 'eloquent',

        'wrapper' => 'Michaeljennings\Carpenter\Wrappers\Eloquent',

    ),

    'paginator' => array(

        /**
         * Set the pagination driver.
         *
         * Supported: illuminate or native.
         */
        'driver' => 'illuminate',

    ),

    'session' => array(

        /**
         * Set the session driver.
         *
         * Supported: illuminate, codeigniter, native.
         */
        'driver' => 'illuminate',

        /**
         * Set the session key.
         */
        'key' => 'michaeljennings.carpenter'

    ),

    'view' => array(

        /**
         * Set the view driver.
         *
         * Supported: illuminate, codeigniter, native.
         */
        'driver' => 'illuminate',

        'views' => array(

            /**
             * Set the path to the table template.
             */
            'template' => 'michaeljennings/carpenter::default.table',

        )

    ),

    'tables' => array(

        /**
         * The tables files allows for a convenient place to store your
         * table closures. Set the location of your table file here.
         *
         * In v2 this has been depreciated in favour of service providers
         * and class based tables.
         */
        'location' => app_path() . '/tables.php',

    )
);