<?php

namespace Larafolio\Models\UserTraits;

use Larafolio\Models\Page;

trait ManagesPages
{
    /**
     * Add a page to the portfolio.
     *
     * @param array $data Array of data to save.
     *
     * @return Project
     */
    public function addPage(array $data)
    {
        $data['order'] = Page::all()->pluck('order')->max() + 1;

        $page = Page::create($data);

        foreach (collect($data)->get('blocks', []) as $block) {
            $this->addBlockToModel($page, $block);
        }

        foreach (collect($data)->get('links', []) as $link) {
            $this->addLinkToModel($page, $link);
        }

        return $page;
    }

    /**
     * Update a page.
     *
     * @param Page  $page Page to update.
     * @param array $data Array of data to save.
     *
     * @return Page
     */
    public function updatePage(Page $page, array $data)
    {
        $page->update($data);

        $this->updateAllTextBlocks($page, $data);

        $this->updateAllLinks($page, $data);

        return $page;
    }

    /**
     * Remove a page.
     *
     * @param Page $page Page to remove.
     *
     * @return bool|null
     */
    public function removePage(Page $page)
    {
        return $page->delete();
    }

    /**
     * Restore a soft deleted page.
     *
     * @param Page $page Page to restore.
     *
     * @return bool|null
     */
    public function restorePage(Page $page)
    {
        $this->updatePage($page, ['visible' => false]);

        return $page->restore();
    }

    /**
     * Hard delete a page from the portfolio.
     *
     * @param Page $page Page to purge.
     *
     * @return bool|null
     */
    public function purgePage(Page $page)
    {
        foreach ($page->images as $image) {
            $this->removeImage($image);
        }

        $page->restore();

        return $page->forceDelete();
    }
}
