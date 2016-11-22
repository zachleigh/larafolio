<?php

namespace Larafolio\Http\ImageFilters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Full implements FilterInterface
{
    /**
     * Apply no size filter to image.
     *
     * @param  Intervention\Image\Image  $image
     *
     * @return Intervention\Image\Image
     */
    public function applyFilter(Image $image)
    {
        return $image;
    }
}
