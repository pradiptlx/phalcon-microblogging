<?php
declare(strict_types=1);

namespace Dex\Microblog\Controller;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        return $this->response->redirect('/home');
    }

    public function fourOhFourAction()
    {
        return $this->view->pick('fourohfour');
    }

    public function fiveOhZero()
    {
        return $this->view->pick('fiveohzero');
    }

}

