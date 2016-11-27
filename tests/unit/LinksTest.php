<?php

namespace Larafolio\tests\unit;

use Larafolio\Models\Link;
use Larafolio\Models\Project;
use Larafolio\tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LinksTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function user_can_add_link_to_project()
    {
        $project = factory(Project::class)->create();

        $data = [
            'key' => 'key',
            'link' => 'link'
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
            'key'  => 'first link key',
            'link' => 'first link',
        ];

        $secondLink = [
            'key'  => 'second link key',
            'link' => 'second link',
        ];

        $data = [
            'name'   => 'project name',
            'link'   => 'link',
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
            'key'   => $link->key(),
            'link' => $link->link(),
        ]);

        $data = [
            'key'   => 'new key',
            'link' => 'new link',
        ];

        $this->user->updateLink($link, $data);

        $this->seeInDatabase('links', [
            'id' => $link->id(),
            'key'   => $data['key'],
            'link' => $data['link'],
        ]);
    }

    /**
     * @test
     */
    public function user_can_update_link_data_with_other_data()
    {
        $project = factory(Project::class)->create();

        $firstLink = [
            'key'  => 'first link key',
            'link' => 'first link',
        ];

        $secondLink = [
            'key'  => 'second link key',
            'link' => 'second link',
        ];

        $data = [
            'name'   => 'updated name',
            'link'   => 'updated link',
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
            'key'   => $link->key(),
            'link' => $link->link(),
            'deleted_at' => null,
        ]);

        $success = $this->user->removeLink($link);

        $this->assertTrue($success);

        $this->seeInDatabase('links', [
            'key'   => $link->key(),
            'link' => $link->link(),
        ]);

        $this->dontSeeInDatabase('links', [
            'key'   => $link->key(),
            'link' => $link->link(),
            'deleted_at' => null,
        ]);
    }
}
