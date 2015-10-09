<?php

namespace Modules\Articles\Model;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer        $id
 * @property string         $title
 * @property string         $slug
 * @property integer        $count_articles
 * @property float          $publish_cost
 * @property Collection     $articles
 *
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Category extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'publish_cost'
    ];


    /**
     * @param Article $article
     *
     * @return bool
     */
    public function attachArticle(Article $article)
    {
        $query = $this->articles()
            ->newPivotStatement()
            ->where('article_id', $article->id)
            ->where('category_id', $this->id)
            ->first();

        if ($query !== null) {
            return false;
        }

        $this->articles()->attach($article->id);
        $this->increment('count_articles');

        return true;
    }


    /**
     * @param Article $article
     *
     * @return bool
     */
    public function detachArticle(Article $article)
    {
        $query = $this->articles()
            ->newPivotStatement()
            ->where('article_id', $article->id)
            ->where('category_id', $this->id)
            ->first();

        if ($query === null) {
            return false;
        }

        $this->articles()->detach($article->id);
        $this->decrement('count_articles');

        return true;
    }


    /**********************************************************************
     * Mutators
     **********************************************************************/

    /**
     * @param string $slug
     */
    public function setSlugAttribute($slug)
    {
        $this->attributes['slug'] = Str::slug($slug);
    }


    /**
     * @param float $amount
     */
    public function setPublishCostAttribute($amount)
    {
        $this->attributes['publish_cost'] = (float) $amount;
    }

    /**********************************************************************
     * Relations
     **********************************************************************/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }
}
