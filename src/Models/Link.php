<?php

namespace Larafolio\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Link extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'links';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['key', 'url'];

    /**
     * A link belongs to a single project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Return link id.
     *
     * @return int
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Return the link key.
     *
     * @return string
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * Return the url.
     *
     * @return string
     */
    public function url()
    {
        return $this->url;
    }
}
