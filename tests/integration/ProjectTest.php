<?php

namespace Larafolio\tests\integration;

use Larafolio\Models\Project;
use Larafolio\tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function only_admin_user_can_access_create_project_page()
    {
        $this->visit('/manager/add')
             ->seeStatusCode(200)
             ->seePageIs('/');
    }

    /**
     * @test
     */
    public function only_admin_can_post_new_project()
    {
        $this->dontSeeInDatabase('projects', ['name' => 'name']);

        $this->json('POST', '/manager',
            ['name' => 'name'],
            ['link' => 'https://www.google.com']
        )
             ->seeStatusCode(302)
             ->dontSeeInDatabase('projects', ['name' => 'name']);
    }

    /**
     * @test
     */
    public function only_admin_user_can_access_update_project_page()
    {
        $project = factory(Project::class)->create();

        $this->visit('/manager/'.$project->slug().'/edit')
             ->seeStatusCode(200)
             ->seePageIs('/');
    }

    /**
     * @test
     */
    public function only_admin_user_can_post_update_project()
    {
        $project = factory(Project::class)->create();

        $this->seeInDatabase('projects', ['id' => $project->id()])
             ->json('PATCH', 'manager/'.$project->slug().'/update', ['name' => 'new'])
             ->seeStatusCode(302)
             ->dontSeeInDatabase('projects', ['name' => 'new']);
    }

    /**
     * @test
     */
    public function only_admin_user_can_remove_a_project()
    {
        $project = factory(Project::class)->create();

        $this->seeInDatabase('projects', ['id' => $project->id()])
             ->json('DELETE', "/manager/{$project->slug()}")
             ->seeStatusCode(302)
             ->seeInDatabase('projects', ['id' => $project->id()]);
    }
}
