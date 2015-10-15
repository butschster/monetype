<?php

namespace Modules\Users\Http\Controllers;

use Assets;
use Modules\Users\Repositories\UserRepository;
use Modules\Core\Http\Controllers\System\FrontController;

class ProfileController extends FrontController
{

    public function boot()
    {
        if ( ! is_null($currentRoute = $this->getRouter()->getCurrentRoute())) {
            {
                if ($currentRoute->getName() == 'profile.show' and is_null($currentRoute->getParameter('id'))) {
                    $this->middleware('auth');
                }
            }
        }
    }


    /**
     * @param UserRepository $repository
     * @param int|null       $id
     *
     * @return \View
     */
    public function showById(UserRepository $repository, $id = null)
    {
        if (is_null($id)) {
            $id = $this->user->id;
        } else if($id <= 3) {
            abort(404, trans('users::user.message.not_found'));
        }

        $user = $repository->findOrFail($id);

        Assets::package(['backstretch']);

        return $this->setLayout('user.profile', compact('user'));
    }


    /**
     * @param UserRepository $repository
     * @param string       $username
     *
     * @return \View
     */
    public function showByUsername(UserRepository $repository, $username)
    {
        $user = $repository->findBy('username', $username);

        if(is_null($user)) {
            abort(404, trans('users::user.message.not_found'));
        }

        Assets::package(['backstretch']);

        return $this->setLayout('user.profile', compact('user'));
    }


    /**
     * @return \View
     */
    public function edit()
    {
        Assets::package(['dropzone', 'backstretch']);
        return $this->setLayout('user.edit', [
            'user' => $this->user
        ]);
    }
}
