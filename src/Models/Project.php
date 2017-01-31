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
     * Bootstrap model.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function (Project $project) {
            $project->setSlug('name');
        });

        static::updating(function (Project $project) {
            $project->setSlug('name');
        });
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
     * Order and group query, return results.
     *
     * @param Builder $query Query to be ordered.
     * @param bool    $group If true, group projects by 'type'.
     * @param bool    $order If true, order projects by 'order'.
     *
     * @return \Illuminate\Support\Collection
     */
    protected static function orderAndGroupQuery($query, $group, $order)
    {
        if ($order) {
            $query->orderBy('order');
        }

        $query->orderRelationship('links');

        $query->orderRelationship('blocks');

        if ($group) {
            return $query->get()
                ->each(function ($project, $key) {
                    $project->index = $key;
                })
                ->groupBy('type');
        }

        return $query->get();
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
        return static::join('text_blocks', 'projects.id', '=', 'text_blocks.resource_id')
            ->where('text_blocks.name', '=', $blockName)
            ->where('text_blocks.resource_type', '=', Project::class)
            ->select('projects.*')
            ->get();
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
        return static::join('images', 'projects.id', '=', 'images.resource_id')
            ->where('images.name', '=', $imageName)
            ->where('images.resource_type', '=', Project::class)
            ->select('projects.*')
            ->get();
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
        return static::hasRelationshipNamed('links', $linkName);
    }

    /**
     * Get all projects with relationship on table that has given name.
     *
     * @param string $table Name of table relationship is on.
     * @param string $name  Relationship name.
     *
     * @return \Illuminate\Support\Collection
     */
    protected static function hasRelationshipNamed($table, $name)
    {
        return static::join($table, 'projects.id', '=', "{$table}.project_id")
            ->where("{$table}.name", '=', $name)
            ->select('projects.*')
            ->get();
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
     * Get formatted text of block named description or first block.
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
     * Get formatted text of block named description or first block.
     *
     * @param bool $formatted If true, return formatted text.
     *
     * @return string
     */
    public function getProjectBlockText($formatted = true)
    {
        $project = $this->getProjectBlock();

        if ($project && $formatted) {
            return $project->formattedText();
        } elseif ($project) {
            return $project->text();
        }

        return $project;
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

    /**
     * Get blocks sorted by order.
     *
     * @param \Builder $query Query builder.
     * @param string   $slug  Project slug.
     *
     * @return \Builder
     */
    public function scopeWithBlocks($query, $slug)
    {
        return $query->orderRelationship('blocks')
            ->where('slug', $slug);
    }

    /**
     * Get full project info (blocks and links sorted by order).
     *
     * @param \Builder $query Query builder.
     * @param string   $slug  Project slug.
     *
     * @return \Builder
     */
    public function scopeFull($query, $slug)
    {
        return $query->orderRelationship('blocks')
            ->orderRelationship('links')
            ->where('slug', $slug);
    }

    /**
     * Order given relationship by order value.
     *
     * @param \Builder $query        Query builder.
     * @param string   $relationship Name of relationship to order.
     *
     * @return \Builder
     */
    public function scopeOrderRelationship($query, $relationship)
    {
        return $query->with([$relationship => function ($query) {
            $query->orderBy('order');
        }]);
    }

    /**
     * Return project properties to be passed to js.
     *
     * @return array
     */
    public function generateProps()
    {
        return [
            'deletedAt' => $this->deleted_at->diffForHumans(),
            'id'        => $this->id(),
            'name'      => $this->name(),
            'slug'      => $this->slug(),
        ];
    }
}
