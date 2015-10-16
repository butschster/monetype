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
 * @property integer        $article_id
 * @property float          $percent
 * @property string         $error
 * @property string         $text
 * @property string         $created
 *
 * @property object         $response
 * @property Article        $article
 *
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class ArticleCheck extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'article_checks';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'response' => 'array',
        'percent' => 'float'
    ];


    /**
     * @return bool
     */
    public function hasError()
    {
        return ! empty( $this->error );
    }

    /**
     * @return bool
     */
    public function isPlagiarism()
    {
        return $this->percent > (int) config('article.check.max_percent_plagiarism', 20);
    }

    /**********************************************************************
     * Mutators
     **********************************************************************/

    /**
     * @return string
     */
    public function getCreatedAttribute()
    {
        return Date::format($this->created_at);
    }

    /**
     * @return string
     */
    public function getStatusAttribute()
    {
        if($this->isPlagiarism()) {
            return trans('articles::check.label.plagiarism');
        }

        return trans('articles::check.label.successfully');
    }

    /**********************************************************************
     * Scopes
     **********************************************************************/

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeRelevant(Builder $query)
    {
        return $query

            ->orderBy('created_at', 'desc')
            ->take(50);
    }

    /**********************************************************************
     * Relations
     **********************************************************************/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}