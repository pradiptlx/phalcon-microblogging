<?php


namespace Dex\Microblog\Presentation\Web\Controller;


use Dex\Microblog\Core\Application\Service\ShowAllPostService;
use Phalcon\Mvc\Controller;

class PostController extends Controller
{
    private ShowAllPostService $showAllPostService;

    public function initialize()
    {
        $this->showAllPostService = $this->di->get('showAllService');

        if (!$this->session->has('user_id')) {
            $this->response->redirect('/user/login');
        }

        if($this->session->has('username'))
            $this->view->setVar('username', $this->session->get('username'));

        $postCssCollection = $this->assets->collection('postCss');
        $postCssCollection->addCss('/css/main.css');
    }

    /**
     * GET only
     */
    public function indexAction()
    {
        $this->view->setVar('title', 'Home');

        $response = $this->showAllPostService->execute();

        if(!$response->getError()){
            $this->view->setVar('posts', $response->getData());
            $this->view->setVar('totalPost', sizeof($response->getData()));
        }

        return $this->view->pick('post/home');
    }

}
