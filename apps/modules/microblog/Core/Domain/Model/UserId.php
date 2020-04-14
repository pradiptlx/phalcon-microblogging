<?php


namespace Dex\Microblog\Core\Domain\Model;

use Ramsey\Uuid\Uuid;

class UserId
{

    private string $id;

    public function __construct($id = null)
    {
        $this->id = $id ?: Uuid::uuid4()->toString();
    }

    public function getId() {
        return $this->id;
    }

    // TODO: Create post->userId === $this->>id relation
//    public function equals()

}
