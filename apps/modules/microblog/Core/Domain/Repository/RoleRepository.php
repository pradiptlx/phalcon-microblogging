<?php


namespace Dex\Microblog\Core\Domain\Model\Repository;


use Dex\Microblog\Core\Domain\Model\RoleId;
use Dex\Microblog\Core\Domain\Model\RoleModel;

interface RoleRepository
{
    public function byId(RoleId $roleId): ?RoleModel;

    public function saveRole(RoleModel $role);

    public function getPermission(RoleId $roleId);

    public function getRoles(RoleId $roleId);
}
