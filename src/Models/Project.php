<?php

namespace Larafolio\Models;

use Larafolio\Helpers\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends HasContent
{
    use Sluggable, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'projects';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'type', 'visible', 'order',
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
     * Return all visible projects.
     *
     * @param bool $group If true, group projects by 'type'.
     * @param bool $order If true, order projects by 'order'.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function allVisible($group = true, $order = true)
    {
        $query = static::where('visible', true);

        return static::orderAndGroupQuery($query, $group, $order);
    }

    /**
     * Return all hidden projects.
     *
     * @param bool $group If true, group projects by 'type'.
     * @param bool $order If true, order projects by 'order'.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function allHidden($group = true, $order = true)
    {
        $query = static::where('visible', false);

        return static::orderAndGroupQuery($query, $group, $order);
    }

    /**
     * Return all projects grouped by 'type'.
     *
     * @param bool $order If true, order projects by 'order'.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function allGrouped($order = true)
    {
        $query = static::query();

        return static::orderAndGroupQuery($query, true, $order);
    }

    /**
     * Return all projects ordered by 'order'.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function allOrdered()
    {
        $query = static::query();

        return static::orderAndGroupQuery($query, false, true);
    }

    /**
     * Get all projects with given block name.
     *
     * @param string $blockName Name of block.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function hasBlockNamed($blockName)
    {
        return static::hasRelationshipNamed(Project::class, 'projects', 'text_blocks', $blockName);
    }

    /**
     * Get all projects with given image name.
     *
     * @param string $imageName Name of image.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function hasImageNamed($imageName)
    {
        return static::hasRelationshipNamed(Project::class, 'projects', 'images', $imageName);
    }

    /**
     * Get all projects with given link name.
     *
     * @param string $linkName Name of link.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function hasLinkNamed($linkName)
    {
        return static::hasRelationshipNamed(Project::class, 'projects', 'links', $linkName);
    }

    /**
     * Return the project id.
     *
     * @return int
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Return the project name.
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Return the project type.
     *
     * @return string
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * Return the project slug.
     *
     * @return string
     */
    public function slug()
    {
        return $this->slug;
    }

    /**
     * Return the project order value.
     *
     * @return int
     */
    public function order()
    {
        return $this->order;
    }

    /**
     * Get formatted text of block with project name or first block.
     *
     * @return Larafolio\Models\TextBlock
     */
    public function getProjectBlock()
    {
        $block = $this->block($this->name());

        if ($block) {
            return $block;
        } elseif ($this->hasBlocks()) {
            return $this->blocks()->first();
        }
    }

    /**
     * Get formatted text of block with project name or first block.
     *
     * @param bool $formatted If true, return formatted text.
     *
     * @return string
     */
    public function getProjectBlockText($formatted = true)
    {
        $block = $this->getProjectBlock();

        if ($block && $formatted) {
            return $block->formattedText();
        } elseif ($block) {
            return $block->text();
        }

        return $block;
    }

    /**
     * Get url of small image with project name or first image in collection.
     *
     * @return Larafolio\Models\Image
     */
    public function getProjectImage()
    {
        $projectImage = $this->image($this->name());

        if ($projectImage) {
            return $projectImage;
        } elseif ($this->hasImages()) {
            return $this->images()->first();
        }
    }

    /**
     * Get url of small image with project name or first image in collection.
     *
     * @param string $size The size of the image, name of image cache filter.
     *
     * @return string
     */
    public function getProjectImageUrl($size = 'small')
    {
        $projectImage = $this->getProjectImage();

        if ($projectImage) {
            return $projectImage->{$size}();
        }

        return $projectImage;
    }
}
