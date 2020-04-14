<?php


namespace Dex\Microblog\Core\Domain\Model\Repository;


use Dex\Microblog\Core\Domain\Model\RoleModel;

interface RoleRepository
{
    public function saveRole(RoleModel $role): ?RoleModel;
}
