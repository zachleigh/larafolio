<?php

class PageImagesCest
{
    public function _before(\AcceptanceTester $I)
    {
        $I->migrate();
    }

    public function user_can_add_image_to_page(AcceptanceTester $I)
    {
        $page = $I->getPage($I);
        $I->wantTo('Add an image to a page.');
        $I->login($I);
        $I->addImageToPage($I, $page);
        $I->seeInDatabase('images', ['path' => 'public/images/b9dc5a3f4e80d23072fdd43fd23ea635.jpeg']);
        $I->see('Image added to portfolio');
    }

    public function user_can_update_page_image_name_caption_and_alt(AcceptanceTester $I)
    {
        $page = $I->getPage($I);
        $image = $I->getImageFromResourceArray($page);
        $id = $image->id;

        $data = [
            'name'.$id    => 'image name',
            'caption'.$id => 'image caption',
            'alt'.$id     => 'image alt',
        ];

        $I->wantTo('Add a name, caption and alt for a page image.');
        $I->login($I);
        $I->amOnPagePage($I, $page);
        $I->fillForm($I, $data);
        $I->click('#button'.$id);
        $I->wait(1);
        $I->seeInDatabase('images', [
            'path'    => $image->path,
            'name'    => 'image name',
            'caption' => 'image caption',
            'alt'     => 'image alt',
        ]);
        $I->see('Image information updated');
    }

    public function user_can_remove_an_image_from_page(AcceptanceTester $I)
    {
        $page = $I->getPage($I);
        $image = $I->getImageFromResourceArray($page);
        $id = $image->id;

        $I->wantTo('Remove an image from a page.');
        $I->login($I);
        $I->seeInDatabase('images', ['path' => $image->path]);
        $I->amOnPagePage($I, $page);
        $I->click('#remove'.$id);
        $I->wait(1);
        $I->click('Remove Image');
        $I->wait(1);
        $I->dontSeeInDatabase('images', ['path' => $image->path]);
        $I->see('Image removed from portfolio');
    }

    public function page_image_update_button_disabled_until_name_changed(AcceptanceTester $I)
    {
        $page = $I->getPage($I);

        $image = $I->getImageFromResourceArray($page);

        $id = $image->id;

        $I->wantTo('Be able to update a page image if the name has changed.');
        $I->login($I);
        $I->amOnPagePage($I, $page);
        $I->seeElement('#button'.$id, ['disabled' => 'true']);
        $I->fillField(['name' => 'name'.$id], 'abc');
        $I->dontSeeElement('#button'.$id, ['disabled' => 'true']);
    }

    public function page_image_update_button_disabled_until_alt_changed(AcceptanceTester $I)
    {
        $page = $I->getPage($I);

        $image = $I->getImageFromResourceArray($page);

        $id = $image->id;

        $I->wantTo('Be able to update a page image if the alt text has changed.');
        $I->login($I);
        $I->amOnPagePage($I, $page);
        $I->seeElement('#button'.$id, ['disabled' => 'true']);
        $I->fillField(['name' => 'alt'.$id], 'abc');
        $I->dontSeeElement('#button'.$id, ['disabled' => 'true']);
    }

    public function page_image_update_button_disabled_until_caption_changed(AcceptanceTester $I)
    {
        $page = $I->getPage($I);

        $image = $I->getImageFromResourceArray($page);

        $id = $image->id;

        $I->wantTo('Be able to update a page image if the caption has changed.');
        $I->login($I);
        $I->amOnPagePage($I, $page);
        $I->seeElement('#button'.$id, ['disabled' => 'true']);
        $I->fillField(['name' => 'caption'.$id], 'abc');
        $I->dontSeeElement('#button'.$id, ['disabled' => 'true']);
    }

    public function message_displayed_when_page_has_no_images(AcceptanceTester $I)
    {
        $I->wantTo('Verify that a message is displayed when no page has no images.');
        $I->login($I);
        $page = $I->getPage($I, 2);
        $I->amOnPagePage($I, $page);
        $I->see('This resource has no images');
    }

    public function no_images_message_is_hidden_when_page_image_added(AcceptanceTester $I)
    {
        $I->wantTo('Verify that the no images message is hidden when a page image is added.');
        $I->login($I);
        $page = $I->getPage($I, 2);
        $I->amOnPagePage($I, $page);
        $I->see('This resource has no images');
        $I->addImageToPage($I, $page);
        $I->dontSee('This resource has no images');
    }
}
