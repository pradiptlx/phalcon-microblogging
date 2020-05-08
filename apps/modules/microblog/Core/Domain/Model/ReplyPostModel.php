<?php


namespace Dex\Microblog\Core\Domain\Model;


class ReplyPostModel
{
    protected string $id;

    protected PostModel $post;

    protected UserModel $user;

    public function __construct(
        string $id,
        PostModel $post,
        UserModel $user
    )
    {
        $this->id = $id;
        $this->post = $post;
        $this->user = $user;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPost(): PostModel
    {
        return $this->post;
    }

    public function getUser(): UserModel
    {
        return $this->user;
    }

}
