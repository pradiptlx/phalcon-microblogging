<?php


namespace Dex\Microblog\Core\Domain\Model;

class PermissionModel {

    const USER_ADMIN = 'admin';
    const USER_GUEST = 'guest';
    const USER_GENERAL = 'general';


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

    protected array $listOfRoles;

    protected bool $isActive;


}