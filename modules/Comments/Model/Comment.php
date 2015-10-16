<?php

namespace Modules\Comments\Model;

use Modules\Users\Model\User;
use Modules\Articles\Model\Article;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer        $id
 * @property string         $text
 * @property integer        $author_id
 * @property integer        $article_id
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
        'text'
    ];

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
