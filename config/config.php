<?php

return array(

    'store' => array(

        /**
         * Set the store driver.
         *
         * Supported: eloquent, array.
         */
        'driver' => 'eloquent',

        /**
         * Set a class to wrap each row from the data store.
         *
         * Supported: Michaeljennings\Carpenter\Wrappers\Eloquent
         *            Michaeljennings\Carpenter\Wrappers\ArrayWrapper
         */
        'wrapper' => 'Michaeljennings\Carpenter\Wrappers\Eloquent',

    ),

    'paginator' => array(

        /**
         * Set the pagination driver.
         *
         * Supported: illuminate, native.
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
            'template' => 'michaeljennings/carpenter::bootstrap.table',

        )

    )
);