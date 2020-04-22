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

    public function loginUser(string $username, string $password): ?UserModel
    {
        $db = $this->di->getShared('db');

        $query = "SELECT id, username, fullname, email, password, role_id
                    FROM user
                    WHERE username=:username AND password=:password";

        $result = $db->query($query, Db::FETCH_ASSOC, [
            'username' => $username,
            'password' => password_hash($password, PASSWORD_BCRYPT)
        ]);

        $role_query = "SELECT id, rolename, permissions
                FROM roles
                WHERE id=:id";

        $result_role = $db->query($role_query, Db::FETCH_ASSOC, [
            'id' => $result['role_id']
        ]);

        if($result && $result_role){
            return new UserModel(
                new UserId($result['id']),
                $result['username'],
                $result['fullname'],
                $result['email'],
                $result['password'],
                new RoleModel(
                    new RoleId($result['role_id']),
                    $result_role['rolename'],
                    $result_role['permissions']
                )
            );
        }

        return null;
    }


}
