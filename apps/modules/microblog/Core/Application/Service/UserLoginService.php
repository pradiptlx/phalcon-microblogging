<?php


namespace Dex\Microblog\Core\Application\Service;

use Dex\Microblog\Core\Application\Request\UserLoginRequest;
use Dex\Microblog\Core\Application\Response\UserLoginResponse;
use Dex\Microblog\Core\Domain\Exception\InvalidUsernameDomainException;
use Dex\Microblog\Core\Domain\Repository\UserRepository;
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

        if ($userModel instanceof InvalidUsernameDomainException)
            return new UserLoginResponse($userModel, $userModel->getMessage(), 500, true);
        elseif (is_null($userModel))
            return new UserLoginResponse(null, "User Not Found", 200, true);

        $res = $this->verifyPassword($password, $userModel->getPassword());
        if ($res) {
            $this->session->set('userModel', $userModel);
            $this->session->set('user_id', $userModel->getId()->getId());
            $this->session->set('username', $userModel->getUsername());
            $this->session->set('fullname', $userModel->getFullname());
            return new UserLoginResponse($userModel, 'Login Success', 200, false);
        } else {
            return new UserLoginResponse($userModel, 'Wrong Password', 400, true);
        }

    }

    private function verifyPassword(string $password, string $dbPassword): bool
    {
        return password_verify($password, $dbPassword);
    }


}
