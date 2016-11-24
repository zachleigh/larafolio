<?php

namespace Larafolio\tests;

use App\User;
use Larafolio\Models\Image;
use Larafolio\Models\Project;
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

        $this->app['config']->set('database.default', 'sqlite');

        $this->app['config']->set(
            'database.connections.sqlite.database',
            ':memory:'
        );

        $this->migrate();

        $this->makeAdminUser();
    }

    /**
     * Run all application migrations.
     */
    protected function migrate()
    {
        $paths = $this->getMigrationPaths();

        collect(iterator_to_array($paths))->flatten()
            ->sort()
            ->each(function ($path) {
                require_once $path;

                $className = $this->getClassNameFromPath($path);

                (new $className())->up();
            });
    }

    /**
     * Get all migration file paths from migrations folder.
     *
     * @return array
     */
    protected function getMigrationPaths()
    {
        $path = __DIR__.'/../src/database/migrations/';

        $directory = new \RecursiveDirectoryIterator($path);

        $iterator = new \RecursiveIteratorIterator($directory);

        return new \RegexIterator(
            $iterator,
            '/^.+\.php$/i',
            \RecursiveRegexIterator::GET_MATCH
        );
    }

    /**
     * Get migration class name form the migration file path.
     *
     * @param string $path Migration file path.
     *
     * @return string
     */
    protected function getClassNameFromPath($path)
    {
        $pathArray = explode('/', $path);

        $fileName = array_pop($pathArray);

        $snakeClassName = collect(explode('_', $fileName))
            ->filter(function ($value) {
                return !is_numeric($value);
            })->implode('_');

        return str_replace('.php', '', studly_case($snakeClassName));
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
