<?php

class ImagesCest
{
    public function _before(\AcceptanceTester $I)
    {
        $I->migrate();
    }

    public function user_can_add_image(AcceptanceTester $I)
    {
        $project = $I->getProject($I);
        $I->wantTo('Add an image to a project.');
        $I->login($I);
        $I->addImage($I, $project);
        $I->seeInDatabase('images', ['path' => 'public/images/b9dc5a3f4e80d23072fdd43fd23ea635.jpeg']);
        $I->see('Image added to project');
    }

    public function user_can_update_image_name_caption_and_alt(AcceptanceTester $I)
    {
        $project = $I->getProject($I);
        $image = $I->getImageFromProjectArray($project);
        $id = $image->id();

        $data = [
            'name'.$id    => 'image name',
            'caption'.$id => 'image caption',
            'alt'.$id     => 'image alt',
        ];

        $I->wantTo('Add a name, caption and alt for an image.');
        $I->login($I);
        $I->amOnProjectPage($I, $project);
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

    public function user_can_remove_an_image(AcceptanceTester $I)
    {
        $project = $I->getProject($I);
        $image = $I->getImageFromProjectArray($project);
        $id = $image->id();

        $I->wantTo('Remove an image from a project.');
        $I->login($I);
        $I->seeInDatabase('images', ['path' => $image->path()]);
        $I->amOnProjectPage($I, $project);
        $I->click('#remove'.$id);
        $I->wait(1);
        $I->click('Remove Image');
        $I->wait(1);
        $I->dontSeeInDatabase('images', ['path' => $image->path()]);
        $I->see('Image removed from project');
    }

    public function update_button_disabled_until_name_changed(AcceptanceTester $I)
    {
        $project = $I->getProject($I);

        $image = $I->getImageFromProjectArray($project);

        $id = $image->id();

        $I->wantTo('Be able to update an image if the name has changed.');
        $I->login($I);
        $I->amOnProjectPage($I, $project);
        $I->seeElement('#button'.$id, ['disabled' => 'true']);
        $I->fillField(['name' => 'name'.$id], 'abc');
        $I->dontSeeElement('#button'.$id, ['disabled' => 'true']);
    }

    public function update_button_disabled_until_alt_changed(AcceptanceTester $I)
    {
        $project = $I->getProject($I);

        $image = $I->getImageFromProjectArray($project);

        $id = $image->id();

        $I->wantTo('Be able to update an image if the alt text has changed.');
        $I->login($I);
        $I->amOnProjectPage($I, $project);
        $I->seeElement('#button'.$id, ['disabled' => 'true']);
        $I->fillField(['name' => 'alt'.$id], 'abc');
        $I->dontSeeElement('#button'.$id, ['disabled' => 'true']);
    }

    public function update_button_disabled_until_caption_changed(AcceptanceTester $I)
    {
        $project = $I->getProject($I);

        $image = $I->getImageFromProjectArray($project);

        $id = $image->id();

        $I->wantTo('Be able to update an image if the caption has changed.');
        $I->login($I);
        $I->amOnProjectPage($I, $project);
        $I->seeElement('#button'.$id, ['disabled' => 'true']);
        $I->fillField(['name' => 'caption'.$id], 'abc');
        $I->dontSeeElement('#button'.$id, ['disabled' => 'true']);
    }

    public function image_with_project_name_is_default_image(AcceptanceTester $I)
    {
        $project = $I->getProject($I);

        $image = $project->images->last();

        $id = $image->id();

        $I->wantTo('Set the default project image by saving the project name as its name.');
        $I->login($I);
        $I->amOnProjectPage($I, $project);
        $I->fillField(['name' => 'name'.$id], $project->name());
        $I->click('#button'.$id);
        $I->wait(1);
        $I->amOnPage('/manager');
        $I->seeElement('//img[@src="'.$image->small().'"]');
    }

    public function message_displayed_when_project_has_no_images(AcceptanceTester $I)
    {
        $I->wantTo('Verify that a message is displayed when no project has no images.');
        $I->login($I);
        $project = $I->addProject($I);
        $I->amOnProjectPage($I, $project);
        $I->see('This project has no images');
    }

    public function no_images_message_is_hidden_when_image_added(AcceptanceTester $I)
    {
        $I->wantTo('Verify that the no images message is hidden when a image is added.');
        $I->login($I);
        $project = $I->addProject($I);
        $I->amOnProjectPage($I, $project);
        $I->see('This project has no images');
        $I->addImage($I, $project);
        $I->dontSee('This project has no images');
    }
}
