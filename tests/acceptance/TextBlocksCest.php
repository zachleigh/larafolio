<?php

use Larafolio\Models\TextBlock;

class TextBlocksCest
{
    public function _before(\AcceptanceTester $I)
    {
        $I->migrate();
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
            'text0' => 'Project description',
            'text1' => 'Project info',
        ];

        $I->wantTo('Add a new text block to a project being added.');
        $I->login($I);
        $I->amOnAddPage($I);
        $I->click('#addBlock');
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
        $I->click('#addBlock');
        $I->fillForm($I, $data);
        $I->see('Project description');
        $I->see('Project info');
        $I->removeBlock($I, '#delete1');
        $I->dontSee('Project info');
    }

    public function user_can_add_and_remove_blocks_like_crazy(AcceptanceTester $I)
    {
        $I->wantTo('Add and remove a bunch of random text blocks.');
        $I->login($I);
        $I->amOnAddPage($I);
        // fill 0
        $I->fillField(['name' => 'text0'], 'block0');
        $I->see('block0');
        // add and fill 1
        $I->click('#addBlock');
        $I->fillField(['name' => 'text1'], 'block1');
        $I->see('block1');
        // add and fill 2
        $I->click('#addBlock');
        $I->fillField(['name' => 'text2'], 'block2');
        $I->see('block2');
        // delete 1
        $I->removeBlock($I, '#delete1');
        $I->dontSee('block1');
        // add and fill 3
        $I->click('#addBlock');
        $I->fillField(['name' => 'text2'], 'block3');
        $I->see('block3');
        // delete 0
        $I->removeBlock($I, '#delete0');
        $I->dontSee('block0');
        // add and fill 4
        $I->click('#addBlock');
        $I->fillField(['name' => 'text2'], 'block4');
        $I->see('block4');
        // add and fill 5
        $I->click('#addBlock');
        $I->fillField(['name' => 'text3'], 'block5');
        $I->see('block5');
        // delete 5
        $I->removeBlock($I, '#delete3');
        $I->dontSee('block5');
        // add and fill 6
        $I->click('#addBlock');
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
        $I->click('#addBlock');
        $I->click('#addBlock');
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
        $I->click('#addBlock');
        $I->click('#addBlock');
        $I->fillForm($I, $data);
        $I->click('#down0');
        $I->click('#down1');
        $I->removeBlock($I, '#delete1');
        $I->removeBlock($I, '#delete0');
        $I->see('block0');
        $I->dontSee('block1');
        $I->dontSee('block2');
    }

    public function user_can_add_and_delete_blocks_when_editing(AcceptanceTester $I)
    {
        $project = $I->getProject($I);
        $blocks = $I->getProjectBlocks($project);

        $I->wantTo('Add and delete text blocks from a project when editing.');
        $I->login($I);
        $I->amOnPage("/manager/{$project->slug()}/edit");
        $I->click('#addBlock');
        $I->fillField('text3', 'block4');
        $I->removeBlock($I, '#delete2');
        $I->removeBlock($I, '#delete1');
        $I->removeBlock($I, '#delete0');
        $I->click('#addBlock');
        $I->fillField('text1', 'block5');
        $I->wait(1);
        $I->click('Update Project');
        $I->wait(1);
        $I->amOnPage("/manager/{$project->slug()}");
        $I->see('block4');
        $I->see('block5');
        $I->dontSee($blocks[0]->text());
        $I->dontSee($blocks[1]->text());
        $I->dontSee($blocks[2]->text());
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
