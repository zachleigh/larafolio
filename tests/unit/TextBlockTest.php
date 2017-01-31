<?php

namespace Larafolio\tests\unit;

use Larafolio\Models\Project;
use Larafolio\tests\TestCase;
use Larafolio\Models\TextBlock;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TextBlockTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function user_can_add_a_text_block_to_a_project()
    {
        $project = factory(Project::class)->create();

        $data = [
            'name'           => 'text block name',
            'text'           => 'text block text',
            'formatted_text' => '<p>text block text</p>',
            'order'          => 0,
        ];

        $this->user->addBlockToProject($project, $data);

        $data['resource_id'] = $project->id();

        $this->seeInDatabase('text_blocks', $data);
    }

    /**
     * @test
     */
    public function user_can_add_text_blocks_with_other_data()
    {
        $firstBlock = [
            'name'           => 'first text block name',
            'text'           => 'first text block text',
            'formatted_text' => '<p>first text block text</p>',
            'order'          => 0,
        ];

        $secondBlock = [
            'name'           => 'second text block name',
            'text'           => 'second text block text',
            'formatted_text' => '<p>second text block text</p>',
            'order'          => 1,
        ];

        $data = [
            'name'   => 'project name',
            'link'   => 'link',
            'blocks' => [$firstBlock, $secondBlock],
        ];

        $project = $this->user->addProject($data);

        $firstBlock['resource_id'] = $project->id();
        $secondBlock['resource_id'] = $project->id();

        $this->seeInDatabase('text_blocks', $firstBlock);
        $this->seeInDatabase('text_blocks', $secondBlock);
    }

    /**
     * @test
     */
    public function user_can_update_a_text_block()
    {
        $textBlock = factory(TextBlock::class)->create();

        $this->seeInDatabase('text_blocks', [
            'id'   => $textBlock->id(),
            'name' => $textBlock->name(),
            'text' => $textBlock->text(),
        ]);

        $data = [
            'name' => 'updated name',
            'text' => 'updated text',
        ];

        $this->user->updateTextBlock($textBlock, $data);

        $this->seeInDatabase('text_blocks', [
            'id'   => $textBlock->id(),
            'name' => $data['name'],
            'text' => $data['text'],
        ]);
    }

    /**
     * @test
     */
    public function user_can_update_text_block_data_with_other_data()
    {
        $project = factory(Project::class)->create();

        $firstBlock = [
            'name'           => 'first text block name',
            'text'           => 'first text block text',
            'formatted_text' => '<p>first text block text</p>',
            'order'          => 0,
        ];

        $secondBlock = [
            'name'           => 'second text block name',
            'text'           => 'second text block text',
            'formatted_text' => '<p>second text block text</p>',
            'order'          => 1,
        ];

        $data = [
            'name'   => 'updated name',
            'link'   => 'updated link',
            'blocks' => [$firstBlock, $secondBlock],
        ];

        $this->user->updateProject($project, $data);

        $firstBlock['resource_id'] = $project->id();

        $this->seeInDatabase('text_blocks', $firstBlock);

        $secondBlock['resource_id'] = $project->id();

        $this->seeInDatabase('text_blocks', $secondBlock);
    }

    /**
     * @test
     */
    public function block_order_is_reduced_when_updating_block()
    {
        $project = factory(Project::class)->create();

        $firstBlock = [
            'name'           => 'first text block name',
            'text'           => 'first text block text',
            'formatted_text' => '<p>first text block text</p>',
            'order'          => 5,
        ];

        $secondBlock = [
            'name'           => 'second text block name',
            'text'           => 'second text block text',
            'formatted_text' => '<p>second text block text</p>',
            'order'          => 10,
        ];

        $data = [
            'blocks' => [$firstBlock, $secondBlock],
        ];

        $project = $this->user->updateProject($project, $data);

        $firstBlock['order'] = 0;
        $firstBlock['resource_id'] = $project->id();

        $this->seeInDatabase('text_blocks', $firstBlock);

        $secondBlock['order'] = 1;
        $secondBlock['resource_id'] = $project->id();

        $this->seeInDatabase('text_blocks', $secondBlock);
    }

    /**
     * @test
     */
    public function user_can_remove_a_text_block()
    {
        $textBlock = factory(TextBlock::class)->create();

        $this->seeInDatabase('text_blocks', [
            'id'         => $textBlock->id(),
            'name'       => $textBlock->name(),
            'text'       => $textBlock->text(),
            'deleted_at' => null,
        ]);

        $success = $this->user->removeTextBlock($textBlock);

        $this->assertTrue($success);

        $this->seeInDatabase('text_blocks', [
            'id'   => $textBlock->id(),
            'name' => $textBlock->name(),
            'text' => $textBlock->text(),
        ]);

        $this->dontSeeInDatabase('text_blocks', [
            'id'         => $textBlock->id(),
            'name'       => $textBlock->name(),
            'text'       => $textBlock->text(),
            'deleted_at' => null,
        ]);
    }

    /**
     * @test
     */
    public function user_can_get_a_text_block_from_project()
    {
        $project = $this->createProjectWithBlock();

        $block = $project->block('name');

        $this->assertInstanceOf(TextBlock::class, $block);

        $this->assertEquals('name', $block->name());
    }

    /**
     * @test
     */
    public function user_can_get_formatted_block_text_from_project()
    {
        $project = $this->createProjectWithBlock();

        $formattedText = $project->blockText('name');

        $this->assertEquals('formatted', $formattedText);
    }

    /**
     * @test
     */
    public function user_can_get_unformatted_block_text_from_project()
    {
        $project = $this->createProjectWithBlock();

        $unformattedText = $project->blockText('name', false);

        $this->assertEquals('text', $unformattedText);
    }
}
