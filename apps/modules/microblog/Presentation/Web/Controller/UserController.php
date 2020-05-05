<?php


namespace Dex\Microblog\Presentation\Web\Controller;

use Dex\Microblog\Core\Application\Request\CreateUserAccountRequest;
use Dex\Microblog\Core\Application\Request\UserLoginRequest;
use Dex\Microblog\Core\Application\Service\CreateUserAccountService;
use Dex\Microblog\Core\Application\Service\UserLoginService;
use Phalcon\Mvc\Controller;

class UserController extends Controller
{
    private CreateUserAccountService $createUserAccountService;
    private UserLoginService $userLoginService;

    public function initialize()
    {
        // $this->createUserAccountService = $this->di->get('createUserAccountService');
        // $this->userLoginService = $this->di->get('userLoginService');

        if (is_null($this->router->getActionName())) {
            $this->response->redirect('user/login');
        }


        if ($this->session->has('user_id') && $this->session->has('username')) {
            $this->view->setVar('username', $this->session->get('username'));
            $this->view->setVar('user_id', $this->session->get('user_id'));
        }
    }

    public function dashboardAction()
    {
        $this->session->set('last_url', $this->router->getControllerName() . '/' . $this->router->getActionName());

        if (!$this->session->has('user_id')) {
            $this->flashSession->error('You must login first.');

            $this->dispatcher->forward([
                'module' => 'microblog',
                'controller' => 'user',
                'action' => 'login'
            ]);
        }

        $dashboardCollection = $this->assets->collection('dashboardCss');
        $dashboardCollection->addCss('/css/profile.css');

        $user = User::query()
            ->where('id=:id:')
            ->bind([
                'id' => $this->session->get('user_id')
            ])
            ->execute()->getFirst();

        $userPosts = Post::query()
            ->where('user_id=:user_id:')
            ->bind(
                [
                    'user_id' => $this->session->get('user_id')
                ]
            )
            ->execute();

        $this->view->setVar('posts', $userPosts);
        $this->view->setVar('self', true);
        $this->view->setVar('user', $user);
        $this->view->setVar('title', 'Dashboard');
        $this->view->pick('user/dashboard');
    }

    public function registerAction()
    {
        if ($this->request->isPost()) {
//            $service = new CreateUserAccountService()
            $request = new CreateUserAccountRequest(
                $this->request->getPost('username', 'string'),
                $this->request->getPost('fullname', 'string'),
                $this->request->getPost('email', 'string'),
                password_hash($this->request->getPost('password', 'string'), PASSWORD_BCRYPT)
            );

            // TODO: Create role registration
            $service = new CreateUserAccountService(
                $this->di->getShared('sqlUserRepository'),
                $this->di->getShared('sqlRoleRepository')
            );

            if ($service->execute($request)) {
                // TODO: View success
                $this->response->setStatusCode(200, 'Success');
            } else {
                // TODO: view failed
                $this->response->setStatusCode(400, 'Bad request');
            }
        }
    }

    public function loginAction()
    {
        $headerCollection = $this->assets->collection('headerCss');
        $headerCollection->addCss('/css/login/main.css');
        $headerCollection->addCss('/css/login/index.css');
        $this->view->setVar('title', 'Login Page');

        $request = $this->request;

        if ($request->isPost()) {
            $username = $request->getPost('username', 'string');
            $password = $request->getPost('password', 'string');

            $userLoginRequest = new UserLoginRequest(
                $username,
                $password
            );

            $response = $this->userLoginService->execute($userLoginRequest);

            if ($response->getError()) {
                $this->flashSession->error($response->getMessage());
                return $this->response->redirect('');
            } else {
                $this->flashSession->success($response->getMessage());
            }
            return $this->response->redirect('/');
        }

        return $this->view->pick("user/login");
    }

    public function logoutAction()
    {
        if ($this->di->has('user')) {
            $this->di->remove('user');
        }
        if ($this->session->has('user_id')) {
            $this->session->remove('user_id');
        }

        if ($this->session->has('username'))
            $this->session->remove('username');

        if ($this->session->has('fullname'))
            $this->session->remove('fullname');

        if ($this->session->has('last_url'))
            $this->session->remove('last_url');

        $this->flashSession->success("Successfully logout");
        return $this->response->redirect('login');
    }

}
