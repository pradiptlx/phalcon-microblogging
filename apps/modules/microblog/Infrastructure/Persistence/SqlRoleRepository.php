<?php


namespace Dex\microblog\Infrastructure\Persistence;


use Dex\Microblog\Core\Domain\Model\RoleId;
use Dex\Microblog\Core\Domain\Model\RoleModel;
use Phalcon\Di\DiInterface;

class SqlRoleRepository implements \Dex\Microblog\Core\Domain\Model\Repository\RoleRepository
{
    protected DiInterface $di;

    public function __construct(DiInterface $di)
    {
        $this->di = $di;

        return $this;
    }

    public function byId(RoleId $roleId): ?RoleModel
    {
        $db = $this->di->getShared('db');

        $query = "SELECT id, role_name, permissions 
                    FROM roles
                    WHERE id = :id";

        $result = $db->fetchOne($query, \Phalcon\Db::FETCH_ASSOC, [
            'id' => $roleId->getId()
        ]);

        //TODO: FIX db type of "PERMISSIONS" in role model
        if ($result) {
            $roleModel = new RoleModel(
//                new RoleId($result['id']),
                $roleId,
                $result['role_name'],
                $result['permissions']
            );

            return $roleModel;
        }

        return null;
    }

    public function saveRole(RoleModel $role)
    {
        // TODO: Implement saveRole() method.
    }

    public function getPermission(RoleId $roleId)
    {
        // TODO: Implement getPermission() method.
    }

    public function getRoles(RoleId $roleId)
    {
        // TODO: Implement getRoles() method.
    }

    public function byName(string $rolename): ?RoleModel
    {
        $db = $this->di->getShared('db');

        $query = "SELECT id, role_name, permissions 
                    FROM roles
                    WHERE role_name LIKE '%:rolename%'";

        $result = $db->fetchOne($query, \Phalcon\Db::FETCH_ASSOC, [
            'rolename' => $rolename
        ]);

        if ($result) {
            return new RoleModel(
                new RoleId($result['id']),
                $result['role_name'],
                $result['permissions']
            );
        }

        return null;
    }
}
