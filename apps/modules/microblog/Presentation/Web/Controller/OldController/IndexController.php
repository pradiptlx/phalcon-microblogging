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
        $this->view->title = 'Whoops!';
        return $this->view->pick('fourohfour');
    }

    public function fiveOhZero()
    {
        $this->view->title = 'Whoops!';
        return $this->view->pick('fiveohzero');
    }


}

