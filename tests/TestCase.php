<?php

namespace Larafolio\tests;

use App\User;
use Larafolio\Models\Link;
use Larafolio\Models\Page;
use Larafolio\Models\Image;
use Larafolio\Models\Project;
use Larafolio\Models\HasContent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Foundation\Testing\TestCase as IlluminateTestCase;

abstract class TestCase extends IlluminateTestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../vendor/laravel/laravel/bootstrap/app.php';

        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        $app->make(EloquentFactory::class)->load(__DIR__.'/../src/database/factories/');

        return $app;
    }

    /**
     * Setup the test class.
     */
    public function setUp()
    {
        parent::setUp();

        Artisan::call('migrate');

        $this->makeAdminUser();
    }

    /**
     * Make a new admin user with ID of 1.
     *
     * @return User
     */
    protected function makeAdminUser()
    {
        if ($user = User::find(1)) {
            return $this->user = $user;
        }

        return $this->user = factory(User::class)->create();
    }

    /**
     * Create a project with a single block.
     *
     * @param string $name Name of block.
     *
     * @return Larafolio\Models\Project
     */
    protected function createProjectWithBlock($name = 'name')
    {
        $project = factory(Project::class)->create();

        return $this->addBlockToModel($project, $name);
    }

    /**
     * Create a page with a single block.
     *
     * @param string $name Name of block.
     *
     * @return Larafolio\Models\Page
     */
    protected function createPageWithBlock($name = 'name')
    {
        $page = factory(Page::class)->create();

        return $this->addBlockToModel($page, $name);
    }

    /**
     * Add a block to a HasContent model.
     *
     * @param Larafolio\Models\HasContent $model Model to add block to.
     * @param string                      $name  Name of block.
     *
     * @return Larafolio\Models\HasContent
     */
    private function addBlockToModel(HasContent $model, $name)
    {
        $model->blocks()->create([
            'name'           => $name,
            'text'           => 'text',
            'formatted_text' => 'formatted',
            'order'          => 5,
        ]);

        return $model;
    }

    /**
     * Make a new project with the given number of images.
     *
     * @param int $imageCount The number of images to attach to project.
     *
     * @return Larafolio\Models\Project
     */
    protected function makeProjectWithImages($imageCount = 3)
    {
        $project = factory(Project::class)->create();

        return $this->addImagesToModel($project, $imageCount);
    }

    /**
     * Make a new page with the given number of images.
     *
     * @param int $imageCount The number of images to attach to page.
     *
     * @return Larafolio\Models\Page
     */
    protected function makePageWithImages($imageCount = 3)
    {
        $page = factory(Page::class)->create();

        return $this->addImagesToModel($page, $imageCount);
    }

    /**
     * Add images to a HasContent model.
     *
     * @param Larafolio\Models\HasContent $model      Model to add images to.
     * @param int                         $imageCount Number of images to add.
     *
     * @return Larafolio\Models\HasContent 
     */
    private function addImagesToModel(HasContent $model, $imageCount)
    {
        foreach (range(1, $imageCount) as $i) {
            $model->images()->save(factory(Image::class)->make());
        }

        return $model;
    }

    /**
     * Make a single project with an image.
     *
     * @param string $name Image name.
     *
     * @return Larafolio\Models\Project
     */
    protected function makeProjectWithImage($name = 'name', $alt = 'alt')
    {
        $project = factory(Project::class)->create();

        return $this->addSingleImageToModel($project, $name, $alt);
    }

    /**
     * Make a single page with an image.
     *
     * @param string $name Image name.
     *
     * @return Larafolio\Models\Page
     */
    protected function makePageWithImage($name = 'name', $alt = 'alt')
    {
        $page = factory(Page::class)->create();

        return $this->addSingleImageToModel($page, $name, $alt);
    }

    /**
     * Add a single image to a HasContent model.
     *
     * @param Larafolio\Models\HasContent $model Model to add image to.
     * @param string                      $name  Name of image.
     * @param string                      $alt   Alt text for image.
     *
     * @return Larafolio\Models\HasContent
     */
    private function addSingleImageToModel(HasContent $model, $name, $alt)
    {
        $model->images()->save(factory(Image::class)->make([
            'name' => $name,
            'alt'  => $alt,
        ]));

        return $model;
    }

    /**
     * Make a project with a link.
     *
     * @param string $name Name of link.
     *
     * @return Larafolio\Models\Project
     */
    protected function makeProjectWithLink($name = 'name', $url = 'url', $text = 'text')
    {
        $project = factory(Project::class)->create();
        
        return $this->addLinkToModel($project, $name, $url, $text);
    }

    /**
     * Make a page with a link.
     *
     * @param string $name Name of link.
     *
     * @return Larafolio\Models\Page
     */
    protected function makePageWithLink($name = 'name', $url = 'url', $text = 'text')
    {
        $page = factory(Page::class)->create();

        return $this->addLinkToModel($page, $name, $url, $text);
    }
    /**
     * Add a link to a model.
     *
     * @param HasContent $model Model to add link to.
     * @param string     $name  Name to assign to link.
     * @param string     $url   Link url.
     * @param string     $text  Link text.
     *
     * @return HasContent
     */
    private function addLinkToModel(HasContent $model, $name, $url, $text)
    {
        $model->links()->save(factory(Link::class)->make([
            'name' => $name,
            'url'  => $url,
            'text' => $text,
        ]));

        return $model;
    }

    /**
     * Assert a collection is ordered.
     *
     * @param \Illuminate\Support\Collection $ordered Collection of numbers.
     */
    protected function assertOrder(Collection $ordered)
    {
        $current = 0;

        $ordered->each(function ($order) use (&$current) {
            $this->assertTrue($order >= $current);

            if ($order != $current) {
                $current = $order;
            }
        });
    }
}
