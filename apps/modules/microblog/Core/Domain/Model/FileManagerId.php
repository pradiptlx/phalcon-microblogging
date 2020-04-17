<?php


namespace Dex\microblog\Core\Domain\Model;


use Ramsey\Uuid\Uuid;

class FileManagerId
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

    public function isEqual(FileManagerId $fileManagerId): bool
    {
        return $this->id === $fileManagerId;
    }

}
