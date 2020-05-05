<?php


namespace Dex\microblog\Core\Application\Service;

use Dex\microblog\Core\Application\Request\UserLoginRequest;
use Dex\microblog\Core\Application\Response\UserLoginResponse;
use Dex\Microblog\Core\Domain\Model\Repository\UserRepository;
use Phalcon\Di\Injectable;

class UserLoginService extends Injectable
{

    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(UserLoginRequest $request): UserLoginResponse
    {
        $username = $request->username;
        $password = $request->password;

        $userModel = $this->userRepository->byUsername($username);

        if ($this->verifyPassword($password, $userModel->getPassword())) {
            $this->session->set('user_id', $userModel->getId()->getId());
            $this->session->set('username', $userModel->getUsername());
            $this->session->set('fullname', $userModel->getFullname());
            return new UserLoginResponse($userModel, 'Login Success', 200, false);
        }

        return new UserLoginResponse('', 'Login Failed', 400, true);
    }

    private function verifyPassword(string $password, string $dbPassword): bool
    {
        return password_verify($password, $dbPassword);
    }


}
