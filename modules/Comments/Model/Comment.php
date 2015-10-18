<?php

namespace Modules\Comments\Model;

use Carbon\Carbon;
use Modules\Users\Model\User;
use Modules\Support\Helpers\Date;
use Modules\Articles\Model\Article;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer        $id
 * @property string         $text
 * @property integer        $author_id
 * @property integer        $article_id
 * @property string         $user_ip
 * @property string         $created
 *
 * @property Article        $article
 *
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 */
class Comment extends Model
{

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
