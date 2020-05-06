<?php


namespace Dex\Microblog\Core\Application\Service;

use Dex\Microblog\Core\Application\Request\CreateUserAccountRequest;
use Dex\Microblog\Core\Application\Response\CreateUserAccountResponse;
use Dex\Microblog\Core\Domain\Repository\UserRepository;
use Dex\Microblog\Core\Domain\Model\UserId;
use Dex\Microblog\Core\Domain\Model\UserModel;
use Phalcon\Di\Injectable;

class CreateUserAccountService extends Injectable
{

    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(CreateUserAccountRequest $request): CreateUserAccountResponse
    {
        //TODO: FIX role creation

        $userModel = new UserModel(
            $request->userId,
            $request->username,
            $request->fullname,
            $request->email,
            $request->password,
        );

        $result = $this->userRepository->saveUser($userModel);
        if ($result){
            $this->session->set('user_id', $userModel->getId()->getId());
            $this->session->set('username', $userModel->getUsername());
            $this->session->set('fullname', $userModel->getFullname());
            return new CreateUserAccountResponse($userModel,'Akun berhasil dibuat',200,false);
        }
        else{
            return new CreateUserAccountResponse($userModel,'Akun gagal dibuat',500,true);
        }
    }

}
