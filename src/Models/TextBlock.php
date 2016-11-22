<?php

namespace Larafolio\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TextBlock extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'text_blocks';

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
    protected $fillable = ['name', 'text', 'formatted_text', 'order'];

    /**
     * A text block belongs to a single project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Return the text block id.
     *
     * @return int
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Return the text block name.
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Return the raw block text.
     *
     * @return string
     */
    public function text()
    {
        return $this->text;
    }

    /**
     * Return the formatted block text.
     * 
     * @return string
     */
    public function formattedText()
    {
        return $this->formatted_text;
    }

    /**
     * Return the text block order value.
     *
     * @return int
     */
    public function order()
    {
        return $this->order;
    }
}
