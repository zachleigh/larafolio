<?php

namespace Larafolio\Models;

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
    protected $with = ['images'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'visible' => 'boolean',
    ];

    /**
     * Bootstrap model.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            $project->setSlug('name');
        });

        static::updating(function ($project) {
            $project->setSlug('name');
        });
    }

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
     * A project has many text blocks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function blocks()
    {
        return $this->hasMany(TextBlock::class);
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
     * A project has many images.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(Image::class);
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
     * Get a text block by name, if exists.
     *
     * @param string $name Name of text block to get.
     *
     * @return string
     */
    public function block($name)
    {
        $block = $this->blocks->where('name', $name);

        if (!$block->isEmpty()) {
            return $block->first()->formattedText();
        }
    }

    /**
     * Get url of small image with project name or first image in collection.
     *
     * @return string
     */
    public function getProjectImage()
    {
        $projectImage = $this->images()
            ->where('name', $this->name())
            ->get();

        if (!$projectImage->isEmpty()) {
            return $projectImage->first()->small();
        } elseif ($this->hasImages()) {
            return $this->images()->first()->small();
        }
    }

    /**
     * Get formatted text of block named description or first block.
     *
     * @return string
     */
    public function getProjectBlock()
    {
        $description = $this->block('description');

        if ($description) {
            return $description;
        } elseif ($this->hasBlocks()) {
            return $this->blocks()->first()->formattedText();
        }
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
     * Return true if project has blocks.
     *
     * @return bool
     */
    public function hasBlocks()
    {
        return !$this->blocks->isEmpty();
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
        return $query->with(['blocks' => function ($query) {
            $query->orderBy('order');
        }])->where('slug', $slug)
            ->first();
    }

    /**
     * Get full project info (blocks sorted by order, links).
     *
     * @param \Builder $query Query builder.
     * @param string   $slug  Project slug.
     *
     * @return \Builder
     */
    public function scopeFull($query, $slug)
    {
        return $query->with(['blocks' => function ($query) {
            $query->orderBy('order');
        }])->with('links')->where('slug', $slug)
            ->first();
    }

    /**
     * Return images with all props needed for javascript.
     *
     * @return Collection
     */
    public function imagesWithProps()
    {
        return $this->images
            ->map(function ($image) {
                return $image->generateProps();
            })->reverse()->values();
    }
}
