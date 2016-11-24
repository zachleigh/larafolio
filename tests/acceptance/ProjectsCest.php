<?php

use Larafolio\Models\TextBlock;
use Illuminate\Support\Facades\Artisan;

class ProjectsCest
{
    public function _before(\AcceptanceTester $I)
    {
        Artisan::call('migrate:refresh');

        Artisan::call('db:seed', [
            '--class' => 'Larafolio\database\seeds\DatabaseSeeder',
        ]);
    }

    public function _after(\AcceptanceTester $I)
    {
        Artisan::call('migrate:refresh');

        Artisan::call('db:seed', [
            '--class' => 'Larafolio\database\seeds\DatabaseSeeder',
        ]);
    }

    public function user_can_add_a_project(AcceptanceTester $I)
    {
        $data = [
            'name'        => 'Project Name',
            'link'        => 'Project link',
            'projectType' => 'Project type',
            'text0'       => 'Project description',
        ];

        $I->wantTo('Add a new project to the portfolio.');
        $I->login($I);
        $I->amOnAddPage($I);
        $I->fillForm($I, $data);
        $I->click('Add Project');
        $I->wait(1);
        $I->seeCurrentUrlEquals('/manager/project_name');
        $I->see('Project successfully added');
        $I->seeInDatabase('projects', ['name' => $data['name']]);
        $I->seeInDatabase('text_blocks', ['text' => $data['text0']]);
    }

    public function project_name_is_required(AcceptanceTester $I)
    {
        $data = ['text0' => 'Project description'];

        $I->wantTo('Verify that name required error appears.');
        $I->login($I);
        $I->amOnAddPage($I);
        $I->fillForm($I, $data);
        $I->click('Add Project');
        $I->wait(1);
        $I->confirmOnAddPage($I);
        $I->see('The name field is required.');
    }

    public function block_text_is_required(AcceptanceTester $I)
    {
        $data = ['name' => 'Project Name'];

        $I->wantTo('Verify that text block required error appears.');
        $I->login($I);
        $I->amOnAddPage($I);
        $I->fillForm($I, $data);
        $I->click('Add Project');
        $I->wait(1);
        $I->confirmOnAddPage($I);
        $I->see('The block text field is required.');
    }

    public function user_can_add_new_text_block(AcceptanceTester $I)
    {
        $data = [
            'name'  => 'Project Name',
            'link'  => 'Project link',
            'text0' => 'Project description',
            'text1' => 'Project info',
        ];

        $I->wantTo('Add a new text block to a project being created.');
        $I->login($I);
        $I->amOnAddPage($I);
        $I->click('+');
        $I->fillForm($I, $data);
        $I->click('Add Project');
        $I->wait(1);
        $I->seeCurrentUrlEquals('/manager/project_name');
        $I->seeInDatabase('projects', ['name' => $data['name']]);
        $I->seeInDatabase('text_blocks', ['text' => $data['text0']]);
        $I->seeInDatabase('text_blocks', ['text' => $data['text1']]);
    }

    public function user_can_remove_a_text_block(AcceptanceTester $I)
    {
        $data = [
            'text0' => 'Project description',
            'text1' => 'Project info',
        ];

        $I->wantTo('Remove a text block from a project being created.');
        $I->login($I);
        $I->amOnAddPage($I);
        $I->click('+');
        $I->fillForm($I, $data);
        $I->see('Project description');
        $I->see('Project info');
        $I->removeBlock($I, '#delete1');
        $I->dontSee('Project info');
    }

    public function user_can_add_and_delete_blocks_like_crazy(AcceptanceTester $I)
    {
        $I->wantTo('Add and delete a bunch of random text blocks.');
        $I->login($I);
        $I->amOnAddPage($I);
        // fill 0
        $I->fillField(['name' => 'text0'], 'block0');
        $I->see('block0');
        // add and fill 1
        $I->click('+');
        $I->fillField(['name' => 'text1'], 'block1');
        $I->see('block1');
        // add and fill 2
        $I->click('+');
        $I->fillField(['name' => 'text2'], 'block2');
        $I->see('block2');
        // delete 1
        $I->removeBlock($I, '#delete1');
        $I->dontSee('block1');
        // add and fill 3
        $I->click('+');
        $I->fillField(['name' => 'text2'], 'block3');
        $I->see('block3');
        // delete 0
        $I->removeBlock($I, '#delete0');
        $I->dontSee('block0');
        // add and fill 4
        $I->click('+');
        $I->fillField(['name' => 'text2'], 'block4');
        $I->see('block4');
        // add and fill 5
        $I->click('+');
        $I->fillField(['name' => 'text3'], 'block5');
        $I->see('block5');
        // delete 5
        $I->removeBlock($I, '#delete3');
        $I->dontSee('block5');
        // add and fill 6
        $I->click('+');
        $I->fillField(['name' => 'text3'], 'block6');
        $I->see('block6');
        $I->see('block2');
        $I->see('block3');
        $I->see('block4');
    }

    public function user_can_move_text_block_up(AcceptanceTester $I)
    {
        $data = [
            'text0' => 'block0',
            'text1' => 'block1',
            'text2' => 'block2',
        ];

        $I->wantTo('Move a text block up.');
        $I->login($I);
        $I->amOnAddPage($I);
        $I->click('+');
        $I->click('+');
        $I->fillForm($I, $data);
        $I->click('#up2');
        $I->click('#up1');
        $I->removeBlock($I, '#delete2');
        $I->removeBlock($I, '#delete1');
        $I->see('block2');
        $I->dontSee('block0');
        $I->dontSee('block1');
    }

    public function user_can_move_text_block_down(AcceptanceTester $I)
    {
        $data = [
            'text0' => 'block0',
            'text1' => 'block1',
            'text2' => 'block2',
        ];

        $I->wantTo('Move a text block down.');
        $I->login($I);
        $I->amOnAddPage($I);
        $I->click('+');
        $I->click('+');
        $I->fillForm($I, $data);
        $I->click('#down0');
        $I->click('#down1');
        $I->removeBlock($I, '#delete1');
        $I->removeBlock($I, '#delete0');
        $I->see('block0');
        $I->dontSee('block1');
        $I->dontSee('block2');
    }

    public function user_can_toggle_visibility(AcceptanceTester $I)
    {
        $project = $I->getProject($I);
        $I->wantTo('Toggle the visibility of a project.');
        $I->login($I);
        $I->amOnPage("/manager/{$project->slug()}");
        $I->see('Hidden');
        $I->seeInDatabase('projects', [
            'id'      => $project->id(),
            'visible' => false,
        ]);
        $I->click('#makeVisible');
        $I->see('Visible');
        $I->see('Project is now publicly viewable');
        $I->dontSee('Hidden');
        $I->seeInDatabase('projects', [
            'id'      => $project->id(),
            'visible' => true,
        ]);
        $I->click('#makeHidden');
        $I->see('Hidden');
        $I->see('Project is not publicly viewable');
        $I->dontSee('Visible');
        $I->seeInDatabase('projects', [
            'id'      => $project->id(),
            'visible' => false,
        ]);
    }

    public function user_can_remove_a_project(AcceptanceTester $I)
    {
        $project = $I->getProject($I);
        $I->wantTo('Remove a project from the portfolio.');
        $I->login($I);
        $I->amOnPage("/manager/{$project->slug()}");
        $I->seeInDatabase('projects', [
            'id'         => $project->id(),
            'deleted_at' => null,
        ]);
        $I->click('#removeProject');
        $I->click('Remove Project');
        $I->wait(1);
        $I->seeInDatabase('projects', ['id' => $project->id()]);
        $I->dontseeInDatabase('projects', [
            'id'         => $project['id'],
            'deleted_at' => null,
        ]);
        $I->see('Project removed from portfolio');
    }

    public function user_can_update_a_project(AcceptanceTester $I)
    {
        $data = [
            'name'        => 'updated name',
            'link'        => 'updated link',
            'projectType' => 'updated type',
            'name0'       => 'updatedName0',
            'text0'       => 'updated0',
            'name1'       => 'updatedName1',
            'text1'       => 'updated1',
            'name2'       => 'updatedName2',
            'text2'       => 'updated2',
        ];

        $project = $I->getProject($I);
        $I->wantTo('Update a project in the portfolio.');
        $I->login($I);
        $I->amOnPage("/manager/{$project->slug()}/edit");
        $I->fillForm($I, $data);
        $I->click('Update Project');
        $I->wait(1);
        $I->seeInDatabase('projects', [
            'id'   => $project['id'],
            'name' => $data['name'],
            'link' => $data['link'],
            'type' => $data['projectType'],
        ]);
        $I->seeInDatabase('text_blocks', [
            'name' => $data['name0'],
            'text' => $data['text0'],
        ]);
        $I->seeInDatabase('text_blocks', [
            'name' => $data['name1'],
            'text' => $data['text1'],
        ]);
        $I->seeInDatabase('text_blocks', [
            'name' => $data['name2'],
            'text' => $data['text2'],
        ]);
        $I->seeCurrentUrlEquals('/manager/updated_name/edit');
        $I->see('Project successfully updated');
    }

    /**
     * @test
     */
    public function user_can_add_and_delete_blocks_when_editing(AcceptanceTester $I)
    {
        $project = $I->getProject($I);
        $blocks = $I->getProjectBlocks($project);

        $I->wantTo('Add and delete text blocks from a project when editing.');
        $I->login($I);
        $I->amOnPage("/manager/{$project->slug()}/edit");
        $I->click('+');
        $I->fillField('text3', 'block4');
        $I->removeBlock($I, '#delete2');
        $I->removeBlock($I, '#delete1');
        $I->removeBlock($I, '#delete0');
        $I->click('+');
        $I->fillField('text1', 'block5');
        $I->click('Update Project');
        $I->wait(1);
        $I->amOnPage("/manager/{$project->slug()}");
        $I->see('block4');
        $I->see('block5');
        $I->dontSee($blocks[0]->name());
        $I->dontSee($blocks[1]->name());
        $I->dontSee($blocks[2]->name());
    }

    public function user_can_move_block_up_while_editing(AcceptanceTester $I)
    {
        $project = $I->getProject($I);
        $data = [
            'name0' => 'updatedName0',
            'name1' => 'updatedName1',
            'name2' => 'updatedName2',
        ];

        $I->wantTo('Move a block up while editing a project.');
        $I->login($I);
        $I->amOnPage("/manager/{$project->slug()}/edit");
        $I->wait(1);
        $I->fillForm($I, $data);
        $I->click('Update Project');
        $I->wait(1);
        $I->click('#up2');
        $I->wait(1);
        $I->click('#up1');
        $I->wait(1);
        $I->removeBlock($I, '#delete2');
        $I->removeBlock($I, '#delete1');
        $I->click('Update Project');
        $I->wait(1);
        $I->amOnPage("/manager/{$project->slug()}");
        $I->see($data['name2']);
        $I->dontSee($data['name0']);
        $I->dontSee($data['name1']);
    }

    public function user_can_move_block_down_while_editing(AcceptanceTester $I)
    {
        $project = $I->getProject($I);
        $data = [
            'name0' => 'updatedName0',
            'name1' => 'updatedName1',
            'name2' => 'updatedName2',
        ];

        $I->wantTo('Move a block down while editing a project.');
        $I->login($I);
        $I->amOnPage("/manager/{$project->slug()}/edit");
        $I->wait(1);
        $I->fillForm($I, $data);
        $I->click('Update Project');
        $I->wait(1);
        $I->click('#down0');
        $I->wait(1);
        $I->click('#down1');
        $I->wait(1);
        $I->removeBlock($I, '#delete1');
        $I->removeBlock($I, '#delete0');
        $I->click('Update Project');
        $I->wait(1);
        $I->amOnPage("/manager/{$project->slug()}");
        $I->see($data['name0']);
        $I->dontSee($data['name2']);
        $I->dontSee($data['name1']);
    }

    public function order_is_remembered_when_moving_blocks(AcceptanceTester $I)
    {
        $project = $I->getProject($I);
        $data = [
            'name0' => 'updatedName0',
            'name1' => 'updatedName1',
            'name2' => 'updatedName2',
        ];

        $I->wantTo('Change the order of blocks and have that order remembered.');
        $I->login($I);
        $I->amOnPage("/manager/{$project->slug()}/edit");
        $I->wait(1);
        $I->fillForm($I, $data);
        $I->click('Update Project');
        $I->wait(1);
        $I->click('#down0');
        $I->wait(1);
        $I->click('#up2');
        $I->wait(1);
        $I->click('Update Project');
        $I->wait(1);
        $I->amOnPage("/manager/{$project->slug()}");
        $I->click('Edit Project');
        $I->removeBlock($I, '#delete2');
        $I->removeBlock($I, '#delete0');
        $I->wait(1);
        $I->amOnPage("/manager/{$project->slug()}");
        $I->see($data['name2']);
        $I->dontSee($data['name0']);
        $I->dontSee($data['name1']);
    }

    public function update_button_disabled_until_name_changed(AcceptanceTester $I)
    {
        $project = $I->getProject($I);

        $I->wantTo('Be able to update a project if the name was changed.');
        $I->login($I);
        $I->amOnPage("/manager/{$project->slug()}/edit");
        $I->seeElement('.button--green', ['disabled' => 'true']);
        $I->fillField(['name' => 'name'], 'abc');
        $I->dontSeeElement('.button--green', ['disabled' => 'true']);
    }

    public function update_button_disabled_until_link_changed(AcceptanceTester $I)
    {
        $project = $I->getProject($I);

        $I->wantTo('Be able to update a project if the link was changed.');
        $I->login($I);
        $I->amOnPage("/manager/{$project->slug()}/edit");
        $I->seeElement('.button--green', ['disabled' => 'true']);
        $I->fillField(['name' => 'link'], 'abc');
        $I->dontSeeElement('.button--green', ['disabled' => 'true']);
    }

    public function update_button_disabled_until_type_changed(AcceptanceTester $I)
    {
        $project = $I->getProject($I);

        $I->wantTo('Be able to update a project if the type was changed.');
        $I->login($I);
        $I->amOnPage("/manager/{$project->slug()}/edit");
        $I->seeElement('.button--green', ['disabled' => 'true']);
        $I->fillField(['name' => 'projectType'], 'abc');
        $I->dontSeeElement('.button--green', ['disabled' => 'true']);
    }

    public function update_button_disabled_until_block_changed(AcceptanceTester $I)
    {
        $project = $I->getProject($I);

        $I->wantTo('Be able to update a project if a block was changed.');
        $I->login($I);
        $I->amOnPage("/manager/{$project->slug()}/edit");
        $I->seeElement('.button--green', ['disabled' => 'true']);
        $I->fillField(['name' => 'text0'], 'abc');
        $I->dontSeeElement('.button--green', ['disabled' => 'true']);
    }

    public function update_button_disabled_until_block_moved_up(AcceptanceTester $I)
    {
        $project = $I->getProject($I);

        $I->wantTo('Be able to update a project if a block was moved up.');
        $I->login($I);
        $I->amOnPage("/manager/{$project->slug()}/edit");
        $I->seeElement('.button--green', ['disabled' => 'true']);
        $I->click('#up2');
        $I->dontSeeElement('.button--green', ['disabled' => 'true']);
    }

    public function update_button_disabled_until_block_moved_down(AcceptanceTester $I)
    {
        $project = $I->getProject($I);

        $I->wantTo('Be able to update a project if a block was moved down.');
        $I->login($I);
        $I->amOnPage("/manager/{$project->slug()}/edit");
        $I->seeElement('.button--green', ['disabled' => 'true']);
        $I->click('#down1');
        $I->dontSeeElement('.button--green', ['disabled' => 'true']);
    }

    public function block_with_name_description_is_shown_on_dashboard(AcceptanceTester $I)
    {
        $project = $I->getProject($I);

        $block = $project->blocks->last();

        $I->wantTo('Set a block as the description and see it on the dashboard.');
        $I->login($I);
        $I->amOnPage("/manager/{$project->slug()}/edit");
        $I->fillField(['name' => 'name0'], 'dfsd');
        $I->click('Update Project');
        $I->wait(1);

        $block = TextBlock::find($block->id());

        $I->fillField(['name' => 'name'.$block->order()], 'description');
        $I->click('Update Project');
        $I->wait(1);
        $I->amOnPage('/manager');
        $I->see($block->text());
    }
}
