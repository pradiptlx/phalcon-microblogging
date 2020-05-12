<?php


namespace Dex\Microblog\Core\Domain\Model;


class ReplyPostModel
{
    protected string $id;

    protected PostModel $reply;

    protected ?string $originalPostId;

    public function __construct(
        string $id,
        PostModel $reply,
        string $originalPostId = null
    )
    {
        $this->id = $id;
        $this->reply = $reply;
        $this->originalPostId = $originalPostId;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getReply(): PostModel
    {
        return $this->reply;
    }

    public function getOriginalPostId(): ?string
    {
        return $this->originalPostId;
    }

}
