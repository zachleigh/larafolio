<?php

namespace Larafolio\tests\unit;

use Larafolio\Models\Project;
use Larafolio\tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function user_can_add_a_project()
    {
        $data = [
            'name' => 'name',
            'link' => 'link'
        ];

        $project = $this->user->addProject($data);

        $this->assertInstanceOf(Project::class, $project);

        $this->seeInDatabase('projects', $data);
    }

    /**
     * @test
     */
    public function slug_is_created_when_project_is_added()
    {
        $data = [
            'name' => 'project name',
            'link' => 'link'
        ];

        $project = $this->user->addProject($data);

        $this->assertEquals('project_name', $project->slug());

        $data['slug'] = $project->slug();

        $this->seeInDatabase('projects', $data);
    }

    /**
     * @test
     */
    public function user_can_update_a_project()
    {
        $project = factory(Project::class)->create();

        $data = [
            'link' => 'new link',
        ];

        $this->user->updateProject($project, $data);

        $merged = collect($project->getAttributes())->merge($data)->all();

        $this->seeInDatabase('projects', $merged);
    }

    /**
     * @test
     */
    public function user_can_update_visibility()
    {
        $project = factory(Project::class)->create();

        $this->seeInDatabase('projects', [
            'id' => $project->id(),
            'visible' => false
        ]);

        $data = [
            'visible' => true,
        ];

        $this->user->updateProject($project, $data);

        $this->seeInDatabase('projects', [
            'id' => $project->id(),
            'visible' => true
        ]);
    }

    /**
     * @test
     */
    public function slug_is_updated_when_name_is_updated()
    {
        $project = factory(Project::class)->create(['name' => 'first name']);

        $this->assertEquals('first_name', $project->slug());

        $project = $this->user->updateProject($project, ['name' => 'second name']);

        $this->assertEquals('second_name', $project->slug());
    }

    /**
     * @test
     */
    public function user_can_remove_a_project()
    {
        $project = factory(Project::class)->create();

        $this->seeInDatabase('projects', [
            'id' => $project->id(),
            'deleted_at' => null
        ]);

        $deleted = $this->user->removeProject($project);

        $this->assertTrue($deleted);

        $this->dontSeeInDatabase('projects', [
            'id' => $project->id(),
            'deleted_at' => null
        ]);

        $this->seeInDatabase('projects', [
            'id' => $project->id()
        ]);
    }
}
