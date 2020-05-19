<?php


namespace Dex\Microblog\Core\Application\Service;

use Dex\Microblog\Core\Application\Request\ForgotPasswordLoginRequest;
use Dex\Microblog\Core\Application\Response\ForgotPasswordLoginResponse;
use Dex\Microblog\Core\Domain\Exception\InvalidUsernameDomainException;
use Dex\Microblog\Core\Domain\Repository\UserRepository;
use Phalcon\Di\Injectable;

class ForgotPasswordLoginService extends Injectable
{

    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(ForgotPasswordLoginRequest $request): ForgotPasswordLoginResponse
    {
        $email = $request->email;

        $userModel = $this->userRepository->byEmail($email);

        if ($userModel instanceof InvalidUsernameDomainException)
            return new ForgotPasswordLoginResponse($userModel, $userModel->getMessage(), 500, true);
        elseif (is_null($userModel))
            return new ForgotPasswordLoginResponse(null, "User Not Found", 200, true);

        $this->session->set('userModel', $userModel);
        $this->session->set('user_id', $userModel->getId()->getId());
        $this->session->set('username', $userModel->getUsername());
        $this->session->set('fullname', $userModel->getFullname());

        return new ForgotPasswordLoginResponse($userModel, 'Please change your password!', 200, false);

    }


}
