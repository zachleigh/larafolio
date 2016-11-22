<?php

namespace Larafolio\Http\ImageFilters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Small implements FilterInterface
{
    /**
     * Apply small filter to image.
     *
     * @param  Intervention\Image\Image  $image
     *
     * @return Intervention\Image\Image
     */
    public function applyFilter(Image $image)
    {
        return $image->resize(null, 150, function ($constraint) {
            $constraint->aspectRatio();
        });
    }
}
