<?php

namespace Larafolio\Models\UserTraits;

use Larafolio\Models\Page;

trait ManagesPages
{
    /**
     * Add a blocks and links to model.
     *
     * @param HasContent $model Model to add extras to.
     * @param array      $data  Array of posted user data.
     *
     * @return HasContent
     */
    protected abstract function addModelExtras(HasContent $model, array $data);

    /**
     * Update a HasContent model and its children.
     *
     * @param  HasContent $model Model to update.
     * @param  array      $data  Array of posted user data.
     *
     * @return HasContent
     */
    protected abstract function updateModel(HasContent $model, array $data);

    /**
     * Permanently delete a model.
     *
     * @param  HasContent $model Model to delete.
     *
     * @return boolean
     */
    protected abstract function purgeModel(HasContent $model);

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

        return $this->addModelExtras($page, $data);
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
        return $this->updateModel($page, $data);
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
        return $this->purgeModel($page);
    }
    
    /**
     * Update the order of pages in the portfolio.
     *
     * @param array $data Array of data for pages.
     */
    public function updatePageOrder(array $data)
    {
        $pageData = $this->setOrder($data);

        foreach ($pageData as $singlePageData) {
            $page = Page::find($singlePageData['id']);

            $page->update($singlePageData);
        }
    }
}