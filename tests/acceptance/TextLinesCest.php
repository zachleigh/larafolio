<?php

class TextLinesCest
{
    public function _before(\AcceptanceTester $I)
    {
        $I->migrate();
    }

    public function user_can_add_line_to_project(AcceptanceTester $I)
    {
        $data = [
            'name'      => 'Project Name',
            'lineName0' => 'Line name',
            'lineText0' => 'Line text',
            'text0'     => 'Project description'
        ];

        $I->wantTo('Save a line when a new project is added.');
        $I->login($I);
        $I->amOnAddPage($I);
        $I->fillForm($I, $data);
        $I->click('Add Project');
        $I->wait(1);
        $I->seeCurrentUrlEquals('/manager/projects/project_name');
        $I->seeInDatabase('projects', ['name' => $data['name']]);
        $I->seeInDatabase('text_lines', [
            'name' => $data['lineName0'],
            'text' => $data['lineText0'],
        ]);
    }

    public function user_can_update_a_line(AcceptanceTester $I)
    {
        $data = [
            'lineName0' => 'updated name0',
            'lineText0' => 'Line text0',
        ];

        $project = $I->getProject($I);
        $I->wantTo('Update a project line.');
        $I->login($I);
        $I->amOnPage("/manager/projects/{$project->slug()}/edit");
        $I->fillForm($I, $data);
        $I->click('Update Project');
        $I->wait(1);
        $I->seeInDatabase('text_lines', [
            'resource_id' => $project['id'],
            'name'       => $data['lineName0'],
            'text'       => $data['lineText0'],
        ]);
    }

    public function user_can_remove_line_from_project(AcceptanceTester $I)
    {
        $data = [
            'lineName0' => 'Line name 0',
            'lineText0' => 'Line text0',
            'lineName1' => 'Line name1',
            'lineText1' => 'Line text1',
        ];

        $I->wantTo('Remove a line from a project being added.');
        $I->login($I);
        $I->amOnAddPage($I);
        $I->click('#addLine');
        $I->fillForm($I, $data);
        $I->see('Line text1');
        $I->removeLine($I, '#deleteLine1');
        $I->dontSee('Line text1');
        $I->click('Add Project');
    }

    public function user_can_add_and_remove_lines_like_crazy(AcceptanceTester $I)
    {
        $I->wantTo('Add and remove a bunch of random lines.');
        $I->login($I);
        $I->amOnAddPage($I);
        // fill 0
        $I->fillField(['name' => 'lineText0'], 'lineText0');
        $I->see('lineText0');
        // add and fill 1
        $I->click('#addLine');
        $I->fillField(['name' => 'lineText1'], 'lineText1');
        $I->see('lineText1');
        // add and fill 2
        $I->click('#addLine');
        $I->fillField(['name' => 'lineText2'], 'lineText2');
        $I->see('lineText2');
        // delete 1
        $I->removeLine($I, '#deleteLine1');
        $I->dontSee('lineText1');
        // add and fill 3
        $I->click('#addLine');
        $I->fillField(['name' => 'lineText2'], 'lineText3');
        $I->see('lineText3');
        // delete 0
        $I->removeLine($I, '#deleteLine0');
        $I->dontSee('lineText0');
        // // add and fill 4
        $I->click('#addLine');
        $I->fillField(['name' => 'lineText2'], 'lineText4');
        $I->see('lineText4');
        // // add and fill 5
        $I->click('#addLine');
        $I->fillField(['name' => 'lineText3'], 'lineText5');
        $I->see('lineText5');
        // // delete 5
        $I->removeLine($I, '#deleteLine3');
        $I->dontSee('lineText5');
        // // add and fill 6
        $I->click('#addLine');
        $I->fillField(['name' => 'lineText3'], 'lineText6');
        $I->see('lineText6');
        $I->see('lineText2');
        $I->see('lineText3');
        $I->see('lineText4');
        $I->click('Add Project');
    }

    public function user_can_move_line_up(AcceptanceTester $I)
    {
        $data = [
            'lineText0' => 'line0',
            'lineText1' => 'line1',
            'lineText2' => 'line2',
        ];

        $I->wantTo('Move a line up.');
        $I->login($I);
        $I->amOnAddPage($I);
        $I->click('#addLine');
        $I->click('#addLine');
        $I->fillForm($I, $data);
        $I->click('#upLine2');
        $I->click('#upLine1');
        $I->removeLine($I, '#deleteLine2');
        $I->removeLine($I, '#deleteLine1');
        $I->see('line2');
        $I->dontSee('line0');
        $I->dontSee('line1');
        $I->click('Add Project');
    }

    public function user_can_move_line_down(AcceptanceTester $I)
    {
        $data = [
            'lineText0' => 'line0',
            'lineText1' => 'line1',
            'lineText2' => 'line2',
        ];

        $I->wantTo('Move a line down.');
        $I->login($I);
        $I->amOnAddPage($I);
        $I->click('#addLine');
        $I->click('#addLine');
        $I->fillForm($I, $data);
        $I->click('#downLine0');
        $I->click('#downLine1');
        $I->removeLine($I, '#deleteLine1');
        $I->removeLine($I, '#deleteLine0');
        $I->see('line0');
        $I->dontSee('line1');
        $I->dontSee('line2');
        $I->click('Add Project');
    }

    public function user_can_add_and_delete_lines_when_editing(AcceptanceTester $I)
    {
        $project = $I->getProject($I);
        $lines = $project->lines;

        $I->wantTo('Add and delete lines from a project when editing.');
        $I->login($I);
        $I->amOnPage("/manager/projects/{$project->slug()}/edit");
        $I->click('#addLine');
        $I->fillField('lineText5', 'lineText5');
        $I->removeLine($I, '#deleteLine2');
        $I->removeLine($I, '#deleteLine1');
        $I->removeLine($I, '#deleteLine0');
        $I->click('#addLine');
        $I->fillField('lineText3', 'lineText6');
        $I->removeLine($I, '#deleteLine0');
        $I->wait(1);
        $I->click('Update Project');
        $I->wait(1);
        $I->amOnPage("/manager/projects/{$project->slug()}");
        $I->see('lineText5');
        $I->see('lineText6');
        $I->dontSee($lines[0]->text());
        $I->dontSee($lines[1]->text());
        $I->dontSee($lines[2]->text());
    }

    public function user_can_move_line_up_while_editing(AcceptanceTester $I)
    {
        $project = $I->getProject($I);
        $data = [
            'lineText0' => 'updatedlineText0',
            'lineText1' => 'updatedlineText1',
            'lineText2' => 'updatedlineText2',
        ];

        $I->wantTo('Move a line up while editing a project.');
        $I->login($I);
        $I->amOnPage("/manager/projects/{$project->slug()}/edit");
        $I->wait(1);
        $I->fillForm($I, $data);
        $I->click('Update Project');
        $I->wait(1);
        $I->click('#upLine2');
        $I->wait(1);
        $I->click('#upLine1');
        $I->wait(1);
        $I->removeLine($I, '#deleteLine2');
        $I->removeLine($I, '#deleteLine1');
        $I->click('Update Project');
        $I->wait(1);
        $I->amOnPage("/manager/projects/{$project->slug()}");
        $I->see($data['lineText2']);
        $I->dontSee($data['lineText0']);
        $I->dontSee($data['lineText1']);
    }

    public function user_can_move_line_down_while_editing(AcceptanceTester $I)
    {
        $project = $I->getProject($I);
        $data = [
            'lineText0' => 'updatedlineText0',
            'lineText1' => 'updatedlineText1',
            'lineText2' => 'updatedlineText2',
        ];

        $I->wantTo('Move a line down while editing a project.');
        $I->login($I);
        $I->amOnPage("/manager/projects/{$project->slug()}/edit");
        $I->wait(1);
        $I->fillForm($I, $data);
        $I->click('Update Project');
        $I->wait(1);
        $I->click('#downLine0');
        $I->wait(1);
        $I->click('#downLine1');
        $I->wait(1);
        $I->removeLine($I, '#deleteLine1');
        $I->removeLine($I, '#deleteLine0');
        $I->click('Update Project');
        $I->wait(1);
        $I->amOnPage("/manager/projects/{$project->slug()}");
        $I->see($data['lineText0']);
        $I->dontSee($data['lineText2']);
        $I->dontSee($data['lineText1']);
    }

    public function order_is_remembered_when_moving_lines(AcceptanceTester $I)
    {
        $project = $I->getProject($I);
        $data = [
            'lineText0' => 'updatedlineText0',
            'lineText1' => 'updatedlineText1',
            'lineText2' => 'updatedlineText2',
        ];

        $I->wantTo('Change the order of lines and have that order remembered.');
        $I->login($I);
        $I->amOnPage("/manager/projects/{$project->slug()}/edit");
        $I->wait(1);
        $I->fillForm($I, $data);
        $I->click('Update Project');
        $I->wait(1);
        $I->click('#downLine0');
        $I->wait(1);
        $I->click('#upLine2');
        $I->wait(1);
        $I->click('Update Project');
        $I->wait(1);
        $I->amOnPage("/manager/projects/{$project->slug()}");
        $I->click('Edit Project');
        $I->removeLine($I, '#deleteLine2');
        $I->removeLine($I, '#deleteLine0');
        $I->wait(1);
        $I->amOnPage("/manager/projects/{$project->slug()}");
        $I->see($data['lineText2']);
        $I->dontSee($data['lineText0']);
        $I->dontSee($data['lineText1']);
    }
}
