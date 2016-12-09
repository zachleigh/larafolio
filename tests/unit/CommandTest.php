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
        $this->cleanDirectories();

        $this->assertFilesDontExist();

        Artisan::call('larafolio:seeds');

        $this->assertFilesExist();

        $this->cleanDirectories();
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

        $this->assertFileNotExists(database_path('factories/ModelFactory.php'));
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

        $this->assertFileExists(database_path('factories/ModelFactory.php'));
    }

    /**
     * Get path for seed file/directory.
     *
     * @param  string $fileName File name.
     *
     * @return string
     */
    protected function getSeedFilePath($fileName = null)
    {
        if (!$fileName) {
            return database_path('seeds');
        }
        
        return database_path("seeds/{$fileName}");
    }

    /**
     * Clean all seeds and factories directories.
     */
    protected function cleanDirectories()
    {
        $filesystem = new Filesystem();

        $filesystem->cleanDirectory($this->getSeedFilePath());

        $filesystem->cleanDirectory(database_path('factories'));
    }
}
