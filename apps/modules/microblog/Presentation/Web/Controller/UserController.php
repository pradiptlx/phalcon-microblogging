<?php


namespace Dex\microblog\Presentation\Web\Controller;

use Dex\microblog\Core\Application\CreateUserAccountRequest;
use Dex\microblog\Core\Application\Service\CreateUserAccountService;
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

}
