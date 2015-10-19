<?php

namespace Modules\Users\Http\Controllers\Api;

use Modules\Core\Http\Controllers\System\ApiController;

class ProfileController extends ApiController
{
    public function uploadBackground()
    {
        $file = $this->getRequiredParameter('file', [
            'required', 'image'
        ]);

        $user = auth()->user();
        $user->attachBackground($file);

        $this->setContent($user->getBackground());
    }
}
