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

        Artisan::call('migrate:refresh');

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
     * Make a new project with the given number of images.
     *
     * @param int $imageCount The number of images to attach to project.
     *
     * @return Project
     */
    protected function makeProjectWithImages($imageCount = 3)
    {
        $project = factory(Project::class)->create();

        foreach (range(1, $imageCount) as $i) {
            $project->images()->save(factory(Image::class)->make());
        }

        return $project;
    }
}
