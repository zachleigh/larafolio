<?php

namespace Larafolio\Models;

use Larafolio\Helpers\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends HasContent
{
    use Sluggable, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'visible', 'order',
    ];

    /**
     * Return all visible pages.
     *
     * @param bool $order If true, order pages by 'order'.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function allVisible($order = true)
    {
        $query = static::where('visible', true);

        return static::orderAndGroupQuery($query, false, $order);
    }

    /**
     * Return all hidden pages.
     *
     * @param bool $order If true, order pages by 'order'.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function allHidden($order = true)
    {
        $query = static::where('visible', false);

        return static::orderAndGroupQuery($query, false, $order);
    }

    /**
     * Return all pages ordered by 'order'.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function allOrdered()
    {
        $query = static::query();

        return static::orderAndGroupQuery($query, false, true);
    }

    /**
     * Get all pages with given block name.
     *
     * @param string $blockName Name of block.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function hasBlockNamed($blockName)
    {
        return static::hasRelationshipNamed('pages', 'text_blocks', $blockName);
    }

    /**
     * Get all pages with given image name.
     *
     * @param string $imageName Name of image.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function hasImageNamed($imageName)
    {
        return static::hasRelationshipNamed('pages', 'images', $imageName);
    }

    /**
     * Get all pages with given link name.
     *
     * @param string $linkName Name of link.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function hasLinkNamed($linkName)
    {
        return static::hasRelationshipNamed('pages', 'links', $linkName);
    }
}
