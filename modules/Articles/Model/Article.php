<?php

namespace Modules\Articles\Model;

use Carbon\Carbon;
use Modules\Users\Model\User;
use Modules\Support\Helpers\Date;
use Modules\Support\Helpers\String;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Modules\Articles\Traits\TaggableTrait;
use Modules\Transactions\Contracts\Buyable;
use Modules\Transactions\Model\Transaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Articles\Exceptions\ArticleException;

/**
 * @property integer        $id
 * @property integer        $author_id
 * @property string         $title
 * @property string         $text_source
 * @property string         $text_intro_source
 * @property string         $text
 * @property string         $text_intro
 * @property string         $image
 * @property boolean        $forbid_comment
 * @property string         $status
 * @property string         $block_reason
 * @property integer        $count_payments
 * @property float          $amount
 * @property float          $cost
 *
 * @property User           $author
 * @property Collection     $tags
 * @property Collection     $categories
 *
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $published_at
 */
class Article extends Model implements Buyable
{

    use TaggableTrait, SoftDeletes;

    const STATUS_PUBLISHED = 'published';
    const STATUS_DRAFT = 'draft';
    const STATUS_BLOCKED = 'blocked';
    const STATUS_APPROVED = 'approved';

    /**
     * @var bool
     */
    private $isPurchased;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'articles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'text_source',
        'text_intro_source',
        'tags',
        'image',
        'forbid_comment',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'forbid_comment' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['published_at', 'deleted_at'];


    /**
     * @return int
     */
    public function getCost()
    {
        return 1;
    }


    /**
     * @param User $user
     *
     * @return bool
     */
    public function isPurchasedByUser(User $user)
    {
        if (is_bool($this->isPurchased)) {
            return $this->isPurchased;
        }

        return $this->isPurchased = ! is_null(Transaction::byUser($user)->byArticle($this)->onlyPayments()->first());
    }


    /**
     * @param $author
     */
    public function assignAuthor($author)
    {
        $this->author()->associate($author);
        $this->save();
    }


    /**
     * @throws ArticleException
     */
    public function setPublished()
    {
        if ($this->status != static::STATUS_DRAFT) {
            throw new ArticleException(trans('articles::article.message.can_publish_only_draft'));
        }

        $this->status       = static::STATUS_PUBLISHED;
        $this->published_at = Carbon::now();
        $this->save();
    }


    public function setDraft()
    {
        $this->status       = static::STATUS_PUBLISHED;
        $this->published_at = null;
        $this->save();
    }


    /**
     * @throws ArticleException
     */
    public function setApproved()
    {
        if ($this->status != static::STATUS_PUBLISHED) {
            throw new ArticleException(trans('articles::article.message.can_approve_ony_published'));
        }

        $this->status = static::STATUS_APPROVED;
        $this->save();
    }


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
     * Mutators
     **********************************************************************/

    /**
     * @return string|null
     */
    public function getPublishedAttribute()
    {
        if ($this->published_at instanceof Carbon) {
            if ($this->published_at->gt(new Carbon('-2 days'))) {
                return $this->published_at->diffForHumans();
            } else {
                return Date::format($this->published_at);
            }

        }

        return null;
    }


    /**
     * @return string
     */
    public function getAmountAttribute()
    {
        return String::formatAmount(array_get($this->attributes, 'amount'));
    }


    /**
     * @return string
     */
    public function getCostAttribute()
    {
        return String::formatAmount($this->getCost());
    }

    /**********************************************************************
     * Scopes
     **********************************************************************/

    /**
     * @param Builder    $query
     * @param User|null  $user
     *
     * @return Builder
     */
    public function scopeIsPurchased(Builder $query, User $user = null)
    {
        if(is_null($user)) {
            $userId = 0;
        } else {
            $userId = $user->id;
        }

        return $query
            ->selectSub('select `id` from `transactions` where `transactions`.`deleted_at` is null and `debit` = ? and `article_id` = ? limit 1', 'is_published')
            ->addBinding([$userId, $this->id], 'select');
    }


    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeNotApproved(Builder $query)
    {
        return $query->where('status', static::STATUS_PUBLISHED)
            ->whereHas('author', function (Builder $q) {
                $q->where('status', '!=', User::STATUS_BLOCKED);
            });
    }


    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeBlocked(Builder $query)
    {
        return $query->where('status', static::STATUS_BLOCKED);
    }


    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopePublished(Builder $query)
    {
        return $query->whereIn('status', [
            static::STATUS_PUBLISHED,
            static::STATUS_APPROVED,
        ])->whereHas('author', function ($q) {
            $q->where('status', '!=', User::STATUS_BLOCKED);
        });
    }


    /**
     * @param Builder $query
     * @param string  $column
     *
     * @return Builder
     */
    public function scopeOrderByDate(Builder $query, $column = 'published_at')
    {
        return $query->orderBy($column, 'desc');
    }

    /**********************************************************************
     * Relations
     **********************************************************************/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recipients()
    {
        return $this->hasMany(Transaction::class, 'article_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}