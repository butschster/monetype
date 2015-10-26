<?php

namespace Modules\Articles\Traits;

use Modules\Articles\Model\Tag;
use Illuminate\Database\Eloquent\Builder;

trait TaggableTrait
{

    /**
     * Добавление тегов по имени
     *
     * @param array $tags
     *
     * @return array
     */
    public function attachTags(array $tags)
    {
        $tags = $this->_filterTags($tags);

        $attachedTags = [];
        foreach ($tags as $name) {
            if ($this->addTag($name)) {
                $attachedTags[] = $name;
            }
        }

        $this->tags_list = $this->tags->lists('name')->all();
        $this->save();

        return $attachedTags;
    }


    /**
     * Удаление тегов по имени
     *
     * @param array $tags
     *
     * @return array
     */
    public function detachTags(array $tags = null)
    {
        $detachedTags = [];
        if (is_null($tags)) {
            $tags = $this->tags->lists('name')->all();
        }

        $tags = $this->_filterTags($tags);
        foreach ($tags as $name) {
            if ($this->removeTag($name)) {
                $detachedTags[] = $name;
            }
        }

        $this->tags_list = $this->tags->lists('name')->all();
        $this->save();

        return $detachedTags;
    }


    /**
     * @param array $tags
     *
     * @return array
     */
    public function updateTags(array $tags)
    {
        $tags         = $this->_filterTags($tags);
        $currentTags  = $this->tags->lists('name')->all();
        $replacedTags = [];

        $deletions = array_diff($currentTags, $tags);
        $additions = array_diff($tags, $currentTags);

        foreach ($deletions as $name) {
            if ($this->removeTag($name)) {
                $replacedTags[] = $name;
            }
        }

        foreach ($additions as $name) {
            if ($this->addTag($name)) {
                $replacedTags[] = $name;
            }
        }

        $this->tags_list = $tags;
        $this->save();

        return $replacedTags;
    }


    /**
     * Добавление тега по мени
     *
     * @param string $name
     *
     * @return bool
     */
    private function addTag($name)
    {
        $name = trim($name);

        $tag = Tag::firstOrCreate(['name' => $name]);

        $query = $this->tags()
            ->newPivotStatement()
            ->where('article_id', $this->id)
            ->where('tag_id', $tag->id)
            ->first();

        if (!is_null($query)) {
            return false;
        }

        $this->tags()->attach($tag);
        $tag->increment('count');

        return true;
    }


    /**
     * @param string $name
     *
     * @return bool
     */
    private function removeTag($name)
    {
        $name = trim($name);

        $tag = Tag::where('name', $name)->first();

        if (is_null($tag)) {
            return false;
        }

        $query = $this->tags()
            ->newPivotStatement()
            ->where('article_id', $this->id)
            ->where('tag_id', $tag->id)
            ->first();

        if (is_null($query)) {
            return false;
        }

        $this->tags()->detach($tag);
        $tag->decrement('count');

        return true;
    }


    /**
     * @param array $tags
     *
     * @return array
     */
    protected function _filterTags(array $tags)
    {
        return array_unique(array_map('trim', $tags));
    }

    /**********************************************************************
     * Scopes
     **********************************************************************/

    /**
     * @param Builder      $query
     * @param string|array $tag
     *
     * @return Builder
     */
    public function scopeFilterByTag(Builder $query, $tag)
    {
        return $query->whereHas('tags', function ($query) use ($tag) {
            if (is_array($tag)) {
                $query->whereIn('name', $tag);
            } else {
                $query->where('name', $tag);
            }
        });
    }

    /**
     * @param Builder   $query
     * @param array     $ids
     *
     * @return Builder
     */
    public function scopeFilterByTagIds(Builder $query, array $ids)
    {
        return $query->whereHas('tags', function ($query) use ($ids) {
            $query->whereIn('id', $ids);
        });
    }

    /**********************************************************************
     * Mutatotrs
     **********************************************************************/

    /**
     * @param array $tags
     */
    public function setTagsListAttribute(array $tags)
    {
        $tags                          = $this->_filterTags($tags);
        $this->attributes['tags_list'] = implode(',', $tags);
    }


    /**
     * @return array
     */
    public function getTagsArrayAttribute()
    {
        return explode(',', $this->getTagsStringAttribute());
    }


    /**
     * @return string|null
     */
    public function getTagsStringAttribute()
    {
        return array_get($this->attributes, 'tags_list');
    }

    /**********************************************************************
     * Relations
     **********************************************************************/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}