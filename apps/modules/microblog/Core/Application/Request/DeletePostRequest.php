<?php


namespace Dex\Microblog\Core\Application\Request;


class DeletePostRequest
{
    public string $postId;

    public function __construct(string $postId)
    {
        $this->postId = $postId;
    }

}
