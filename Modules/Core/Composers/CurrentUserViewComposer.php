<?php

namespace Modules\Core\Composers;

use Illuminate\Contracts\View\View;
use Modules\User\Contracts\Authentication;
use Illuminate\Support\Facades\Auth;

class CurrentUserViewComposer
{
    /**
     * @var Authentication
     */
    private $auth;

    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

    public function compose(View $view)
    {
        $view->with('currentUser', Auth::user());
    }
}
