<?php


namespace Dex\Microblog\Core\Domain\Model;

use Phalcon\Mvc\Model;

class UserModel
{

    /**
     * @var UserId $id
     * Generated by UUID
     */
    protected UserId $id;

    protected string $username;

    protected string $fullName;

    protected string $email;

    protected string $numberOfPost;

    /**
     * @var RoleModel $roleUser
     *
     */
    protected RoleModel $roleUser;

    /**
     * UserModel constructor.
     * @param UserId $id
     * @param string $username
     * @param string $fullName
     * @param string $email
     * @param RoleModel $roleUser
     */
    public function __construct(
        UserId $id,
        string $username,
        string $fullName,
        string $email,
        RoleModel $roleUser
    )
    {
        $this->id = $id;
        $this->username = $username;
        $this->fullName = $fullName;
        $this->email = $email;
        $this->roleUser = $roleUser;

    }

    public function getRoleUser()
    {
        return $this->roleUser->getRole();
    }

    public function getPermissionUser()
    {

        return $this->roleUser->getPermission();
    }

    // TODO: Setter role
    public function setRoleUser()
    {

    }

    // TODO: Setter perm user
    public function setPermissionUser()
    {

    }

}
