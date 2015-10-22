<?php

namespace Modules\Articles\Http\Controllers\Api;

use Modules\Articles\Model\Tag;
use Illuminate\Http\JsonResponse;
use Modules\Articles\Repositories\TagRepository;
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
}
