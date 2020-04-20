<?php


namespace Dex\microblog\Core\Application\Service;


use Dex\microblog\Core\Application\CreateUserAccountRequest;
use Dex\Microblog\Core\Domain\Model\Repository\RoleRepository;
use Dex\Microblog\Core\Domain\Model\Repository\UserRepository;
use Dex\Microblog\Core\Domain\Model\RoleId;
use Dex\Microblog\Core\Domain\Model\RoleModel;
use Dex\Microblog\Core\Domain\Model\UserId;
use Dex\Microblog\Core\Domain\Model\UserModel;
use Dex\microblog\Infrastructure\Persistence\SqlRoleRepository;
use Phalcon\Di\Injectable;

class CreateUserAccountService extends Injectable
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

    public function execute(CreateUserAccountRequest $request): bool
    {
        //TODO: FIX role creation
        $roleModel = new SqlRoleRepository($this->di);
        $roleId = new RoleId();

        $this->di->setShared('roleId', $roleId);

        $user = new UserModel(
            $request->userId,
            $request->username,
            $request->fullname,
            $request->email,
            $request->password,
            $roleModel->byId($roleId)
        );

        $result = $this->userRepository->saveUser($user);
        if ($result)
            return true;

        return false;
    }

}
