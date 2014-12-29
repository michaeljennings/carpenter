<?php

return array(

    'tables' => array(

        'location' => app_path() . '/tables.php',

    ),

    'database' => array(

        'driver' => 'eloquent',

    ),

    'paginator' => array(

        'driver' => 'illuminate',

        'view' => 'pagination::slider-3',

    ),

    'session' => array(

        'driver' => 'illuminate',

    ),

    'view' => array(

        'driver' => 'illuminate',

        'views' => array(

            'template' => 'michaeljennings/carpenter::blade.table',

        )

    )
);