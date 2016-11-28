<?php

use Illuminate\Support\Facades\Artisan;

class LinksCest
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

    public function user_can_add_link_to_project(AcceptanceTester $I)
    {
        $data = [
            'name'  => 'Project Name',
            'key0' => 'Link key',
            'url0' => 'Link url',
            'text0' => 'Project description'
        ];

        $I->wantTo('Save a link when a new project is added.');
        $I->login($I);
        $I->amOnAddPage($I);
        $I->fillForm($I, $data);
        $I->click('Add Project');
        $I->wait(1);
        $I->seeCurrentUrlEquals('/manager/project_name');
        $I->seeInDatabase('projects', ['name' => $data['name']]);
        $I->seeInDatabase('links', [
            'key' => $data['key0'],
            'url' => $data['url0']
        ]);
    }

    public function user_can_update_a_link(AcceptanceTester $I)
    {
        $data = [
            'key0'       => 'updated key0',
            'url0'       => 'updated url0',
        ];

        $project = $I->getProject($I);
        $I->wantTo('Update a project link.');
        $I->login($I);
        $I->amOnPage("/manager/{$project->slug()}/edit");
        $I->fillForm($I, $data);
        $I->click('Update Project');
        $I->wait(1);
        $I->seeInDatabase('links', [
            'project_id'   => $project['id'],
            'key' => $data['key0'],
            'url' => $data['url0']
        ]);
    }

    public function user_can_remove_link_from_project(AcceptanceTester $I)
    {
        $data = [
            'key0' => 'Link key 0',
            'url0' => 'Link url 0',
            'key1' => 'Link key 1',
            'url1' => 'Link url 1',
        ];

        $I->wantTo('Remove a link from a project being added.');
        $I->login($I);
        $I->amOnAddPage($I);
        $I->click('#addLink');
        $I->fillForm($I, $data);
        $I->see('Link url 1');
        $I->removeLink($I, '#deleteLink1');
        $I->dontSee('Link url 1');
    }

    public function user_can_add_and_remove_links_like_crazy(AcceptanceTester $I)
    {
        $I->wantTo('Add and remove a bunch of random links.');
        $I->login($I);
        $I->amOnAddPage($I);
        // fill 0
        $I->fillField(['name' => 'url0'], 'link0');
        $I->see('link0');
        // add and fill 1
        $I->click('#addLink');
        $I->fillField(['name' => 'url1'], 'url1');
        $I->see('url1');
        // add and fill 2
        $I->click('#addLink');
        $I->fillField(['name' => 'url2'], 'url2');
        $I->see('url2');
        // delete 1
        $I->removeLink($I, '#deleteLink1');
        $I->dontSee('url1');
        // add and fill 3
        $I->click('#addLink');
        $I->fillField(['name' => 'url2'], 'url3');
        $I->see('url3');
        // delete 0
        $I->removeLink($I, '#deleteLink0');
        $I->dontSee('url0');
        // // add and fill 4
        $I->click('#addLink');
        $I->fillField(['name' => 'url2'], 'url4');
        $I->see('url4');
        // // add and fill 5
        $I->click('#addLink');
        $I->fillField(['name' => 'url3'], 'url5');
        $I->see('url5');
        // // delete 5
        $I->removeLink($I, '#deleteLink3');
        $I->dontSee('url5');
        // // add and fill 6
        $I->click('#addLink');
        $I->fillField(['name' => 'url3'], 'url6');
        $I->see('url6');
        $I->see('url2');
        $I->see('url3');
        $I->see('url4');
    }

    public function user_can_add_and_delete_links_when_editing(AcceptanceTester $I)
    {
        $project = $I->getProject($I);
        $links = $project->links;

        $I->wantTo('Add and delete links from a project when editing.');
        $I->login($I);
        $I->amOnPage("/manager/{$project->slug()}/edit");
        $I->click('#addLink');
        $I->fillField('url5', 'url5');
        $I->removeLink($I, '#deleteLink2');
        $I->removeLink($I, '#deleteLink1');
        $I->removeLink($I, '#deleteLink0');
        $I->click('#addLink');
        $I->fillField('url3', 'url6');
        $I->removeLink($I, '#deleteLink0');
        $I->wait(1);
        $I->click('Update Project');
        $I->wait(1);
        $I->amOnPage("/manager/{$project->slug()}");
        $I->see('url5');
        $I->see('url6');
        $I->dontSee($links[0]->url());
        $I->dontSee($links[1]->url());
        $I->dontSee($links[2]->url());
    }

    public function link_with_no_url_is_not_saved(AcceptanceTester $I)
    {
        $data = [
            'name'  => 'Project Name',
            'key0' => 'Link key',
            'text0' => 'Project description'
        ];

        $I->wantTo('Make sure empty links arent saved.');
        $I->login($I);
        $I->amOnAddPage($I);
        $I->fillForm($I, $data);
        $I->click('Add Project');
        $I->wait(1);
        $I->dontSeeInDatabase('links', [
            'key' => $data['key0'],
        ]);
    }
}
