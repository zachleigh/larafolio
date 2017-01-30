<?php

namespace Larafolio\Models;

use Larafolio\Models\Image;
use Larafolio\Models\Project;
use Larafolio\Helpers\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
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
        return static::hasRelationshipNamed('text_blocks', $blockName);
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
        return static::hasRelationshipNamed('images', $imageName);
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
     * A project has many text blocks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function blocks()
    {
        return $this->hasMany(TextBlock::class);
    }

    /**
     * A project has many images.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(Image::class);
    }

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
     * Return true if project has images.
     *
     * @return bool
     */
    public function hasImages()
    {
        return !$this->images->isEmpty();
    }

    /**
     * Get image by name, if exists.
     *
     * @param string $name Name of image to get.
     *
     * @return Larafolio\Models\Image|null
     */
    public function image($name)
    {
        return $this->getFromRelationshipByName('images', $name);
    }

    /**
     * Get image url for given size.
     *
     * @param string $name Name of image to get url for.
     * @param string $size Size of image.
     *
     * @return string|null
     */
    public function imageUrl($name, $size = 'medium')
    {
        if (!$image = $this->image($name)) {
            return;
        }

        return $image->{$size}();
    }

    /**
     * Get caption for image.
     *
     * @param string $name Name of image to get caption for.
     *
     * @return string|null
     */
    public function imageCaption($name)
    {
        if (!$image = $this->image($name)) {
            return;
        }

        return $image->caption();
    }

    /**
     * Get alt for image.
     *
     * @param string $name Name of image to get caption for.
     *
     * @return string|null
     */
    public function imageAlt($name)
    {
        if (!$image = $this->image($name)) {
            return;
        }

        return $image->alt();
    }

    /**
     * Get url of small image with project name or first image in collection.
     *
     * @return string
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
     * Return images with all props needed for javascript.
     *
     * @return Collection
     */
    public function imagesWithProps()
    {
        return $this->images
            ->map(function (Image $image) {
                return $image->generateProps();
            })->reverse()->values();
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
