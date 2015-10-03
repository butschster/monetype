<?php

namespace Modules\Articles\Model;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * Set timestamps off
     */
    public $timestamps = false;


    /**
     *
     * @return HasMany
     */
    public function articles()
    {
        return $this->belongsToMany(Article::class)
            ->OrderByDate()
            ->withFavorite()
            ->published()
            ->with('categories', 'author');
    }
}
