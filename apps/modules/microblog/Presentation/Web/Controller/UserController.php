<?php


namespace Dex\microblog\Presentation\Web\Controller;

use Dex\microblog\Core\Application\Request\CreateUserAccountRequest;
use Dex\microblog\Core\Application\Request\UserLoginRequest;
use Dex\microblog\Core\Application\Service\CreateUserAccountService;
use Dex\microblog\Core\Application\Service\UserLoginService;
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

            if($service->execute($request)){
                // TODO: View success
                $this->response->setStatusCode(200, 'Success');
            }else{
                // TODO: view failed
                $this->response->setStatusCode(400, 'Bad request');
            }
        }
    }

    public function loginAction(){
        $request = $this->request;

        if ($request->isPost()) {
            $username = $request->getPost('username', 'string');
            $password = $request->getPost('password', 'string');

            $userLoginRequest = new UserLoginRequest(
                $username,
                $password
            );

            $userLoginService = new UserLoginService();

            if($userLoginService->execute($userLoginRequest)){
                $this->flash->success("Login Success");

                $this->response->redirect('/microblog/home');
            }else {
                $this->flash->error("Can't Login");

                $this->response->redirect('/microblog/user/login');
            }

//            return $this->view->pick("user/login");
        } else if($request->isGet()){
            $this->view->title = "Login page";

            return $this->view->pick("user/login");
        }
    }

}
