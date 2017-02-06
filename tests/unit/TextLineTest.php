<?php

namespace Larafolio\tests\unit;

use Larafolio\Models\Project;
use Larafolio\tests\TestCase;
use Larafolio\Models\TextLine;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TextLineTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function user_can_add_a_text_line_to_a_project()
    {
        $project = factory(Project::class)->create();

        $data = [
            'name'           => 'text block name',
            'text'           => 'text block text',
            'order'          => 0,
        ];

        $this->user->addLineToModel($project, $data);

        $data['resource_id'] = $project->id();

        $this->seeInDatabase('text_lines', $data);
    }

    /**
     * @test
     */
    public function user_can_add_text_lines_with_other_data()
    {
        $firstLine = [
            'name'           => 'first text line name',
            'text'           => 'first text line text',
            'order'          => 0,
        ];

        $secondLine = [
            'name'           => 'second text line name',
            'text'           => 'second text line text',
            'order'          => 1,
        ];

        $data = [
            'name'   => 'project name',
            'link'   => 'link',
            'lines' => [$firstLine, $secondLine],
        ];

        $project = $this->user->addProject($data);

        $firstLine['resource_id'] = $project->id();
        $secondLine['resource_id'] = $project->id();

        $this->seeInDatabase('text_lines', $firstLine);
        $this->seeInDatabase('text_lines', $secondLine);
    }

    /**
     * @test
     */
    public function user_can_update_a_text_line()
    {
        $textLine = factory(TextLine::class)->create();

        $this->seeInDatabase('text_lines', [
            'id'   => $textLine->id(),
            'name' => $textLine->name(),
            'text' => $textLine->text(),
        ]);

        $data = [
            'name' => 'updated name',
            'text' => 'updated text',
        ];

        $this->user->updateTextLine($textLine, $data);

        $this->seeInDatabase('text_lines', [
            'id'   => $textLine->id(),
            'name' => $data['name'],
            'text' => $data['text'],
        ]);
    }

    /**
     * @test
     */
    public function user_can_update_text_line_data_with_other_data()
    {
        $project = factory(Project::class)->create();

        $firstLine = [
            'name'           => 'first text line name',
            'text'           => 'first text line text',
            'order'          => 0,
        ];

        $secondLine = [
            'name'           => 'second text line name',
            'text'           => 'second text line text',
            'order'          => 1,
        ];

        $data = [
            'name'   => 'updated name',
            'link'   => 'updated link',
            'lines' => [$firstLine, $secondLine],
        ];

        $this->user->updateProject($project, $data);

        $firstLine['resource_id'] = $project->id();

        $this->seeInDatabase('text_lines', $firstLine);

        $secondLine['resource_id'] = $project->id();

        $this->seeInDatabase('text_lines', $secondLine);

        $firstLineModel = TextLine::where($firstLine)->first();

        $firstLineUpdated = [
            'name'           => 'first text line name updated',
            'text'           => 'first text line text updated',
            'resource_id'    => $project->id(),
            'id'             => $firstLineModel->id()
        ];

        $newData = [
            'name'   => 'new updated name',
            'link'   => 'new updated link',
            'lines' => [$firstLineUpdated],
        ];

        $this->user->updateProject($project, $newData);

        $this->seeInDatabase('text_lines', $firstLineUpdated);
    }

    /**
     * @test
     */
    public function line_order_is_reduced_when_updating_block()
    {
        $project = factory(Project::class)->create();

        $firstLine = [
            'name'           => 'first text block name',
            'text'           => 'first text block text',
            'order'          => 5,
        ];

        $secondLine = [
            'name'           => 'second text block name',
            'text'           => 'second text block text',
            'order'          => 10,
        ];

        $data = [
            'lines' => [$firstLine, $secondLine],
        ];

        $project = $this->user->updateProject($project, $data);

        $firstLine['order'] = 0;
        $firstLine['resource_id'] = $project->id();

        $this->seeInDatabase('text_lines', $firstLine);

        $secondLine['order'] = 1;
        $secondLine['resource_id'] = $project->id();

        $this->seeInDatabase('text_lines', $secondLine);
    }

    /**
     * @test
     */
    public function user_can_remove_a_text_line()
    {
        $textLine = factory(TextLine::class)->create();

        $this->seeInDatabase('text_lines', [
            'id'         => $textLine->id(),
            'name'       => $textLine->name(),
            'text'       => $textLine->text(),
            'deleted_at' => null,
        ]);

        $success = $this->user->removeTextLine($textLine);

        $this->assertTrue($success);

        $this->seeInDatabase('text_lines', [
            'id'   => $textLine->id(),
            'name' => $textLine->name(),
            'text' => $textLine->text(),
        ]);

        $this->dontSeeInDatabase('text_lines', [
            'id'         => $textLine->id(),
            'name'       => $textLine->name(),
            'text'       => $textLine->text(),
            'deleted_at' => null,
        ]);
    }

    /**
     * @test
     */
    public function user_can_get_a_text_line_from_project()
    {
        $project = $this->createProjectWithLine();

        $line = $project->line('name');

        $this->assertInstanceOf(TextLine::class, $line);

        $this->assertEquals('name', $line->name());
    }

    /**
     * @test
     */
    public function user_can_get_line_text_from_project()
    {
        $project = $this->createProjectWithLine();

        $text = $project->lineText('name', false);

        $this->assertEquals('text', $text);
    }
}
