<?php

namespace Modules\Articles\Model;

use cogpowered\FineDiff\Diff;
use Modules\Support\Helpers\String;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 * @property string         $id
 * @property integer        $article_id
 * @property string         $text_source
 * @property string         $opcodes
 *
 * @property Article        $article
 *
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 */
class ArticleRevision extends Model
{

    use SoftDeletes;


    protected static function boot()
    {
        parent::boot();

        static::creating(function (ArticleRevision $revision) {
            $revision->id = String::uniqueId();
            $lastRevision = static::getLastByArticle($revision->article_id)->first();
            if ( ! is_null($lastRevision)) {
                $diff              = new Diff;
                $revision->opcodes = $diff->getOpcodes(
                    $lastRevision->text_source,
                    $revision->text_source
                )->generate();
            }
        });
    }


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'article_revisions';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text_source'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


    /**
     * @param string $revisionId
     *
     * @return null|string
     */
    public function getDiff($revisionId)
    {
        if ($this->id == $revisionId) {
            return null;
        }

        $revision = static::where('article_id', $this->article_id)
            ->where('id', $revisionId)
            ->firstOrFail();

        return (new Diff)->render($this->text_source, $revision->text_source);
    }

    /**********************************************************************
     * Mutators
     **********************************************************************/

    /**
     * @param $text
     */
    public function setTextSourceAttribute($text)
    {
        $this->attributes['text_source'] = trim($text);
    }

    /**********************************************************************
     * Scopes
     **********************************************************************/

    /**
     * @param Builder $query
     * @param integer $articleId
     */
    public function scopeGetLastByArticle(Builder $query, $articleId)
    {
        $query->where('article_id', $articleId)->orderBy('created_at', 'desc');
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