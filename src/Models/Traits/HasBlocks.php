<?php

namespace Larafolio\Models\Traits;

use Larafolio\Models\TextBlock;

trait HasBlocks
{
    /**
     * A project has many text blocks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function blocks()
    {
        return $this->hasMany(TextBlock::class);
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
     * @return Larafolio\Models\TextBlock
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
