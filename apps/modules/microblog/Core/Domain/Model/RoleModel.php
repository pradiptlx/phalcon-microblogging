<?php


namespace Dex\Microblog\Core\Domain\Model;

class RoleModel
{

    // Role
    public static string $USER_ADMIN = 'admin';
    public static string $USER_GUEST = 'guest';
    public static string $USER_GENERAL = 'general';

    // Permission
    public static string $PERM_READ = 'read';
    public static string $PERM_WRITE = 'write';
    public static string $PERM_COMMENT = 'comment';

    /**
     * @var string $roleName
     * Example: Admin/author, guest, general user
     */
    protected string $roleName;

    /**
     * @var string $permissionLevel
     * Example: 1->can do everything, 2->can't post, 3-> can't comment
     */
    protected string $permissionLevel;

    protected array $listOfPermission;

    protected bool $isActive;

    public function setRole(string $role)
    {
        switch ($role) {
            case RoleModel::$USER_ADMIN:
                $this->roleName = RoleModel::$USER_ADMIN;

                // TODO: SET ADMIN PRIVILEGE
                break;

            case RoleModel::$USER_GENERAL:
                $this->roleName = RoleModel::$USER_GENERAL;

                //TODO: SET GENERAL
                break;

            case RoleModel::$USER_GUEST:
                $this->roleName = RoleModel::$USER_GUEST;

                // TODO: SET GUEST
                break;
        }
    }

    public function getRole() {

        return $this->roleName;
    }

    public function getPermission()
    {
        // TODO: FIX permission getter
        return $this->listOfPermission;

    }


}
