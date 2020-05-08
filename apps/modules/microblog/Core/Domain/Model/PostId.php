<?php


namespace Dex\Microblog\Core\Domain\Model;


use Ramsey\Uuid\Uuid;

class PostId
{
    /**
     * @var string $id
     */
    protected string $id;

    public function __construct($id = "")
    {
        $this->id = $id ?: Uuid::uuid4()->toString();
    }

    public function getId()
    {
        return $this->id;
    }

    public function isEqual(PostId $postId): bool
    {
        return $this->id === $postId;
    }

}
