<?php

namespace Larafolio\Models;

use Larafolio\Models\HasContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Larafolio\Models\UserTraits\ManagesPages;
use Larafolio\Models\UserTraits\ManagesProjects;

trait ManagesPortfolio
{
    use ManagesPages, ManagesProjects;

    /**
     * Set order of data based on order value.
     *
     * @param array $data Data array containing 'order' index.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function setOrder(array $data)
    {
        return collect($data)->sortBy('order')
            ->map(function ($item, $key) {
                $item['order'] = $key;

                return $item;
            });
    }

    /**
     * Add a text block to a model.
     *
     * @param HasContent $model   Model to add text block to.
     * @param array   $blockData Array of text block data.
     *
     * @return HasContent
     */
    public function addBlockToModel(HasContent $model, array $blockData)
    {
        return $model->blocks()->create($blockData);
    }

    /**
     * Update a text block.
     *
     * @param TextBlock $textBlock Text block to update.
     * @param array     $blockData Array of text block data.
     *
     * @return TextBlock
     */
    public function updateTextBlock(TextBlock $textBlock, array $blockData)
    {
        $textBlock->update($blockData);

        return $textBlock;
    }

    /**
     * Update model text blocks by adding new ones and updating existing ones.
     *
     * @param Model $model Model that blocks belong to.
     * @param array $data  Array of model information.
     */
    public function updateAllTextBlocks(Model $model, array $data)
    {
        $blockData = collect($data)->get('blocks', []);

        $blockData = $this->setOrder($blockData);

        foreach ($blockData as $singleBlockData) {
            if (isset($singleBlockData['resource_id'])) {
                $block = TextBlock::find($singleBlockData['id']);

                $this->updateTextBlock($block, $singleBlockData);
            } else {
                $this->addBlockToModel($model, $singleBlockData);
            }
        }
    }

    /**
     * Remove a text block from a project.
     *
     * @param TextBlock $textBlock The text block to delete.
     *
     * @return bool|null
     */
    public function removeTextBlock(TextBlock $textBlock)
    {
        return $textBlock->delete();
    }

    /**
     * Add image to a model.
     *
     * @param HasContent $model     Model to add image to.
     * @param array      $imageData Array of image infomation.
     *
     * @return HasContent
     */
    public function addImageToModel(HasContent $model, array $imageData)
    {
        return $model->images()->create($imageData);
    }

    /**
     * Update image name and caption.
     *
     * @param Image $image     Image to update.
     * @param array $imageData Array of inmage information.
     *
     * @return Image
     */
    public function updateImageInfo(Image $image, array $imageData)
    {
        $image->update($imageData);

        return $image;
    }

    /**
     * Remove image from storage and delete database info.
     *
     * @param Image $image Image to remove.
     *
     * @return bool|null
     */
    public function removeImage(Image $image)
    {
        Storage::delete($image->path());

        return $image->delete();
    }

    /**
     * Add a link to a model.
     *
     * @param HasContent $model  Model to add link to.
     * @param array   $linkData Array of link info.
     */
    public function addLinkToModel(HasContent $model, array $linkData)
    {
        return $model->links()->create($linkData);
    }

    /**
     * Update a link.
     *
     * @param Link  $link     Link to update.
     * @param array $linkData Array of link data.
     *
     * @return Link
     */
    public function updateLink(Link $link, array $linkData)
    {
        $link->update($linkData);

        return $link;
    }

    /**
     * Update model links by adding new ones and updating existing ones.
     *
     * @param Model $model Model that links belong to.
     * @param array $data  Array of model information.
     */
    public function updateAllLinks(Model $model, array $data)
    {
        $linkData = collect($data)->get('links', []);

        $linkData = $this->setOrder($linkData);

        foreach ($linkData as $singleLinkData) {
            if (isset($singleLinkData['resource_id'])) {
                $link = Link::find($singleLinkData['id']);

                $this->updateLink($link, $singleLinkData);
            } else {
                $this->addLinkToModel($model, $singleLinkData);
            }
        }
    }

    /**
     * Remove link from a project.
     *
     * @param Link $link Link to remove.
     *
     * @return bool|null
     */
    public function removeLink(Link $link)
    {
        return $link->delete();
    }
}
