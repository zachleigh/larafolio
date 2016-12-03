<?php

namespace Larafolio\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
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
        'path', 'name', 'caption',
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
        return $this->imageRoute('thumbnail');
    }

    /**
     * Get route for small version of image.
     *
     * @return string
     */
    public function small()
    {
        return $this->imageRoute('small');
    }

    /**
     * Get route for medium version of image.
     *
     * @return string
     */
    public function medium()
    {
        return $this->imageRoute('medium');
    }

    /**
     * Get route for full size version of image.
     *
     * @return string
     */
    public function full()
    {
        return $this->imageRoute('full');
    }

    /**
     * Get route for given template image.
     *
     * @param  string $templateName Name of image cache template.
     *
     * @return string
     */
    public function imageRoute($templateName)
    {
        return "/manager/images/{$templateName}/{$this->fileName()}";
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
     * Return image properties to be passed to js.
     *
     * @return array
     */
    public function generateProps()
    {
        return [
            'thumbnail' => $this->thumbnail(),
            'small'     => $this->small(),
            'medium'    => $this->medium(),
            'full'      => $this->full(),
            'name'      => $this->name(),
            'caption'   => $this->caption(),
            'id'        => $this->id(),
        ];
    }
}
