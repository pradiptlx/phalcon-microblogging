<?php


namespace Dex\Microblog\Core\Domain\Model;


use Ramsey\Uuid\Uuid;

class RoleId
{

    protected string $id;

    public function __construct(string $id = "")
    {
        $this->id = $id ?: Uuid::uuid4()->toString();
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
