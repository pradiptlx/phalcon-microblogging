<?php


namespace Dex\Microblog\Core\Domain\Model;

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

    protected string $password;

    protected string $role_id;

    protected string $created_at;

    protected string $updated_at;

    /**
     * UserModel constructor.
     * @param UserId $id
     * @param string $username
     * @param string $fullName
     * @param string $email
     * @param string $password
     */
    public function __construct(
        UserId $id,
        string $username,
        string $fullName,
        string $email,
        string $password
    )
    {
        $this->id = $id;
        $this->username = $username;
        $this->fullName = $fullName;
        $this->email = $email;
        $this->password = $password;
        $this->role_id = 'kosong';

    }

    // public function getRoleUser()
    // {
    //     return $this->roleUser->getRole();
    // }

    // public function getPermissionUser()
    // {

    //     return $this->roleUser->getPermission();
    // }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getFullname(): string
    {
        return $this->fullName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    // public function getRoleModel(): RoleModel
    // {
    //     return $this->roleUser;
    // }

    /**
     * @param string $role
     * @return RoleModel
     */
    // TODO: Setter role
    // public function setRoleUser(string $role): RoleModel
    // {
    //     $this->roleUser->setRole($role);

    //     return $this->roleUser;
    // }

    // /**
    //  * @param array $perm
    //  */
    // // TODO: Setter perm user
    // public function setPermissionUser(array $perm = null)
    // {
    //     $this->roleUser->setListPermission($perm);
    // }

}
