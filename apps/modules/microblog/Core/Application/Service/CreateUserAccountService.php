<?php


namespace Dex\microblog\Core\Application\Service;


use Dex\microblog\Core\Application\CreateUserAccountRequest;
use Dex\Microblog\Core\Domain\Model\Repository\RoleRepository;
use Dex\Microblog\Core\Domain\Model\Repository\UserRepository;

class CreateUserAccountService
{

    private UserRepository $userRepository;
    private RoleRepository $roleRepository;

    public function __construct(
        UserRepository $userRepository,
        RoleRepository $roleRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;

    }

    public function execute(CreateUserAccountRequest $request) {

        $user = $this->userRepository->byId($request->userId);

        // TODO: implement repository

    }

}
