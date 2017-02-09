<?php

use Larafolio\Models\TextBlock;

class PagesCest
{
    public function _before(\AcceptanceTester $I)
    {
        $I->migrate();
    }

    public function user_can_add_a_page(AcceptanceTester $I)
    {
        $data = [
            'name'        => 'Page Name',
            'text0'       => 'Page description',
        ];

        $I->wantTo('Add a new page to the portfolio.');
        $I->login($I);
        $I->amOnAddPagePage($I);
        $I->fillForm($I, $data);
        $I->click('Add Page');
        $I->wait(1);
        $I->seeCurrentUrlEquals('/manager/pages/page_name');
        $I->see('Page successfully added');
        $I->seeInDatabase('pages', ['name' => $data['name']]);
        $I->seeInDatabase('text_blocks', ['text' => $data['text0']]);
    }

    public function page_name_is_required(AcceptanceTester $I)
    {
        $data = ['text0' => 'Page description'];

        $I->wantTo('Verify that name required error appears on add page page.');
        $I->login($I);
        $I->amOnAddPagePage($I);
        $I->fillForm($I, $data);
        $I->click('Add Page');
        $I->wait(1);
        $I->seeCurrentUrlEquals('/manager/pages/add');
        $I->see('Page name is required.');
    }

    public function page_name_must_be_unique_when_updating(AcceptanceTester $I)
    {
        $I->wantTo('Verify that page name must be unique when updating.');

        $page1 = $I->getPage($I, 1);
        $page2 = $I->getPage($I, 2);

        $data = [
            'name' => $page2->name()
        ];

        $I->login($I);
        $I->amOnPage("/manager/pages/{$page1->slug()}/edit");
        $I->fillForm($I, $data);
        $I->click('Update Page');
        $I->wait(1);
        $I->see('Page name is already taken.');
    }

    public function user_can_toggle_page_visibility(AcceptanceTester $I)
    {
        $page = $I->getPage($I);
        $I->wantTo('Toggle the visibility of a page.');
        $I->login($I);
        $I->amOnPage("/manager/pages/{$page->slug()}");
        $I->see('Hidden');
        $I->seeInDatabase('pages', [
            'id'      => $page->id(),
            'visible' => false,
        ]);
        $I->click('#makeVisible');
        $I->see('Visible');
        $I->see($page->name().' is now publicly viewable');
        $I->dontSee('Hidden');
        $I->seeInDatabase('pages', [
            'id'      => $page->id(),
            'visible' => true,
        ]);
        $I->amOnPage("/manager/pages/{$page->slug()}");
        $I->click('#makeHidden');
        $I->see('Hidden');
        $I->see($page->name().' is not publicly viewable');
        $I->dontSee('Visible');
        $I->seeInDatabase('pages', [
            'id'      => $page->id(),
            'visible' => false,
        ]);
    }

    public function user_can_remove_a_page(AcceptanceTester $I)
    {
        $page = $I->getPage($I);
        $I->wantTo('Remove a page from the portfolio.');
        $I->login($I);
        $I->amOnPage("/manager/pages/{$page->slug()}");
        $I->seeInDatabase('pages', [
            'id'         => $page->id(),
            'deleted_at' => null,
        ]);
        $I->click('#removeResource');
        $I->click('Remove');
        $I->wait(1);
        $I->seeInDatabase('pages', ['id' => $page->id()]);
        $I->dontseeInDatabase('pages', [
            'id'         => $page['id'],
            'deleted_at' => null,
        ]);
        $I->see("{$page->name()} removed from portfolio");
    }

    public function user_can_update_a_page(AcceptanceTester $I)
    {
        $data = [
            'name'        => 'updated name',
            'name0'       => 'updatedName0',
            'text0'       => 'updated0',
            'name1'       => 'updatedName1',
            'text1'       => 'updated1',
            'name2'       => 'updatedName2',
            'text2'       => 'updated2',
        ];

        $page = $I->getPage($I);
        $I->wantTo('Update a page in the portfolio.');
        $I->login($I);
        $I->amOnPage("/manager/pages/{$page->slug()}/edit");
        $I->fillForm($I, $data);
        $I->click('Update Page');
        $I->wait(1);
        $I->seeInDatabase('pages', [
            'id'   => $page['id'],
            'name' => $data['name'],
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
        $I->seeCurrentUrlEquals('/manager/pages/updated_name/edit');
        $I->see('Page successfully updated');
    }

    public function page_update_button_disabled_until_name_changed(AcceptanceTester $I)
    {
        $page = $I->getPage($I);

        $I->wantTo('Be able to update a page if the name was changed.');
        $I->login($I);
        $I->amOnPage("/manager/pages/{$page->slug()}/edit");
        $I->seeElement('.button--green', ['disabled' => 'true']);
        $I->fillField(['name' => 'name'], 'abc');
        $I->dontSeeElement('.button--green', ['disabled' => 'true']);
        $I->click('Update Page');
    }

    public function update_button_disabled_until_link_changed(AcceptanceTester $I)
    {
        $page = $I->getPage($I);

        $I->wantTo('Be able to update a page if a link was changed.');
        $I->login($I);
        $I->amOnPage("/manager/pages/{$page->slug()}/edit");
        $I->seeElement('.button--green', ['disabled' => 'true']);
        $I->fillField(['name' => 'url0'], 'abc');
        $I->dontSeeElement('.button--green', ['disabled' => 'true']);
        $I->click('Update Page');
    }

    public function leaving_edit_page_when_unsaved_causes_popup(AcceptanceTester $I)
    {
        $data = ['text0' => 'Page description'];

        $I->wantTo('Verify that popup blocks page leave if page form is unsaved.');
        $I->login($I);
        $I->amOnAddPagePage($I);
        $I->fillForm($I, $data);
        $I->amOnPage('/manager');
        $I->seeInPopup('Form contains unsaved content. Are you sure you want to leave?');
        $I->acceptPopup();
        $I->seeCurrentUrlEquals('/manager');
    }

    public function leaving_edit_page_when_unedited_does_not_cause_popup(AcceptanceTester $I)
    {
        $I->wantTo('Verify that popup does not occur if page form is saved.');
        $I->login($I);
        $I->amOnAddPagePage($I);
        $I->amOnPage('/manager');
        $I->seeCurrentUrlEquals('/manager');
    }

    public function user_can_force_delete_pages(AcceptanceTester $I)
    {
        $page = $I->getPage($I);

        $I->wantTo('Force delete a page.');
        $I->login($I);
        $I->amOnPage("/manager/pages/{$page->slug()}");
        $I->click('#removeResource');
        $I->click('Remove');
        $I->wait(1);
        $I->seeInDatabase('pages', [
            'id' => $page->id()
        ]);
        $I->amOnPage('/manager/settings/pages');
        $I->click('#delete'.$page->id());
        $I->wait(1);
        $I->click('#confirmDelete');
        $I->wait(1);
        $I->dontseeInDatabase('pages', [
            'id' => $page->id()
        ]);
    }

    public function user_can_restore_deleted_pages(AcceptanceTester $I)
    {
        $pages = $I->getPage($I);

        $I->wantTo('Restore a deleted pages.');
        $I->login($I);
        $I->amOnPage("/manager/pages/{$pages->slug()}");
        $I->click('#removeResource');
        $I->click('Remove');
        $I->wait(1);
        $I->dontSeeInDatabase('pages', [
            'id'         => $pages->id(),
            'deleted_at' => null,
        ]);
        $I->amOnPage('/manager/settings/pages');
        $I->click('#restore'.$pages->id());
        $I->wait(1);
        $I->seeInDatabase('pages', [
            'id' => $pages->id(),
            'deleted_at' => null,
        ]);
    }

    public function restored_pages_causes_nav_dropdown_refresh(AcceptanceTester $I)
    {
        $page = $I->getPage($I);

        $I->wantTo('Verify that when a page is restored, the nav menu refreshes.');
        $I->login($I);
        $I->amOnPage("/manager/pages/{$page->slug()}");
        $I->click('#removeResource');
        $I->click('Remove');
        $I->wait(1);
        $I->amOnPage('/manager/settings/pages');
        $I->dontSeeInPageSource('<span class="nav__dropdown-item-text">
                '.$page->name().'
            </span>');
        $I->click('#restore'.$page->id());
        $I->wait(1);
        $I->seeInPageSource('<span class="nav__dropdown-item-text">
                '.$page->name().'
            </span>');
    }
}
