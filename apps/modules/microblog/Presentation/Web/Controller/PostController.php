<?php


namespace Dex\microblog\Presentation\Web\Controller;


use Phalcon\Mvc\Controller;

class PostController extends Controller
{

    public function initialize()
    {
        if (!$this->session->has('user_id')) {
            $this->response->redirect('user/login');
        }

        $postCssCollection = $this->assets->collection('postCss');
        $postCssCollection->addCss('/css/main.css');
    }

    public function indexAction()
    {
        $this->view->setVar('title', 'Home');

        $request = $this->request;

        if($request->isPost()){

        }


    }

}
