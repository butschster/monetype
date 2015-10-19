<?php

namespace Modules\Comments\Model;

use Carbon\Carbon;
use Modules\Users\Model\User;
use Modules\Support\Helpers\Date;
use Modules\Articles\Model\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer        $id
 * @property string         $text
 * @property integer        $author_id
 * @property integer        $article_id
 * @property string         $user_ip
 * @property string         $created
 * @property string         $status
 *
 * @property Article        $article
 *
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 */
class Comment extends Model
{

    use SoftDeletes;

    const STATUS_PUBLISHED = 'published';
    const STATUS_SPAM = 'spam';
    const STATUS_BLOCKED = 'blocked';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text',
    ];

    /**********************************************************************
     * Mutators
     **********************************************************************/

    /**
     * @return string|null
     */
    public function getCreatedAttribute()
    {
        if ($this->created_at instanceof Carbon) {
            if ($this->created_at->gt(new Carbon('-1 days'))) {
                return $this->created_at->diffForHumans();
            } else {
                return Date::format($this->created_at);
            }
        }

        return null;
    }

    /**********************************************************************
     * Scopes
     **********************************************************************/

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
        return $query->where('status', static::STATUS_PUBLISHED)
            ->whereHas('author', function ($q) {
                $q->where('status', '!=', User::STATUS_BLOCKED);
            });
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
