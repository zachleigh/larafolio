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
            'name'  => 'name',
            'text'  => 'text',
            'url'   => 'url',
            'order' => 0,
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
            'text'  => 'first link text',
            'url'   => 'first link url',
            'order' => 0,
        ];

        $secondLink = [
            'name'  => 'second link name',
            'text'  => 'second link text',
            'url'   => 'second link url',
            'order' => 1,
        ];

        $data = [
            'name'  => 'project name',
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
            'name' => $link->name(),
            'url'  => $link->url(),
        ]);

        $data = [
            'name' => 'new name',
            'text' => 'new text',
            'url'  => 'new url',
        ];

        $this->user->updateLink($link, $data);

        $this->seeInDatabase('links', [
            'id'   => $link->id(),
            'name' => $data['name'],
            'url'  => $data['url'],
        ]);
    }

    /**
     * @test
     */
    public function user_can_update_link_data_with_other_data()
    {
        $project = factory(Project::class)->create();

        $firstLink = [
            'name' => 'first link name',
            'text' => 'first link text',
            'url'  => 'first link url',
        ];

        $secondLink = [
            'name' => 'second link name',
            'text' => 'second link text',
            'url'  => 'second link url',
        ];

        $data = [
            'name'  => 'updated name',
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
            'name'       => $link->name(),
            'text'       => $link->text(),
            'url'        => $link->url(),
            'deleted_at' => null,
        ]);

        $success = $this->user->removeLink($link);

        $this->assertTrue($success);

        $this->seeInDatabase('links', [
            'name' => $link->name(),
            'url'  => $link->url(),
        ]);

        $this->dontSeeInDatabase('links', [
            'name'       => $link->name(),
            'text'       => $link->text(),
            'url'        => $link->url(),
            'deleted_at' => null,
        ]);
    }

    /**
     * @test
     */
    public function user_can_get_a_link_from_project()
    {
        $project = $this->makeProjectWithLink();

        $link = $project->link('name');

        $this->assertInstanceOf(Link::class, $link);

        $this->assertEquals('name', $link->name());
    }

    /**
     * @test
     */
    public function user_can_get_a_link_url_from_project()
    {
        $project = $this->makeProjectWithLink();

        $url = $project->linkUrl('name');

        $this->assertEquals('url', $url);
    }

    /**
     * @test
     */
    public function user_can_get_a_link_text_from_project()
    {
        $project = $this->makeProjectWithLink();

        $text = $project->linkText('name');

        $this->assertEquals('text', $text);
    }
}
