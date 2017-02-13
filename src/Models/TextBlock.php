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
     * Convert camelCase properties to snake_case properties and try again.
     *
     * @param  string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        $attribute = parent::__get($key);

        if ($attribute === null) {
            $snakeCase = snake_case($key);

            $attribute = parent::__get($snakeCase);
        }

        return $attribute;
    }
}
