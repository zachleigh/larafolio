<?php

namespace Larafolio\Models\Traits;

trait HasImages
{
    /**
     * A project has many images.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(Image::class);
    }

    /**
     * Return true if project has images.
     *
     * @return bool
     */
    public function hasImages()
    {
        return !$this->images->isEmpty();
    }

    /**
     * Get image by name, if exists.
     *
     * @param string $name Name of image to get.
     *
     * @return Larafolio\Models\Image|null
     */
    public function image($name)
    {
        return $this->getFromRelationshipByName('images', $name);
    }

    /**
     * Get image url for given size.
     *
     * @param string $name Name of image to get url for.
     * @param string $size Size of image.
     *
     * @return string|null
     */
    public function imageUrl($name, $size = 'medium')
    {
        if (!$image = $this->image($name)) {
            return;
        }

        return $image->{$size}();
    }

    /**
     * Get caption for image.
     *
     * @param string $name Name of image to get caption for.
     *
     * @return string|null
     */
    public function imageCaption($name)
    {
        if (!$image = $this->image($name)) {
            return;
        }

        return $image->caption();
    }

    /**
     * Get alt for image.
     *
     * @param string $name Name of image to get caption for.
     *
     * @return string|null
     */
    public function imageAlt($name)
    {
        if (!$image = $this->image($name)) {
            return;
        }

        return $image->alt();
    }
}
