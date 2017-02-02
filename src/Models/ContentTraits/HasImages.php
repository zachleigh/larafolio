<?php

namespace Larafolio\Models\ContentTraits;

use Larafolio\Models\Image;

trait HasImages
{
    /**
     * Get a model from a relationship by model name.
     *
     * @param string $relationship Name of relationship.
     * @param string $name         Name of model to get.
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    abstract protected function getFromRelationshipByName($relationship, $name);

    /**
     * A resource has many images.
     *
     * @return MorphMany
     */
    public function images()
    {
        return $this->morphMany(Image::class, 'resource');
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

    /**
     * Return images with all props needed for javascript.
     *
     * @return Collection
     */
    public function imagesWithProps()
    {
        return $this->images
            ->map(function (Image $image) {
                return $image->generateProps();
            })->reverse()->values();
    }
}
