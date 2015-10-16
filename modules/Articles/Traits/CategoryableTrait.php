<?php

namespace Modules\Articles\Traits;

use Modules\Articles\Model\Category;

/**
 * Class CategoryableTrait
 * @package Modules\Articles\Traits
 *
 * @property Collection $categories
 */
trait CategoryableTrait
{

    /**
     * @param array $ids
     *
     * @return array
     */
    public function attachCategories(array $ids)
    {
        $attachedCategories = [];
        $categories         = Category::whereIn('id', $ids)->get();
        foreach ($categories as $category) {
            if ($category->attachArticle($this)) {
                $attachedCategories[] = $category->id;
            }
        }

        return $attachedCategories;
    }


    /**
     * @param array $ids
     *
     * @return array
     */
    public function updateCategories(array $ids)
    {
        $categories        = Category::whereIn('id', $ids)->get();
        $ids               = $categories->lists('id');
        $currentCategories = $this->categories()->get()->lists('id');
        $updatedCategories = [];

        $deletions = array_diff($currentCategories, $ids);
        $additions = array_diff($ids, $currentCategories);

        foreach (Category::whereIn('id', $deletions)->get() as $category) {
            if ($category->detachArticle($this)) {
                $updatedCategories[] = $category->id;
            }
        }

        foreach (Category::whereIn('id', $additions)->get() as $category) {
            if ($category->attachArticle($this)) {
                $updatedCategories[] = $category->id;
            }
        }

        return $updatedCategories;
    }

    /**********************************************************************
     * Relations
     **********************************************************************/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

}