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
    protected $fillable = ['name', 'text', 'url'];

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
     * Return the link id.
     *
     * @return int
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Return the link name.
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Return the link text.
     *
     * @return string
     */
    public function text()
    {
        return $this->text;
    }

    /**
     * Return the link url.
     *
     * @return string
     */
    public function url()
    {
        return $this->url;
    }
}
