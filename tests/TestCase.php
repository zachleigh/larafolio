<?php

namespace Larafolio\tests;

use App\User;
use Larafolio\Models\Image;
use Larafolio\Models\Project;
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
    public function createProjectWithBlock($name = 'name')
    {
        $project = factory(Project::class)->create();

        $project->blocks()->create([
            'name'           => $name,
            'text'           => 'text',
            'formatted_text' => 'formatted',
            'order' => 5
        ]);

        return $project;
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

        foreach (range(1, $imageCount) as $i) {
            $project->images()->save(factory(Image::class)->make());
        }

        return $project;
    }

    /**
     * Make a single project with an image.
     *
     * @param  string $name Image name.
     *
     * @return Larafolio\Models\Project
     */
    protected function makeProjectWithImage($name = 'name', $alt = 'alt')
    {
        $project = factory(Project::class)->create();

        $project->images()->save(factory(Image::class)->make([
            'name' => $name,
            'alt'  => $alt,
        ]));

        return $project;
    }

    /**
     * Make a project with a link.
     *
     * @param  string $name Name of link.
     *
     * @return Larafolio\Models\Project
     */
    protected function makeProjectWithLink($name = 'name', $url = 'url', $text = 'text')
    {
        $project = factory(Project::class)->create();

        $link = [
            'name' => $name,
            'url'  => $url,
            'text' => $text,
        ];

        $data = [
            'links' => [$link],
        ];

        $this->user->updateProject($project, $data);

        return $project;
    }
}
