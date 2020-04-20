<?php


namespace Dex\Microblog\Core\Domain\Model;


use Ramsey\Uuid\Uuid;

class RoleId
{

    const UUID_NAMESPACE = "ROLENAME";

    protected string $id;

    public function __construct(string $id = "")
    {
        $this->id = $id ?: Uuid::uuid3(self::UUID_NAMESPACE, $id)->toString();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function isEqual(RoleId $roleId): bool
    {
        return $this->id === $roleId;
    }

}
