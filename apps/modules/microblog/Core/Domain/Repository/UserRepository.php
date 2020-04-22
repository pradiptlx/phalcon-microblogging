<?php


namespace Dex\Microblog\Core\Domain\Model\Repository;

use Dex\Microblog\Core\Domain\Model\UserId;
use Dex\Microblog\Core\Domain\Model\UserModel;

interface UserRepository
{
    public function byId(UserId $id): ?UserModel;

    public function saveUser(UserModel $user);

    public function loginUser(string $username, string $password): ?UserModel;
}
