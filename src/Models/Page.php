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
     * Properties to always eager load.
     *
     * @var array
     */
    protected $with = ['blocks', 'images', 'links'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'visible' => 'boolean',
    ];

    /**
     * Fields that are dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

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
        return static::hasRelationshipNamed('text_blocks', $blockName);
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
        return static::hasRelationshipNamed('images', $imageName);
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
        return static::hasRelationshipNamed('links', $linkName);
    }

    /**
     * Get all pages with relationship on table that has given name.
     *
     * @param string $table Name of table relationship is on.
     * @param string $name  Relationship name.
     *
     * @return \Illuminate\Support\Collection
     */
    protected static function hasRelationshipNamed($table, $name)
    {
        return static::join($table, 'pages.id', '=', "{$table}.resource_id")
            ->where("{$table}.name", '=', $name)
            ->where("{$table}.resource_type", '=', self::class)
            ->select('pages.*')
            ->get();
    }

    /**
     * Return the page id.
     *
     * @return int
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Return the page name.
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Return the page slug.
     *
     * @return string
     */
    public function slug()
    {
        return $this->slug;
    }

    /**
     * Return the page order value.
     *
     * @return int
     */
    public function order()
    {
        return $this->order;
    }
}
