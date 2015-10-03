<?php

namespace Modules\Articles\Model;

use Carbon\Carbon;
use Modules\Users\Model\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Articles\Traits\TaggableTrait;
use Modules\Transactions\Contracts\Buyable;
use Modules\Transactions\Model\Transaction;

class Article extends Model implements Buyable
{
    use TaggableTrait;

    const STATUS_PUBLISHED = 'published';
    const STATUS_DRAFT = 'draft';
    const STATUS_BLOCKED = 'blocked';
    const STATUS_APPROVED = 'approved';

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
        'forbid_comment'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'forbid_comment' => 'boolean'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['published_at'];


    public function getCost()
    {
        return 1;
    }

    /**
     * @param $author
     */
    public function assignAuthor($author)
    {
        $this->author()->associate($author);
        $this->save();
    }


    public function setPublished()
    {
        if ($this->status !== static::STATUS_DRAFT) {
            throw new \Exception('Можно опубликовать только черновик');
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


    public function setApproved()
    {
        $this->status = static::STATUS_APPROVED;
        $this->save();
    }

    /**********************************************************************
     * Scopes
     **********************************************************************/

    public function scopePublished($query)
    {
        return $query
             ->whereIn('status', [static::STATUS_PUBLISHED, static::STATUS_APPROVED])
             ->whereHas('author', function ($q) {
                 $q->where('status', '!=', User::STATUS_BLOCKED);
             });
    }


    public function scopeOrderByDate($query, $column = 'published_at')
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
}