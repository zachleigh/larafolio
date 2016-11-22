<?php

namespace Larafolio\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    /**
     * Props to pass to vue.
     *
     * @var array
     */
    public $props = [];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'path', 'name', 'caption'
    ];

    /**
     * Return the image id.
     *
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Return the image path.
     *
     * @return string
     */
    public function path()
    {
        return $this->path;
    }

    /**
     * Return the image name.
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Return the image caption.
     *
     * @return string
     */
    public function caption()
    {
        return $this->caption;
    }

    /**
     * Get route for thumbnail version of image.
     *
     * @return string
     */
    public function thumbnail()
    {
        return '/manager/images/thumbnail/'.$this->fileName();
    }

    /**
     * Get route for small version of image.
     *
     * @return string
     */
    public function small()
    {
        return '/manager/images/small/'.$this->fileName();
    }

    /**
     * Get route for medium version of image.
     *
     * @return string
     */
    public function medium()
    {
        return '/manager/images/medium/'.$this->fileName();
    }

    /**
     * Get route for full size version of image.
     *
     * @return string
     */
    public function full()
    {
        return '/manager/images/full/'.$this->fileName();
    }

    /**
     * Get the file name of the image.
     *
     * @return string
     */
    public function fileName()
    {
        return collect(explode('/', $this->path()))->last();
    }

    /**
     * Generate image properties to be passed to js and save on object.
     */
    public function generateProps()
    {
        $this->props = [
            'thumbnail' => $this->thumbnail(),
            'small' => $this->small(),
            'medium' => $this->medium(),
            'full' => $this->full(),
            'name' => $this->name(),
            'caption' => $this->caption(),
            'id' => $this->id()
        ];
    }
}
