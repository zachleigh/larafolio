<?php

namespace Larafolio\Models;

use Larafolio\Helpers\Sluggable;
use Larafolio\Models\ContentTraits\HasLinks;
use Larafolio\Models\ContentTraits\HasBlocks;
use Larafolio\Models\ContentTraits\HasImages;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HasContent extends Model
{
    use HasBlocks, HasImages, HasLinks, Sluggable, SoftDeletes;

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

        static::creating(function (Model $model) {
            $model->setSlug('name');
        });

        static::updating(function (Model $model) {
            $model->setSlug('name');
        });
    }

    /**
     * Order and group query, return results.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query Query to be ordered.
     * @param bool                                  $group If true, group projects by 'type'.
     * @param bool                                  $order If true, order projects by 'order'.
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
                ->each(function (Model $model, $key) {
                    $model->index = $key;
                })
                ->groupBy('type');
        }

        return $query->get();
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
     * Get all models with relationship on table that have given name.
     *
     * @param string $class      Model class name.
     * @param string $modelTable Model table name.
     * @param string $table      Name of table relationship is on.
     * @param string $name       Relationship name.
     *
     * @return \Illuminate\Support\Collection
     */
    protected static function hasRelationshipNamed($class, $modelTable, $table, $name)
    {
        return static::join($table, "{$modelTable}.id", '=', "{$table}.resource_id")
            ->where("{$table}.name", '=', $name)
            ->where("{$table}.resource_type", '=', $class)
            ->select("{$modelTable}.*")
            ->get();
    }

    /**
     * Get blocks sorted by order.
     *
     * @param Illuminate\Database\Eloquent\Builder $query Query builder.
     * @param string                               $slug  Project slug.
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithBlocks($query, $slug)
    {
        return $query->orderRelationship('blocks')
            ->where('slug', $slug);
    }

    /**
     * Get full model info (blocks and links sorted by order).
     *
     * @param Illuminate\Database\Eloquent\Builder $query Query builder.
     * @param string                               $slug  Project slug.
     *
     * @return Illuminate\Database\Eloquent\Builder
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
     * @param Illuminate\Database\Eloquent\Builder $query        Query builder.
     * @param string                               $relationship Name of relationship to order.
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderRelationship($query, $relationship)
    {
        return $query->with([$relationship => function ($query) {
            $query->orderBy('order');
        }]);
    }

    /**
     * Return page properties to be passed to js.
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
