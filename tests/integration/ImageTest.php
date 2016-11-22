<?php

namespace Larafolio\tests\integration;

use Larafolio\Models\Image;
use Larafolio\Models\Project;
use Larafolio\tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ImageTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function only_admin_can_post_new_image()
    {
        $this->dontSeeInDatabase('images', ['path' => 'path']);

        $project = factory(Project::class)->create();

        $this->json('POST', "manager/{$project->slug()}/images",
            ['path' => 'path']
        )
             ->seeStatusCode(302)
             ->dontSeeInDatabase('images', ['path' => 'path']);
    }

    /**
     * @test
     */
    public function only_admin_can_access_image_management_page()
    {
        $project = factory(Project::class)->create();

        $this->visit('manager/'.$project->slug().'/images')
             ->seeStatusCode(200)
             ->seePageIs('/');
    }

    /**
     * @test
     */
    public function only_admin_can_post_image_update()
    {
        $image = factory(Image::class)->create();

        $this->seeInDatabase('images', ['path' => $image->path()]);

        $this->json('PATCH', "manager/images/{$image->id()}",
            ['path' => 'new path']
        )
             ->seeStatusCode(302)
             ->dontSeeInDatabase('images', ['path' => 'new path']);
    }

    /**
     * @test
     */
    public function only_admin_can_post_remove_image()
    {
        $image = factory(Image::class)->create();

        $this->seeInDatabase('images', ['id' => $image->id()]);

        $this->json('DELETE', "manager/images/{$image->id()}")
             ->seeStatusCode(302)
             ->seeInDatabase('images', ['id' => $image->id()]);
    }
}
