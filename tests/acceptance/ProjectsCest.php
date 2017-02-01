<?php

use Larafolio\Models\TextBlock;

class ProjectsCest
{
    public function _before(\AcceptanceTester $I)
    {
        $I->migrate();
    }

    public function user_can_add_a_project(AcceptanceTester $I)
    {
        $data = [
            'name'        => 'Project Name',
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
        $I->see($project->name().' is now publicly viewable');
        $I->dontSee('Hidden');
        $I->seeInDatabase('projects', [
            'id'      => $project->id(),
            'visible' => true,
        ]);
        $I->amOnPage("/manager/{$project->slug()}");
        $I->click('#makeHidden');
        $I->see('Hidden');
        $I->see($project->name().' is not publicly viewable');
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

    public function update_button_disabled_until_name_changed(AcceptanceTester $I)
    {
        $project = $I->getProject($I);

        $I->wantTo('Be able to update a project if the name was changed.');
        $I->login($I);
        $I->amOnPage("/manager/{$project->slug()}/edit");
        $I->seeElement('.button--green', ['disabled' => 'true']);
        $I->fillField(['name' => 'name'], 'abc');
        $I->dontSeeElement('.button--green', ['disabled' => 'true']);
        $I->click('Update Project');
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
        $I->click('Update Project');
    }

    public function update_button_disabled_until_link_changed(AcceptanceTester $I)
    {
        $project = $I->getProject($I);

        $I->wantTo('Be able to update a project if a link was changed.');
        $I->login($I);
        $I->amOnPage("/manager/{$project->slug()}/edit");
        $I->seeElement('.button--green', ['disabled' => 'true']);
        $I->fillField(['name' => 'url0'], 'abc');
        $I->dontSeeElement('.button--green', ['disabled' => 'true']);
        $I->click('Update Project');
    }

    public function leaving_edit_page_when_unsaved_causes_popup(AcceptanceTester $I)
    {
        $data = ['text0' => 'Project description'];

        $I->wantTo('Verify that popup blocks page leave if form is unsaved.');
        $I->login($I);
        $I->amOnAddPage($I);
        $I->fillForm($I, $data);
        $I->amOnPage('/manager');
        $I->seeInPopup('Form contains unsaved content. Are you sure you want to leave?');
        $I->acceptPopup();
        $I->seeCurrentUrlEquals('/manager');
    }

    public function leaving_edit_page_when_unedited_does_not_cause_popup(AcceptanceTester $I)
    {
        $I->wantTo('Verify that popup does not occur if form is saved.');
        $I->login($I);
        $I->amOnAddPage($I);
        $I->amOnPage('/manager');
        $I->seeCurrentUrlEquals('/manager');
    }

    public function user_can_force_delete_projects(AcceptanceTester $I)
    {
        $project = $I->getProject($I);

        $I->wantTo('Force delete a project.');
        $I->login($I);
        $I->amOnPage("/manager/{$project->slug()}");
        $I->click('#removeProject');
        $I->click('Remove Project');
        $I->wait(1);
        $I->seeInDatabase('projects', [
            'id' => $project->id()
        ]);
        $I->amOnPage('/manager/settings/projects');
        $I->click('#delete'.$project->id());
        $I->wait(1);
        $I->click('#confirmDelete');
        $I->wait(1);
        $I->dontseeInDatabase('projects', [
            'id' => $project->id()
        ]);
    }

    public function user_can_restore_deleted_projects(AcceptanceTester $I)
    {
        $project = $I->getProject($I);

        $I->wantTo('Restore a deleted project.');
        $I->login($I);
        $I->amOnPage("/manager/{$project->slug()}");
        $I->click('#removeProject');
        $I->click('Remove Project');
        $I->wait(1);
        $I->dontSeeInDatabase('projects', [
            'id'         => $project->id(),
            'deleted_at' => null,
        ]);
        $I->amOnPage('/manager/settings/projects');
        $I->click('#restore'.$project->id());
        $I->wait(1);
        $I->seeInDatabase('projects', [
            'id' => $project->id(),
            'deleted_at' => null,
        ]);
    }

    public function restored_project_causes_nav_dropdown_refresh(AcceptanceTester $I)
    {
        $project = $I->getProject($I);

        $I->wantTo('Verify that when a project is restored, the nav menu refreshes.');
        $I->login($I);
        $I->amOnPage("/manager/{$project->slug()}");
        $I->click('#removeProject');
        $I->click('Remove Project');
        $I->wait(1);
        $I->amOnPage('/manager/settings/projects');
        $I->dontSeeInPageSource('<span class="nav__dropdown-item-text">
                '.$project->name().'
            </span>');
        $I->click('#restore'.$project->id());
        $I->wait(1);
        $I->seeInPageSource('<span class="nav__dropdown-item-text">
                '.$project->name().'
            </span>');
    }
}
