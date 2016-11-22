<?php

namespace App\Cleaners;

use LaravelLaundromat\Cleaner;

class ProjectImages extends Cleaner
{
    /**
     * Properties allowed on the clean object.
     *
     * @var array
     */
    protected $allowed = [
        'images'
    ];

    /**
     * Methods to run. Returned value will be stored as a snake case property
     * on the clean object.
     *
     * @var array
     */
    protected $methods = [
        'images.thumbnail',
        'images.small',
        'images.full'
    ];
}
