<?php

namespace Larafolio\tests\unit;

use Larafolio\Models\Link;
use Larafolio\Models\Project;
use Larafolio\tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LinkTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function user_can_add_link_to_project()
    {
        $project = factory(Project::class)->create();

        $data = [
            'name' => 'name',
            'url' => 'url'
        ];

        $this->user->addLinkToProject($project, $data);

        $data['project_id'] = $project->id();

        $this->seeInDatabase('links', $data);
    }

    /**
     * @test
     */
    public function user_can_add_links_with_other_data()
    {
        $firstLink = [
            'name'  => 'first link name',
            'url' => 'first link url',
        ];

        $secondLink = [
            'name'  => 'second link name',
            'url' => 'second link url',
        ];

        $data = [
            'name'   => 'project name',
            'links' => [$firstLink, $secondLink],
        ];

        $project = $this->user->addProject($data);

        $firstLink['project_id'] = $project->id();
        $secondLink['project_id'] = $project->id();

        $this->seeInDatabase('links', $firstLink);
        $this->seeInDatabase('links', $secondLink);
    }

    /**
     * @test
     */
    public function user_can_update_a_link()
    {
        $link = factory(Link::class)->create();

        $this->seeInDatabase('links', [
            'name'   => $link->name(),
            'url' => $link->url(),
        ]);

        $data = [
            'name'   => 'new name',
            'url' => 'new url',
        ];

        $this->user->updateLink($link, $data);

        $this->seeInDatabase('links', [
            'id' => $link->id(),
            'name'   => $data['name'],
            'url' => $data['url'],
        ]);
    }

    /**
     * @test
     */
    public function user_can_update_link_data_with_other_data()
    {
        $project = factory(Project::class)->create();

        $firstLink = [
            'name'  => 'first link name',
            'url' => 'first link url',
        ];

        $secondLink = [
            'name'  => 'second link name',
            'url' => 'second link url',
        ];

        $data = [
            'name'   => 'updated name',
            'links' => [$firstLink, $secondLink],
        ];

        $this->user->updateProject($project, $data);

        $firstLink['project_id'] = $project->id();

        $this->seeInDatabase('links', $firstLink);

        $secondLink['project_id'] = $project->id();

        $this->seeInDatabase('links', $secondLink);
    }

    /**
     * @test
     */
    public function user_can_remove_a_link()
    {
        $link = factory(Link::class)->create();

        $this->seeInDatabase('links', [
            'name'   => $link->name(),
            'url' => $link->url(),
            'deleted_at' => null,
        ]);

        $success = $this->user->removeLink($link);

        $this->assertTrue($success);

        $this->seeInDatabase('links', [
            'name'   => $link->name(),
            'url' => $link->url(),
        ]);

        $this->dontSeeInDatabase('links', [
            'name'   => $link->name(),
            'url' => $link->url(),
            'deleted_at' => null,
        ]);
    }
}
