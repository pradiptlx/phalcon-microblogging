<?php


namespace Dex\Microblog\Presentation\Web\Controller;


use Dex\Microblog\Core\Application\Request\ViewPostRequest;
use Dex\Microblog\Core\Application\Request\ViewReplyByPostRequest;
use Dex\Microblog\Core\Application\Service\ShowAllPostService;
use Dex\Microblog\Core\Application\Service\ViewPostService;
use Dex\Microblog\Core\Application\Service\ViewReplyByPostService;
use Phalcon\Mvc\Controller;

class PostController extends Controller
{
    private ShowAllPostService $showAllPostService;
    private ViewPostService $viewPostService;
    private ViewReplyByPostService $viewReplyByPostService;

    public function initialize()
    {
        $this->showAllPostService = $this->di->get('showAllService');
        $this->viewPostService = $this->di->get('viewPostService');
        $this->viewReplyByPostService = $this->di->get('viewReplyByPostService');

        if (!$this->session->has('user_id')) {
            $this->response->redirect('/user/login');
        }

        if ($this->session->has('user_id') && $this->session->has('username')) {
            $this->view->setVar('username', $this->session->get('username'));
            $this->view->setVar('user_id', $this->session->get('user_id'));
        }

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

        if (!$response->getError()) {
            $this->view->setVar('posts', $response->getData());
            $this->view->setVar('totalPost', sizeof($response->getData()));
        }

        return $this->view->pick('post/home');
    }

    public function viewPostAction()
    {
        $this->view->setVar('title', 'View Post');
        $request = $this->request;

        $idPost = $this->router->getParams()[0];

        if (isset($idPost)) {
            $viewRequest = new ViewPostRequest($idPost);
            $viewReplyRequest = new ViewReplyByPostRequest($idPost);

            $responsePost = $this->viewPostService->execute($viewRequest);
            $responseReply = $this->viewReplyByPostService->execute($viewReplyRequest);

            if (!$responsePost->getError() && !$responseReply->getError()) {
                $this->view->setVar('post', $responsePost->getData());
                $this->view->setVar('reply', $responseReply->getData());
                $this->view->setVar('title', $responsePost->getData()->title);

                return $this->view->pick('post/viewPost');
            }
        }

        $this->flashSession->error('Post not found');
        return $this->response->redirect('/');
    }

}
