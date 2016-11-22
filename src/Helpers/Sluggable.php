<?php

namespace Larafolio\Helpers;

trait Sluggable
{
    /**
     * Set a snake_cased slug on model from model property.
     *
     * @param string $property Object property to make slug from.
     */
    protected function setSlug($property)
    {
        $slug = str_replace(' ', '_', $this->{$property});

        $this->slug = strtolower($slug);
    }
}
