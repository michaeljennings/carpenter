<?php

return array(

    'tables' => array(

        /**
         * The tables files allows for a convenient place to store your
         * table closures. Set the location of your table file here.
         *
         * In v2 this has been depreciated in favour of service providers
         * and class based tables.
         */
        'location' => app_path() . '/tables.php',

    ),

    'store' => array(

        /**
         * Set the store driver.
         *
         * Supported: eloquent.
         */
        'driver' => 'eloquent',

    ),

    'paginator' => array(

        /**
         * Set the pagination driver.
         *
         * Supported: illuminate.
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

    )
);