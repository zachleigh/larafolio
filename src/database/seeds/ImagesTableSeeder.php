<?php

namespace Larafolio\database\seeds;

use App\User;
use Larafolio\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;

class ImagesTableSeeder extends Seeder
{
    /**
     * Lookup table to map images to projects.
     *
     * @var array
     */
    protected $projectLookup = [
        'a' => 1,
        'b' => 2,
        'c' => 3,
        'd' => 4,
        'e' => 5,
    ];

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $filesystem = new Filesystem();

        $from = __DIR__.'/../../../tests/_data/images';

        $images = $filesystem->allFiles($from);

        foreach ($images as $key => $image) {
            $name = $image->getFilename();

            $path = 'public/images/'.$name;

            $this->moveImage($image, $path, $filesystem);

            $this->addToProject($name, $path);
        }

        $old = umask(0);

        chmod(storage_path('app/public/images'), 0775);

        umask($old);
    }

    /**
     * Move image file to storage.
     *
     * @param \Symfony\Component\Finder\SplFileInfo $image      File info.
     * @param string                                $path       Path to move to.
     * @param \Illuminate\Filesystem\Filesystem     $filesystem
     */
    protected function moveImage($image, $path, $filesystem)
    {
        $imageFile = $filesystem->get($image->getPathname());

        \Storage::put($path, $imageFile);

        storage_path('app/'.$path);
    }

    /**
     * Add image to a project.
     *
     * @param string $name Original filename.
     * @param string $path Path to image.
     */
    protected function addToProject($name, $path)
    {
        $key = $this->projectLookup[substr($name, 0, 1)];

        $project = Project::find($key);

        $user = User::find(1);

        $user->addImageToProject($project, ['path' => $path]);
    }
}
