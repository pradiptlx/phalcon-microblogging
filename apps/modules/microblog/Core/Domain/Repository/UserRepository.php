<?php


namespace Dex\Microblog\Core\Domain\Model\Repository;

use Dex\Microblog\Core\Domain\Model\UserModel;

interface UserRepository {
    public function byId(int $id): ?UserModel;
    public function saveUser(UserModel $user);
}