<?php

namespace Larafolio\tests\unit;

use Larafolio\Models\Page;
use Larafolio\Models\Image;
use Larafolio\tests\TestCase;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PagesTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function user_can_add_a_page()
    {
        $data = [
            'name' => 'name'
        ];

        $page = $this->user->addPage($data);

        $this->assertInstanceOf(Page::class, $page);

        $this->seeInDatabase('pages', $data);
    }

    /**
     * @test
     */
    public function page_order_value_is_set_to_next_available_value()
    {
        $max = Page::all()->pluck('order')->max() + 1;

        $dataArray = [
            $max => [
                'name' => 'name1',
            ],
            $max + 1 => [
                'name' => 'name2',
            ],
            $max + 2 => [
                'name' => 'name3',
            ],
        ];

        foreach ($dataArray as $key => $data) {
            $project = $this->user->addPage($data);

            $data['order'] = $key;

            $this->seeInDatabase('pages', $data);
        }
    }

    /**
     * @test
     */
    public function slug_is_created_when_page_is_added()
    {
        $data = [
            'name' => 'page name',
        ];

        $page = $this->user->addPage($data);

        $this->assertEquals('page_name', $page->slug());

        $data['slug'] = $page->slug();

        $this->seeInDatabase('pages', $data);
    }

    /**
     * @test
     */
    public function user_can_update_a_page()
    {
        $page = factory(Page::class)->create();

        $data = [
            'name' => 'new name',
        ];

        $this->user->updatePage($page, $data);

        $merged = collect($page->getAttributes())->merge($data)->all();

        $this->seeInDatabase('pages', $merged);
    }

    /**
     * @test
     */
    public function user_can_update_page_visibility()
    {
        $page = factory(Page::class)->create();

        $this->seeInDatabase('pages', [
            'id'      => $page->id(),
            'visible' => false,
        ]);

        $data = [
            'visible' => true,
        ];

        $this->user->updatePage($page, $data);

        $this->seeInDatabase('pages', [
            'id'      => $page->id(),
            'visible' => true,
        ]);
    }

    /**
     * @test
     */
    public function page_slug_is_updated_when_name_is_updated()
    {
        $page = factory(Page::class)->create(['name' => 'first name']);

        $this->assertEquals('first_name', $page->slug());

        $page = $this->user->updatePage($page, ['name' => 'second name']);

        $this->assertEquals('second_name', $page->slug());
    }

    /**
     * @test
     */
    public function user_can_remove_a_page()
    {
        $page = factory(Page::class)->create();

        $this->seeInDatabase('pages', [
            'id'         => $page->id(),
            'deleted_at' => null,
        ]);

        $deleted = $this->user->removePage($page);

        $this->assertTrue($deleted);

        $this->dontSeeInDatabase('pages', [
            'id'         => $page->id(),
            'deleted_at' => null,
        ]);

        $this->seeInDatabase('pages', [
            'id' => $page->id(),
        ]);
    }

    /**
     * @test
     */
    public function user_can_hard_delete_a_page()
    {
        $page = factory(Page::class)->create();

        $this->seeInDatabase('pages', [
            'id'         => $page->id(),
            'deleted_at' => null,
        ]);
        
        $this->user->removePage($page);

        $deleted = $this->user->purgePage($page);

        $this->assertTrue($deleted);

        $this->dontSeeInDatabase('pages', [
            'id'         => $page->id()
        ]);
    }

    /**
     * @test
     */
    public function user_can_restore_a_soft_deleted_page()
    {
        $page = factory(Page::class)->create();

        $this->seeInDatabase('pages', [
            'id'         => $page->id(),
            'deleted_at' => null,
        ]);

        $deleted = $this->user->removePage($page);

        $this->assertTrue($deleted);

        $this->dontSeeInDatabase('pages', [
            'id'         => $page->id(),
            'deleted_at' => null,
        ]);

        $this->user->restorePage($page);

        $this->seeInDatabase('pages', [
            'id'         => $page->id(),
            'deleted_at' => null,
        ]);
    }

    /**
     * @test
     */
    public function all_visible_static_returns_all_visible_pages()
    {
        $page1 = factory(Page::class)->create();
        $page2 = factory(Page::class)->create();
        $page3 = factory(Page::class)->create();

        $this->user->updatePage($page1, ['visible' => true]);

        $pages = Page::allVisible()->flatten(1);

        $pages->each(function ($page) {
            $this->assertTrue($page->visible);
        });

        $this->assertCount(1, $pages);
    }

    /**
     * @test
     */
    public function all_visible_static_conditionally_orders_visible_pages_by_order()
    {
        $this->makePagesForAllVisibleTest();
        $this->makePagesForAllVisibleTest();
        $this->makePagesForAllVisibleTest();
        $this->makePagesForAllVisibleTest();

        $unordered = Page::allVisible(false)->pluck('order');

        $ordered = Page::allVisible(true)->pluck('order');

        $this->assertOrder($ordered);

        $this->assertNotEquals($unordered, $ordered);
    }

    /**
     * @test
     */
    public function all_hidden_static_returns_all_hidden_pages()
    {
        $page1 = factory(Page::class)->create();
        $page2 = factory(Page::class)->create();
        $page3 = factory(Page::class)->create();

        $this->user->updatePage($page1, ['visible' => true]);

        $pages = Page::allHidden()->flatten(1);

        $pages->each(function ($page) {
            $this->assertFalse($page->visible);
        });

        $hiddenPages = Page::where('visible', false);

        $this->assertCount($hiddenPages->count(), $pages);
    }

    /**
     * @test
     */
    public function all_ordered_static_returns_all_pages_ordered()
    {
        $this->makePagesForAllVisibleTest();
        $this->makePagesForAllVisibleTest(false);
        $this->makePagesForAllVisibleTest(false);
        $this->makePagesForAllVisibleTest(true);

        $ordered = Page::allOrdered()->pluck('order');

        $this->assertOrder($ordered);
    }

    /**
     * @test
     */
    public function user_can_get_all_pages_that_have_block_name()
    {
        $page = $this->createPageWithBlock('block name');
        $page = $this->createPageWithBlock('block name');
        $page = $this->createPageWithBlock('block name');
        $page = $this->createPageWithBlock('other name');

        $page = Page::hasBlockNamed('block name');

        $this->assertCount(3, $page);

        $page->each(function ($page) {
            $block = $page->blocks[0];

            $this->assertEquals('block name', $block->name());
        });
    }

    /**
     * @test
     */
    public function user_can_get_all_pages_that_have_image_name()
    {
        $page = $this->makePageWithImage('image name');
        $page = $this->makePageWithImage('image name');
        $page = $this->makePageWithImage('image name');
        $page = $this->makePageWithImage('other name');

        $pages = Page::hasImageNamed('image name');

        $this->assertCount(3, $pages);

        $pages->each(function ($page) {
            $image = $page->images[0];

            $this->assertEquals('image name', $image->name());
        });
    }

    /**
     * @test
     */
    public function user_can_get_all_pages_that_have_link_name()
    {
        $page = $this->makePageWithLink('link name');
        $page = $this->makePageWithLink('link name');
        $page = $this->makePageWithLink('link name');
        $page = $this->makePageWithLink('other name');

        $pages = Page::hasLinkNamed('link name');

        $this->assertCount(3, $pages);

        $pages->each(function ($page) {
            $link = $page->links[0];

            $this->assertEquals('link name', $link->name());
        });
    }

    /**
     * @test
     */
    public function ordered_page_blocks_are_ordered_by_block_order()
    {
        $page = $this->createPageWithBlock('block name');

        $data1 = [
            'name'           => 'text block name',
            'text'           => 'text block text',
            'formatted_text' => '<p>text block text</p>',
            'order'          => 3,
        ];

        $page->blocks()->create($data1);

        $data2 = [
            'name'           => 'text block name',
            'text'           => 'text block text',
            'formatted_text' => '<p>text block text</p>',
            'order'          => 2,
        ];

        $page->blocks()->create($data1);

        $pages = Page::allOrdered();

        foreach ($pages as $page) {
            $blocks = $page->blocks;

            $this->assertOrder($blocks->pluck('order'));
        }
    }

    /**
     * @test
     */
    public function ordered_pages_links_are_ordered_by_link_order()
    {
        $page = $this->makePageWithLink('link name');

        $data1 = [
            'name'  => 'new name',
            'text'  => 'new text',
            'url'   => 'new url',
            'order' => 3
        ];

        $page->links()->create($data1);

        $data2 = [
            'name'  => 'new name',
            'text'  => 'new text',
            'url'   => 'new url',
            'order' => 2
        ];

        $page->links()->create($data2);

        $projects = Page::allOrdered();

        foreach ($projects as $page) {
            $links = $page->links;

            $this->assertOrder($links->pluck('order'));
        }
    }

    /**
     * Make four pages for allVisible tests.
     */
    protected function makePagesForAllVisibleTest($makeVisible = true)
    {
        foreach (range(0, 3) as $time) {
            factory(Page::class)->create();
        }

        $conditions = [];

        if ($makeVisible) {
            $conditions['visible'] = true;
        }

        Page::all()->each(function ($page) use ($conditions) {
            $this->user->updatePage($page, $conditions);
        });
    }
}
