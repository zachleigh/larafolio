<?php

namespace Larafolio\Models\ContentTraits;

use Larafolio\Models\TextLine;

trait HasLines
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
     * Define a polymorphic one-to-many relationship.
     *
     * @param string $related
     * @param string $name
     * @param string $type
     * @param string $id
     * @param string $localKey
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    abstract public function morphMany($related, $name, $type = null, $id = null, $localKey = null);

    /**
     * A resource has many text lines.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function lines()
    {
        return $this->morphMany(TextLine::class, 'resource');
    }

    /**
     * Return true if project has lines.
     *
     * @return bool
     */
    public function hasLines()
    {
        return !$this->lines->isEmpty();
    }

    /**
     * Get a text line by name, if exists.
     *
     * @param string $name Name of text line to get.
     *
     * @return \Larafolio\Models\TextBlock|null
     */
    public function line($name)
    {
        return $this->getFromRelationshipByName('lines', $name);
    }

    /**
     * Get line text by line name, if line exists.
     *
     * @param string $name      Name of text line to get.
     * @param bool   $formatted If true, return formmated text.
     *
     * @return string|null
     */
    public function lineText($name, $formatted = true)
    {
        if (!$line = $this->line($name)) {
            return;
        }

        if ($formatted) {
            return $line->formattedText();
        }

        return $line->text();
    }
}
