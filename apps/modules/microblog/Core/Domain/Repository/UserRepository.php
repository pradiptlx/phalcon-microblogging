<?php


namespace Dex\Microblog\Core\Domain\Repository;

use Dex\Microblog\Core\Domain\Model\UserId;
use Dex\Microblog\Core\Domain\Model\UserModel;

interface UserRepository
{
    public function byId(UserId $id): ?UserModel;

    public function byUsername(string $username): ?UserModel;

    public function byEmail(string $email): ?UserModel;

    public function saveUser(UserModel $user);

    public function changeProfile(array $data, UserId $userId);

    public function getPassword(UserId $userId);

    public function searchUsername(string $keyword): ?array;
}
