<?php

namespace Larafolio\Models\UserTraits;

use Larafolio\Models\Page;
use Larafolio\Models\HasContent;

trait ManagesPages
{
    /**
     * Add children resources to model.
     *
     * @param \Larafolio\Models\HasContent $model Model to add children to.
     * @param array                        $data  Array of posted user data.
     *
     * @return \Larafolio\Models\HasContent
     */
    abstract protected function addModelChildren(HasContent $model, array $data);

    /**
     * Update a HasContent model and its children.
     *
     * @param \Larafolio\Models\HasContent $model Model to update.
     * @param array                        $data  Array of posted user data.
     *
     * @return \Larafolio\Models\HasContent
     */
    abstract protected function updateModel(HasContent $model, array $data);

    /**
     * Permanently delete a model.
     *
     * @param \Larafolio\Models\HasContent $model Model to delete.
     *
     * @return bool
     */
    abstract protected function purgeModel(HasContent $model);

    /**
     * Add a page to the portfolio.
     *
     * @param array $data Array of data to save.
     *
     * @return \Larafolio\Models\Page
     */
    public function addPage(array $data)
    {
        $data['order'] = Page::all()->pluck('order')->max() + 1;

        $page = Page::create($data);

        return $this->addModelChildren($page, $data);
    }

    /**
     * Update a page.
     *
     * @param \Larafolio\Models\Page $page Page to update.
     * @param array                  $data Array of data to save.
     *
     * @return \Larafolio\Models\Page
     */
    public function updatePage(Page $page, array $data)
    {
        return $this->updateModel($page, $data);
    }

    /**
     * Remove a page.
     *
     * @param \Larafolio\Models\Page $page Page to remove.
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
     * @param \Larafolio\Models\Page $page Page to restore.
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
     * @param \Larafolio\Models\Page $page Page to purge.
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
