<?php


namespace Dex\Microblog\Core\Domain\Model;

class RoleModel
{

    // Role
    public static string $USER_ADMIN = 'admin';
    public static int $LEVEL_ADMIN = 0;
    public static string $USER_GUEST = 'guest';
    public static int $LEVEL_GUEST = 1;
    public static string $USER_GENERAL = 'general';
    public static int $LEVEL_GENERAL = 2;

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

    /**
     * @var array $listOfPermission
     * List of user permission
     */
    protected array $listOfPermission;

    /**
     * @var bool $isActive
     * Is role active?
     */
    protected bool $isActive;

    protected RoleId $id;

    public function __construct(
        RoleId $id,
        string $roleName,
        array $permissions
    )
    {
        $this->id = $id;
        $this->roleName = $roleName;
        $this->listOfPermission = $permissions;

    }

    public static function getRoleModel(): self {

    }

    public function setRole(string $role)
    {
        switch ($role) {
            case RoleModel::$USER_ADMIN:
                $this->roleName = RoleModel::$USER_ADMIN;

                // TODO: SET ADMIN PRIVILEGE
                $this->permissionLevel = 1;
                break;

            case RoleModel::$USER_GENERAL:
                $this->roleName = RoleModel::$USER_GENERAL;

                //TODO: SET GENERAL
                $this->permissionLevel = 2;
                break;

            case RoleModel::$USER_GUEST:
                $this->roleName = RoleModel::$USER_GUEST;

                // TODO: SET GUEST
                $this->permissionLevel = 3;
                break;

            default:
                throw new \InvalidArgumentException("Role tidak valid.");
                break;
        }
    }

    public function getRole()
    {

        return $this->roleName;
    }

    public function getPermission()
    {
        // TODO: FIX permission getter
        return $this->listOfPermission;

    }

    // TODO: Fix set permission and set additional permission
    public function setListPermission(array $additional = null)
    {
        switch ($this->roleName) {
            case RoleModel::$USER_ADMIN:
                $perm = [
                    RoleModel::$PERM_WRITE,
                    RoleModel::$PERM_READ,
                    RoleModel::$PERM_COMMENT
                ];

                $this->listOfPermission = $perm;
                break;

            case RoleModel::$USER_GENERAL:
                $perm = [
                    RoleModel::$PERM_READ,
                    RoleModel::$PERM_COMMENT
                ];

                $this->listOfPermission = $perm;
                break;

            case RoleModel::$USER_GUEST:
                $perm = [
                    RoleModel::$PERM_READ
                ];

                $this->listOfPermission = $perm;
                break;

            default:
                throw new \InvalidArgumentException("Permission tidak valid.");
                break;
        }
    }


}
