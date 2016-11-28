<?php

namespace Larafolio\tests\unit;

use Larafolio\Models\Image;
use Larafolio\Models\Project;
use Larafolio\tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ImageTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function user_can_add_images()
    {
        $project = factory(Project::class)->create();

        $imageData = [
            'path'     => 'path1.jpg',
            'name'     => 'name1',
            'caption' => 'caption',
        ];

        $image = $this->user->addImageToProject($project, $imageData);

        $this->assertInstanceOf(Image::class, $image);

        $imageData['project_id'] = $project->id();

        $this->seeInDatabase('images', $imageData);
    }

    /**
     * @test
     */
    public function user_can_edit_image_name_and_caption()
    {
        $project = $this->makeProjectWithImages();

        $image = $project->images->first();

        $imageData = ['name' => 'new name', 'caption' => 'new caption'];

        $this->user->updateImageInfo($image, $imageData);

        $imageData['id'] = $image->id();

        $this->seeInDatabase('images', $imageData);
    }

    /**
     * @test
     */
    public function user_can_delete_images_in_project()
    {
        $project = $this->makeProjectWithImages();

        $project->images->each(function ($image) {
            $this->seeInDatabase('images', ['id' => $image->id()]);

            $this->user->removeImage($image);

            $this->dontSeeInDatabase('images', ['id' => $image->id()]);
        });
    }
}
