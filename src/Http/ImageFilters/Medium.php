<?php

namespace Larafolio\Http\ImageFilters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Medium implements FilterInterface
{
    /**
     * Apply medium filter to image.
     *
     * @param  Intervention\Image\Image  $image
     *
     * @return Intervention\Image\Image
     */
    public function applyFilter(Image $image)
    {
        return $image->resize(null, 300, function ($constraint) {
            $constraint->aspectRatio();
        });
    }
}
