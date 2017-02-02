<?php

namespace Larafolio\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Larafolio\Models\UserTraits\ManagesPages;
use Larafolio\Models\UserTraits\ManagesProjects;

trait ManagesPortfolio
{
    use ManagesPages, ManagesProjects;

    /**
     * Add a blocks and links to model.
     *
     * @param Larafolio\Models\HasContent $model Model to add extras to.
     * @param array                       $data  Array of posted user data.
     *
     * @return Larafolio\Models\HasContent
     */
    protected function addModelExtras(HasContent $model, array $data)
    {
        foreach (collect($data)->get('blocks', []) as $block) {
            $this->addBlockToModel($model, $block);
        }

        foreach (collect($data)->get('links', []) as $link) {
            $this->addLinkToModel($model, $link);
        }

        return $model;
    }

    /**
     * Update a HasContent model and its children.
     *
     * @param Larafolio\Models\HasContent $model Model to update.
     * @param array                       $data  Array of posted user data.
     *
     * @return Larafolio\Models\HasContent
     */
    protected function updateModel(HasContent $model, array $data)
    {
        $model->update($data);

        $this->updateAllTextBlocks($model, $data);

        $this->updateAllLinks($model, $data);

        return $model;
    }

    /**
     * Permanently delete a model.
     *
     * @param Larafolio\Models\HasContent $model Model to delete.
     *
     * @return bool
     */
    protected function purgeModel(HasContent $model)
    {
        foreach ($model->images as $image) {
            $this->removeImage($image);
        }

        $model->restore();

        return $model->forceDelete();
    }

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
     * @param Larafolio\Models\HasContent $model     Model to add text block to.
     * @param array                       $blockData Array of text block data.
     *
     * @return Larafolio\Models\HasContent
     */
    public function addBlockToModel(HasContent $model, array $blockData)
    {
        return $model->blocks()->create($blockData);
    }

    /**
     * Update a text block.
     *
     * @param Larafolio\Models\TextBlock $textBlock Text block to update.
     * @param array                      $blockData Array of text block data.
     *
     * @return Larafolio\Models\TextBlock
     */
    public function updateTextBlock(TextBlock $textBlock, array $blockData)
    {
        $textBlock->update($blockData);

        return $textBlock;
    }

    /**
     * Update model text blocks by adding new ones and updating existing ones.
     *
     * @param Larafolio\Models\HasContent $model Model that blocks belong to.
     * @param array                       $data  Array of model information.
     */
    public function updateAllTextBlocks(HasContent $model, array $data)
    {
        $blockData = collect($data)->get('blocks', []);

        $type = TextBlock::class;

        $this->updateContent(
            $model,
            $type,
            $blockData,
            [$this, 'updateTextBlock'],
            [$this, 'addBlockToModel']
        );
    }

    /**
     * Remove a text block from a model.
     *
     * @param Larafolio\Models\TextBlock $textBlock The text block to delete.
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
     * @param Larafolio\Models\HasContent $model     Model to add image to.
     * @param array                       $imageData Array of image infomation.
     *
     * @return Larafolio\Models\HasContent
     */
    public function addImageToModel(HasContent $model, array $imageData)
    {
        return $model->images()->create($imageData);
    }

    /**
     * Update image name and caption.
     *
     * @param Larafolio\Models\Image $image     Image to update.
     * @param array                  $imageData Array of inmage information.
     *
     * @return Larafolio\Models\Image
     */
    public function updateImageInfo(Image $image, array $imageData)
    {
        $image->update($imageData);

        return $image;
    }

    /**
     * Remove image from storage and delete database info.
     *
     * @param Larafolio\Models\Image $image Image to remove.
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
     * @param Larafolio\Models\HasContent $model    Model to add link to.
     * @param array                       $linkData Array of link info.
     */
    public function addLinkToModel(HasContent $model, array $linkData)
    {
        return $model->links()->create($linkData);
    }

    /**
     * Update a link.
     *
     * @param Larafolio\Models\Link $link     Link to update.
     * @param array                 $linkData Array of link data.
     *
     * @return Larafolio\Models\Link
     */
    public function updateLink(Link $link, array $linkData)
    {
        $link->update($linkData);

        return $link;
    }

    /**
     * Update model links by adding new ones and updating existing ones.
     *
     * @param Larafolio\Models\HasContent $model Model that links belong to.
     * @param array                       $data  Array of model information.
     */
    public function updateAllLinks(HasContent $model, array $data)
    {
        $linkData = collect($data)->get('links', []);

        $type = Link::class;

        $this->updateContent(
            $model,
            $type,
            $linkData,
            [$this, 'updateLink'],
            [$this, 'addLinkToModel']
        );
    }

    /**
     * Remove link from a model.
     *
     * @param Larafolio\Models\Link $link Link to remove.
     *
     * @return bool|null
     */
    public function removeLink(Link $link)
    {
        return $link->delete();
    }

    /**
     * Update content associated with model.
     *
     * @param Larafolio\Models\HasContent $model          Model associated with content.
     * @param string                      $type           Type of model.
     * @param array                       $data           user posted data.
     * @param callable                    $updateCallback Callback to update the content.
     * @param callable                    $addCallback    Callback to add new content.
     */
    protected function updateContent(
        HasContent $model,
        $type,
        array $data,
        callable $updateCallback,
        callable $addCallback
    ) {
        $data = $this->setOrder($data);

        foreach ($data as $singleItemData) {
            if (isset($singleItemData['resource_id'])) {
                $block = $type::find($singleItemData['id']);

                $updateCallback($block, $singleItemData);
            } else {
                $addCallback($model, $singleItemData);
            }
        }
    }
}
