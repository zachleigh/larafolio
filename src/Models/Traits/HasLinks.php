<?php

namespace Larafolio\Models\Traits;

use Larafolio\Models\Link;

trait HasLinks
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
