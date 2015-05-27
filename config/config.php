<?php

return array(

    'store' => array(

        /**
         * Set the store driver.
         *
         * Supported: eloquent, laravel4, array.
         */
        'driver' => 'array',

        /**
         * Set a class to wrap each row from the data store.
         *
         * Supported: Michaeljennings\Carpenter\Wrappers\Eloquent
         *            Michaeljennings\Carpenter\Wrappers\ArrayWrapper
         */
        'wrapper' => 'Michaeljennings\Carpenter\Wrappers\ArrayWrapper',

    ),

    'paginator' => array(

        /**
         * Set the pagination driver.
         *
         * Supported: illuminate, laravel4, native.
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
        'driver' => 'native',

        'views' => array(

            /**
             * Set the path to the table template.
             */
            'template' => 'michaeljennings/carpenter::bootstrap.table',

        )

    )
);