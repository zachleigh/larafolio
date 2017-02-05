<?php

namespace Larafolio\database\seeds;

use App\User;
use Larafolio\Models\Page;
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
     * User instance.
     *
     * @var User
     */
    protected $user;

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $filesystem = new Filesystem();

        $this->user = User::find(1);

        $this->makeProjectImages($filesystem);

        $this->makePageImages($filesystem);

        $old = umask(0);

        chmod(storage_path('app/public/images'), 0775);

        umask($old);
    }

    /**
     * Make images for project.
     *
     * @param  \Illuminate\Filesystem\Filesystem $filesystem
     */
    protected function makeProjectImages(Filesystem $filesystem)
    {
        $images = $filesystem->allFiles(__DIR__.'/../../../tests/_data/images');

        foreach ($images as $image) {
            $name = $image->getFilename();

            $path = 'public/images/'.$name;

            $this->moveImage($image, $path, $filesystem);

            $this->addToProject($name, $path);
        }
    }

    /**
     * Make images for project.
     *
     * @param  \Illuminate\Filesystem\Filesystem $filesystem
     */
    protected function makePageImages(Filesystem $filesystem)
    {
        $image = $filesystem->get(__DIR__.'/../../../tests/_data/new.jpg');

        $path = 'public/images/pageImage.jpg';

        \Storage::put($path, $image);

        $page = Page::first();

        $this->user->addImageToModel($page, ['path' => $path]);
    }

    /**
     * Move image file to storage.
     *
     * @param \Symfony\Component\Finder\SplFileInfo $image      File info.
     * @param string                                $path       Path to move to.
     * @param \Illuminate\Filesystem\Filesystem     $filesystem
     */
    protected function moveImage($image, $path, Filesystem $filesystem)
    {
        $imageFile = $filesystem->get($image->getPathname());

        \Storage::put($path, $imageFile);
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

        $this->user->addImageToModel($project, ['path' => $path]);
    }
}
