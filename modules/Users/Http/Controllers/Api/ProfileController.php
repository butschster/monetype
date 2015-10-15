<?php

namespace Modules\Users\Http\Controllers\Api;

use KodiCMS\API\Http\Controllers\Controller;

class ProfileController extends Controller
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
