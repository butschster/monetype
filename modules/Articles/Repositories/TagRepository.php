<?php

namespace Modules\Articles\Repositories;

use Modules\Articles\Model\Tag;
use Modules\Support\Helpers\Repository;

class TagRepository extends Repository
{

    /**
     * @return string
     */
    public function model()
    {
        return Tag::class;
    }


    /**
     * @param int $limit
     *
     * @return array
     */
    public function getTagsCloud($limit = 20)
    {
        $terms = $this->getModel()->take($limit)->orderBy('count', 'desc')->get();

        $maximum = $terms->first()->count;

        $cloud = [];

        // start looping through the tags
        foreach ($terms as $term) {
            // determine the popularity of this term as a percentage
            $percent = floor(( $term->count / $maximum ) * 100);

            // determine the class for this term based on the percentage
            if ($percent < 20):
                $class = 'smallest';
            elseif ($percent >= 20 and $percent < 40):
                $class = 'small';
            elseif ($percent >= 40 and $percent < 60):
                $class = 'medium';
            elseif ($percent >= 60 and $percent < 80):
                $class = 'large';
            else:
                $class = 'largest';
            endif;

            $cloud[] = link_to_route('front.articles.byTag', $term->name, $term->name, ['class' => 'tagsCloud--tag tag-size-' . $class]);
        }

        return $cloud;
    }

    /**
     * @param string $string
     *
     * @return array
     */
    public function finAllByString($string)
    {
        return $this->getModel()
            ->where('name', 'like', "$string%")
            ->take(10)
            ->orderBy('count', 'desc')
            ->lists('name');
    }


    /**
     * @param array   $data
     * @param integer $id
     * @param string  $attribute
     *
     * @return Article
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $tags = array_pull($data, 'tags', '');

        $article = parent::update($data, $id, $attribute);

        if (is_string($tags)) {
            $tags = explode(',', $tags);
        }

        $article->updateTags($tags);

        return $article;
    }


    /**
     * @param int   $perPage
     * @param array $columns
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage = 15, $columns = ['*'])
    {
        return $this->getModel()
            ->with('author', 'tags')
            ->orderByDate()
            ->published()
            ->withFavorites()
            ->paginate($perPage, $columns);
    }


    /**
     * @param array|string $tag
     * @param int          $perPage
     * @param array        $columns
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateByTag($tag, $perPage = 15, $columns = ['*'])
    {
        $tag = explode(',', $tag);

        return $this->getModel()
            ->with('author', 'tags')
            ->filterByTag($tag)
            ->orderByDate()
            ->published()
            ->withFavorites()
            ->paginate($perPage, $columns);
    }
}