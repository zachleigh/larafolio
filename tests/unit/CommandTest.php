<?php

namespace Larafolio\tests\unit;

use Artisan;
use Larafolio\tests\TestCase;
use Illuminate\Filesystem\Filesystem;

class CommandTest extends TestCase
{
    /**
     * @test
     */
    public function publish_seeds_command_moves_seeders_to_app()
    {
        $filesystem = new Filesystem();

        $filesystem->cleanDirectory($this->getSeedFilePath());

        $this->assertFilesDontExist();

        Artisan::call('larafolio:seeds');

        $this->assertFilesExist();

        $filesystem->cleanDirectory($this->getSeedFilePath());
    }

    /**
     * Assert that all seeder files do not exist in seeds directory.
     */
    protected function assertFilesDontExist()
    {
        $this->assertFileNotExists($this->getSeedFilePath('LarafolioSeeder.php'));

        $this->assertFileNotExists($this->getSeedFilePath('ImagesTableSeeder.php'));

        $this->assertFileNotExists($this->getSeedFilePath('ProjectsTableSeeder.php'));

        $this->assertFileNotExists($this->getSeedFilePath('TextBlocksTableSeeder.php'));

        $this->assertFileNotExists($this->getSeedFilePath('UsersTableSeeder.php'));
    }

    /**
     * Assert that all seeder files exist in seeds directory.
     */
    protected function assertFilesExist()
    {
        $this->assertFileExists($this->getSeedFilePath('LarafolioSeeder.php'));

        $this->assertFileExists($this->getSeedFilePath('ImagesTableSeeder.php'));

        $this->assertFileExists($this->getSeedFilePath('ProjectsTableSeeder.php'));

        $this->assertFileExists($this->getSeedFilePath('TextBlocksTableSeeder.php'));

        $this->assertFileExists($this->getSeedFilePath('UsersTableSeeder.php'));
    }

    /**
     * Get path for seed file/directory.
     *
     * @param  string $fileName File name.
     *
     * @return string
     */
    public function getSeedFilePath($fileName = null)
    {
        if (!$fileName) {
            return database_path('seeds');
        }
        
        return database_path("seeds/{$fileName}");
    }
}
