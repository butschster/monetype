<?php

namespace Modules\Docs\Http\Controllers;

use Config;
use Modules\Docs\Model\Doc;
use Modules\Core\Http\Controllers\System\FrontController;

class DocsController extends FrontController {

    /**
     * The documentation repository.
     *
     * @var Documentation
     */
    protected $docs;

    /**
     * Create a new controller instance.
     *
     * @param  Documentation  $docs
     * @return void
     */
    public function boot(Doc $docs)
    {
        $this->docs = $docs;
    }

    /**
     * Show a documentation page.
     *
     * @return Response
     */
    public function show($page = null)
    {
        $content = $this->docs->get($page ?: 'blank');

        if (is_null($content)) {
            Config::set('app.debug', false);
            abort(404, trans('core::core.message.page_not_found'));
        }
        return $this->setLayout('docs', compact('content'));
    }
}