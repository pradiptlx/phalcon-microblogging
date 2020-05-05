<?php


namespace Dex\Microblog\Presentation\Web\Controller;

use Dex\Microblog\Core\Application\Request\CreateUserAccountRequest;
use Dex\Microblog\Core\Application\Request\UserLoginRequest;
use Dex\Microblog\Core\Application\Service\CreateUserAccountService;
use Dex\Microblog\Core\Application\Service\UserLoginService;
use Phalcon\Mvc\Controller;

class UserController extends Controller
{

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

            $userLoginService = new UserLoginService($this->di->get('sqlUserRepository'));

            $response = $userLoginService->execute($userLoginRequest);

            $response->getError() ? $this->flashSession->error($response->getMessage()) :
                $this->flashSession->success($response->getMessage());

            var_dump($response);
            die();
//            return $this->response->redirect('/');
        }

        return $this->view->pick("user/login");
    }

    public function logoutAction()
    {

    }

}
