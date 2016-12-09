<?php

namespace Larafolio\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class PublishSeeds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larafolio:seeds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move Larafolio seeders into local project';

    protected $seeds = [
        'ProjectsTableSeeder.php',
        'TextBlocksTableSeeder.php',
        'UsersTableSeeder.php',
    ];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $filesystem = new Filesystem();

        if (!$filesystem->exists($this->seedPath())) {
            $filesystem->makeDirectory($this->seedPath());

            $this->info('Larafolio directory created.');
        }

        $this->copyRootSeeder($filesystem);

        $this->copyImageSeeder($filesystem);

        foreach ($this->seeds as $seed) {
            $this->copySeed($filesystem, $seed);
        }
    }

    /**
     * Copy the root database seeder class to the app.
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     */
    protected function copyRootSeeder(Filesystem $filesystem)
    {
        $file = $filesystem->get(__DIR__.'/../database/seeds/DatabaseSeeder.php');

        $file = $this->removeNamespace($file);

        $file = str_replace('DatabaseSeeder', 'LarafolioSeeder', $file);

        $filesystem->put($this->seedPath('LarafolioSeeder.php'), $file);
    }

    /**
     * Copy the image table seeder class to the app.
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     */
    protected function copyImageSeeder(Filesystem $filesystem)
    {
        $file = $filesystem->get(__DIR__.'/../database/seeds/ImagesTableSeeder.php');

        $file = $this->removeNamespace($file);

        $file = str_replace(__DIR__.'/../../../tests/_data/images',
            __DIR__.'/../../vendor/zachleigh/larafolio/tests/_data/images',
            $file
        );

        $filesystem->put($this->seedPath('ImagesTableSeeder.php'), $file);
    }

    /**
     * Copy standard seed to the app.
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     * @param string                            $seed       Seed name.
     */
    protected function copySeed(Filesystem $filesystem, $seed)
    {
        $file = $filesystem->get(__DIR__."/../database/seeds/{$seed}");

        $file = $this->removeNamespace($file);

        $filesystem->put($this->seedPath($seed), $file);
    }

    /**
     * Return database seed path plus any file name.
     *
     * @param string $fileName Name of file to append to path.
     *
     * @return string
     */
    protected function seedPath($fileName = null)
    {
        if (!$fileName) {
            return database_path('seeds');
        }

        return database_path('seeds/').$fileName;
    }

    /**
     * Remove namespace from seeder file.
     *
     * @param string $file File contents.
     *
     * @return string
     */
    protected function removeNamespace($file)
    {
        return str_replace("namespace Larafolio\database\seeds;\n\n", '', $file);
    }
}
