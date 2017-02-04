<?php

use Larafolio\Models\Project;

class MobileCest
{
    public function _before(\AcceptanceTester $I)
    {
        $I->migrate();
    }

    // **MENU** //
    public function mobile_menu_opens_and_closes(AcceptanceTester $I)
    {
        $I->wantTo('Access the menu on mobile.');
        $I->login($I, 'mobile');
        $I->dontSee('Settings');
        $I->click('#openMenu');
        $I->wait(1);
        $I->see('Settings');
        $I->click('#closeMenu');
        $I->wait(1);
        $I->dontSee('Settings');
        $I->click('#openMenu');
        $I->wait(1);
        $I->click('Dashboard');
        $I->seeCurrentUrlEquals('/manager');
    }

    // **DASHBOARD** //
    public function project_manager_screen_can_be_accessed_from_dashboard(AcceptanceTester $I)
    {
        $project = $I->getProject($I);

        $I->wantTo('Access the project manager page on mobile.');
        $I->login($I, 'mobile');
        $I->click('#manage'.$project->id());
        $I->seeCurrentUrlEquals('/manager/projects/'.$project->slug());
    }

    public function project_order_can_be_changed(AcceptanceTester $I)
    {
        $I->wantTo('Change the project order on mobile.');
        $I->login($I, 'mobile');
        $project = Project::all()->sortBy('order')->last();
        foreach (range(0, 4) as $time) {
            $I->click('#up'.$project->id());
        }
        $I->amOnPage('/manager');
        $I->seeInDatabase('projects', [
            'id'    => $project->id(),
            'order' => 0,
        ]);
        $project = Project::all()->sortBy('order')->first();
        foreach (range(0, 4) as $time) {
            $I->click('#down'.$project->id());
            $I->wait(1);
        }
        $I->seeInDatabase('projects', [
            'id'    => $project->id(),
            'order' => 4,
        ]);
    }

    public function project_visibility_can_be_toggled_from_dashboard_on_mobile(AcceptanceTester $I)
    {
        $project = $I->getProject($I);

        $I->wantTo('Toggle project visibility from the dashboard.');
        $I->login($I, 'mobile');
        $I->click('#makeVisible'.$project->id());
        $I->wait(1);
        $I->see('Resource Visible');
        $I->seeInDatabase('projects', [
            'id'      => $project->id(),
            'visible' => true,
        ]);
        $I->click('#makeHidden'.$project->id());
        $I->wait(1);
        $I->see('Resource Hidden');
        $I->seeInDatabase('projects', [
            'id'      => $project->id(),
            'visible' => false,
        ]);
    }

    // **PROJECT** //
    public function user_can_add_a_project_from_mobile(AcceptanceTester $I)
    {
        $data = [
            'name'        => 'Project Name',
            'projectType' => 'Project type',
            'text0'       => 'Project description',
        ];

        $I->wantTo('Add a new project to the portfolio on mobile.');
        $I->login($I, 'mobile');
        $I->amOnAddPage($I);
        $I->fillForm($I, $data);
        $I->click('Add Project');
        $I->wait(1);
        $I->seeCurrentUrlEquals('/manager/projects/project_name');
        $I->see('Project successfully added');
        $I->seeInDatabase('projects', ['name' => $data['name']]);
        $I->seeInDatabase('text_blocks', ['text' => $data['text0']]);
    }

    public function user_can_update_project_on_mobile(AcceptanceTester $I)
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
        $I->wantTo('Update a project in the portfolio on mobile.');
        $I->login($I, 'mobile');
        $I->amOnPage("/manager/projects/{$project->slug()}/edit");
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
        $I->seeCurrentUrlEquals('/manager/projects/updated_name/edit');
        $I->see('Project successfully updated');
    }

    public function project_visibility_can_be_toggled_from_project_screen_on_mobile(AcceptanceTester $I)
    {
        $project = $I->getProject($I);
        $I->wantTo('Toggle the visibility of a project from the project screen on mobile.');
        $I->login($I, 'mobile');
        $I->amOnPage("/manager/projects/{$project->slug()}");
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
        $I->amOnPage("/manager/projects/{$project->slug()}");
        $I->click('#makeHidden');
        $I->see('Hidden');
        $I->see($project->name().' is not publicly viewable');
        $I->dontSee('Visible');
        $I->seeInDatabase('projects', [
            'id'      => $project->id(),
            'visible' => false,
        ]);
    }

    public function project_can_be_removed_from_mobile(AcceptanceTester $I)
    {
        $project = $I->getProject($I);
        $I->wantTo('Remove a project from the portfolio on mobile.');
        $I->login($I, 'mobile');
        $I->amOnPage("/manager/projects/{$project->slug()}");
        $I->seeInDatabase('projects', [
            'id'         => $project->id(),
            'deleted_at' => null,
        ]);
        $I->click('#removeResource');
        $I->click('Remove');
        $I->wait(1);
        $I->seeInDatabase('projects', ['id' => $project->id()]);
        $I->dontseeInDatabase('projects', [
            'id'         => $project['id'],
            'deleted_at' => null,
        ]);
        $I->see("{$project->name()} removed from portfolio");
    }

    // **PROJECT IMAGES** //
    public function user_can_add_project_images_on_mobile(AcceptanceTester $I)
    {
        $project = $I->getProject($I);
        $I->wantTo('Add an image to a project on mobile.');
        $I->login($I, 'mobile');
        $I->amOnPage("/manager/projects/{$project->slug()}/images");
        $I->waitForElement('.dz-hidden-input');
        $I->attachFile('.dz-hidden-input', 'new.jpg');
        $I->wait(1);
        $I->seeInDatabase('images', ['path' => 'public/images/b9dc5a3f4e80d23072fdd43fd23ea635.jpeg']);
        $I->see('Image added to portfolio');
    }

    public function user_can_update_project_image_info_on_mobile(AcceptanceTester $I)
    {
        $project = $I->getProject($I);
        $image = $I->getImageFromResourceArray($project);
        $id = $image->id();

        $data = [
            'name'.$id    => 'image name',
            'caption'.$id => 'image caption',
            'alt'.$id     => 'image alt',
        ];

        $I->wantTo('Update project image information on mobile.');
        $I->login($I, 'mobile');
        $I->amOnPage("/manager/projects/{$project->slug()}/images");
        $I->fillForm($I, $data);
        $I->click('#button'.$id);
        $I->wait(1);
        $I->seeInDatabase('images', [
            'path'    => $image->path(),
            'name'    => 'image name',
            'caption' => 'image caption',
            'alt'     => 'image alt',
        ]);
        $I->see('Image information updated');
    }

    public function user_can_remove_project_image_from_mobile(AcceptanceTester $I)
    {
        $project = $I->getProject($I);
        $image = $I->getImageFromResourceArray($project);
        $id = $image->id();

        $I->wantTo('Remove an image from a project on mobile.');
        $I->login($I, 'mobile');
        $I->seeInDatabase('images', ['path' => $image->path()]);
        $I->amOnPage("/manager/projects/{$project->slug()}/images");
        $I->click('#remove'.$id);
        $I->wait(1);
        $I->click('Remove Image');
        $I->wait(1);
        $I->dontSeeInDatabase('images', ['path' => $image->path()]);
        $I->see('Image removed from portfolio');
    }

    // **PAGE IMAGES** //
    public function user_can_add_page_images_on_mobile(AcceptanceTester $I)
    {
        $page = $I->getPage($I);
        $I->wantTo('Add an image to a page on mobile.');
        $I->login($I, 'mobile');
        $I->amOnPage("/manager/pages/{$page->slug()}/images");
        $I->waitForElement('.dz-hidden-input');
        $I->attachFile('.dz-hidden-input', 'new.jpg');
        $I->wait(1);
        $I->seeInDatabase('images', ['path' => 'public/images/b9dc5a3f4e80d23072fdd43fd23ea635.jpeg']);
        $I->see('Image added to portfolio');
    }

    public function user_can_update_page_image_info_on_mobile(AcceptanceTester $I)
    {
        $page = $I->getPage($I);
        $image = $I->getImageFromResourceArray($page);
        $id = $image->id();

        $data = [
            'name'.$id    => 'image name',
            'caption'.$id => 'image caption',
            'alt'.$id     => 'image alt',
        ];

        $I->wantTo('Update page image information on mobile.');
        $I->login($I, 'mobile');
        $I->amOnPage("/manager/pages/{$page->slug()}/images");
        $I->fillForm($I, $data);
        $I->click('#button'.$id);
        $I->wait(1);
        $I->seeInDatabase('images', [
            'path'    => $image->path(),
            'name'    => 'image name',
            'caption' => 'image caption',
            'alt'     => 'image alt',
        ]);
        $I->see('Image information updated');
    }

    public function user_can_remove_page_image_from_mobile(AcceptanceTester $I)
    {
        $page = $I->getPage($I);
        $image = $I->getImageFromResourceArray($page);
        $id = $image->id();

        $I->wantTo('Remove an image from a page on mobile.');
        $I->login($I, 'mobile');
        $I->seeInDatabase('images', ['path' => $image->path()]);
        $I->amOnPage("/manager/pages/{$page->slug()}/images");
        $I->click('#remove'.$id);
        $I->wait(1);
        $I->click('Remove Image');
        $I->wait(1);
        $I->dontSeeInDatabase('images', ['path' => $image->path()]);
        $I->see('Image removed from portfolio');
    }

    // **TEXT BLOCKS** //
    public function text_block_order_can_be_changed_on_mobile(AcceptanceTester $I)
    {
        $project = $I->getProject($I);
        $data = [
            'name0' => 'updatedName0',
            'name1' => 'updatedName1',
            'name2' => 'updatedName2',
        ];

        $I->wantTo('Change textblock order on mobile.');
        $I->login($I, 'mobile');
        $I->amOnPage("/manager/projects/{$project->slug()}/edit");
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
        $I->amOnPage("/manager/projects/{$project->slug()}");
        $I->click('Edit Project');
        $I->removeBlock($I, '#delete2');
        $I->removeBlock($I, '#delete0');
        $I->wait(1);
        $I->amOnPage("/manager/projects/{$project->slug()}");
        $I->see($data['name2']);
        $I->dontSee($data['name0']);
        $I->dontSee($data['name1']);
    }

    public function text_blocks_can_be_removed_on_mobile(AcceptanceTester $I)
    {
        $data = [
            'text0' => 'Project description',
            'text1' => 'Project info',
        ];

        $I->wantTo('Remove a text block from a project on mobile.');
        $I->login($I, 'mobile');
        $I->amOnAddPage($I);
        $I->click('#addBlock');
        $I->fillForm($I, $data);
        $I->seeElement('#text0');
        $I->seeElement('#text1');
        $I->removeBlock($I, '#delete1');
        $I->dontSeeElement('#text1');
        $I->click('Add Project');
    }

    // **LINKS** //
    public function link_order_can_be_changed_on_mobile(AcceptanceTester $I)
    {
        $project = $I->getProject($I);
        $data = [
            'url0' => 'updatedUrl0',
            'url1' => 'updatedUrl1',
            'url2' => 'updatedUrl2',
        ];

        $I->wantTo('Change the order of links on mobile.');
        $I->login($I, 'mobile');
        $I->amOnPage("/manager/projects/{$project->slug()}/edit");
        $I->wait(1);
        $I->fillForm($I, $data);
        $I->click('Update Project');
        $I->wait(1);
        $I->click('#downLink0');
        $I->wait(1);
        $I->click('#upLink2');
        $I->wait(1);
        $I->click('Update Project');
        $I->wait(1);
        $I->amOnPage("/manager/projects/{$project->slug()}");
        $I->click('Edit Project');
        $I->removeLink($I, '#deleteLink2');
        $I->removeLink($I, '#deleteLink0');
        $I->wait(1);
        $I->amOnPage("/manager/projects/{$project->slug()}");
        $I->see($data['url2']);
        $I->dontSee($data['url0']);
        $I->dontSee($data['url1']);
    }

    public function link_can_be_removed_on_mobile(AcceptanceTester $I)
    {
        $data = [
            'linkName0' => 'Link name 0',
            'linkText0' => 'Link text0',
            'url0'      => 'Link url 0',
            'linkName1' => 'Link name 1',
            'linkText1' => 'Link text1',
            'url1'      => 'Link url 1',
        ];

        $I->wantTo('Remove a link from a project on mobile.');
        $I->login($I, 'mobile');
        $I->amOnAddPage($I);
        $I->click('#addLink');
        $I->fillForm($I, $data);
        $I->seeElement('#linkText1');
        $I->removeLink($I, '#deleteLink1');
        $I->dontSeeElement('#linkText1');
        $I->click('Add Project');
    }
}
