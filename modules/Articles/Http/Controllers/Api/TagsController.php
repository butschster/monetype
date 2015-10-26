<?php

namespace Modules\Articles\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Modules\Articles\Repositories\TagRepository;
use Modules\Articles\Repositories\ArticleRepository;
use Modules\Core\Http\Controllers\System\ApiController;

class TagsController extends ApiController
{

    /**
     * @param TagRepository $tagRepository
     *
     * @return JsonResponse
     */
    public function search(TagRepository $tagRepository)
    {
        $query = $this->getRequiredParameter('query');

        return new JsonResponse($tagRepository->finAllByString($query));
    }


    /**
     * @param TagRepository $tagRepository
     */
    public function addThematic(TagRepository $tagRepository)
    {
        $tag = $this->getRequiredParameter('tag');

        $user = auth()->user();

        $hasTag = ! is_null($user->tags()->where('name', $tag)->first());

        if ($hasTag) {
            $this->setMessage(trans('articles::tag.message.user_has_thematic_tag'));

            return;
        }

        $tag = $tagRepository->findBy('name', $tag);

        if (is_null($tag)) {
            $this->setMessage(trans('articles::tag.message.tag_not_found'));

            return;
        }

        $user->tags()->attach($tag);
        $tags = $user->tags;

        $this->setContent(view('articles::tag.partials.thematic', compact('tags')));
    }


    public function deleteThematic()
    {
        $user = auth()->user();
        $tag  = $this->getRequiredParameter('tag');
        $tag  = $user->tags()->findOrFail($tag);

        if (is_null($tag)) {
            $this->setMessage(trans('articles::tag.message.tag_not_found'));

            return;
        }

        $user->tags()->detach($tag);

        $tags = $user->tags;
        $this->setContent(view('articles::tag.partials.thematic', compact('tags')));
    }
}
