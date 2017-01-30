<?php

namespace Larafolio\Models;

use Larafolio\Models\Traits\HasLinks;
use Larafolio\Models\Traits\HasBlocks;
use Larafolio\Models\Traits\HasImages;
use Illuminate\Database\Eloquent\Model;

class HasContent extends Model
{
    use HasBlocks, HasImages, HasLinks;

    /**
     * Get a model from a relationship by model name.
     *
     * @param string $relationship Name of relationship.
     * @param string $name         Name of model to get.
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected function getFromRelationshipByName($relationship, $name)
    {
        $items = $this->{$relationship}->where('name', $name);

        if ($items->isEmpty()) {
            return;
        }

        return $items->first();
    }
}
