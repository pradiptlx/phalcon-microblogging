<?php


namespace Dex\microblog\Infrastructure\Persistence;


use Dex\Microblog\Core\Domain\Model\Repository\UserRepository;
use Dex\Microblog\Core\Domain\Model\RoleId;
use Dex\Microblog\Core\Domain\Model\RoleModel;
use Dex\Microblog\Core\Domain\Model\UserId;
use Dex\Microblog\Core\Domain\Model\UserModel;
use Phalcon\Di\DiInterface;
use Phalcon\Db;

class SqlUserRepository implements UserRepository
{
    protected DiInterface $di;

    public function __construct(DiInterface $di)
    {
        $this->di = $di;
    }

    public function byId(UserId $id): ?UserModel
    {
        $db = $this->di->getShared('db');

        $query = "SELECT id, username, fullname, email, password, role_id
                    FROM users
                    WHERE id = :id";
        $result = $db->fetchOne($query, Db::FETCH_ASSOC, [
            'id' => $id->getId()
        ]);

        if ($result) {
            // ROLE MODEL
            $roleModel = new SqlRoleRepository($this->di);
            $roleId = new RoleId($result['role_id']);

            $user = new UserModel(
                new UserId($result['id']),
                $result['username'],
                $result['fullname'],
                $result['email'],
                $result['password'],
                $roleModel->byId($roleId)
            );

            return $user;
        }

        return null;
    }

    public function saveUser(UserModel $user)
    {
        $db = $this->di->getShared('db');

        $query = "INSERT INTO users(
                    id, username, fullname, email, password, role_id
                    ) VALUES (
                    :id, :username, :fullname, :email, :password, :role_id
                    )";

        $result = $db->query($query, [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'fullname' => $user->getFullname(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'role_id' => $user->getRoleModel()->getRoleId()->getId()
        ]);

        if ($result)
            return true;
        return false;
    }
}
