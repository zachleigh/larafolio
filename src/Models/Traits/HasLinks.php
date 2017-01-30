<?php

namespace Larafolio\Models\Traits;

trait HasLinks
{
    /**
     * A project has many links.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function links()
    {
        return $this->hasMany(Link::class);
    }
    
    /**
     * Return true if project has links.
     *
     * @return bool
     */
    public function hasLinks()
    {
        return !$this->links->isEmpty();
    }

    /**
     * Get link by name, if exists.
     *
     * @param string $name Name of link to get.
     *
     * @return Larafolio\Models\Link|null
     */
    public function link($name)
    {
        return $this->getFromRelationshipByName('links', $name);
    }

    /**
     * Get link url.
     *
     * @param string $name Name of link.
     *
     * @return string|null
     */
    public function linkUrl($name)
    {
        if (!$link = $this->link($name)) {
            return;
        }

        return $link->url();
    }

    /**
     * Get link text.
     *
     * @param string $name Name of link.
     *
     * @return string|null
     */
    public function linkText($name)
    {
        if (!$link = $this->link($name)) {
            return;
        }

        return $link->text();
    }
}
