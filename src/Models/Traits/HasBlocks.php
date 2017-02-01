<?php

namespace Larafolio\Models\Traits;

use Larafolio\Models\TextBlock;

trait HasBlocks
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
     * A resource has many text blocks.
     *
     * @return MorphMany
     */
    public function blocks()
    {
        return $this->morphMany(TextBlock::class, 'resource');
    }

    /**
     * Return true if project has blocks.
     *
     * @return bool
     */
    public function hasBlocks()
    {
        return !$this->blocks->isEmpty();
    }

    /**
     * Get a text block by name, if exists.
     *
     * @param string $name Name of text block to get.
     *
     * @return Larafolio\Models\TextBlock|null
     */
    public function block($name)
    {
        return $this->getFromRelationshipByName('blocks', $name);
    }

    /**
     * Get block text by block name, if block exists.
     *
     * @param string $name      Name of text block to get.
     * @param bool   $formatted If true, return formmated text.
     *
     * @return string|null
     */
    public function blockText($name, $formatted = true)
    {
        if (!$block = $this->block($name)) {
            return;
        }

        if ($formatted) {
            return $block->formattedText();
        }

        return $block->text();
    }
}
