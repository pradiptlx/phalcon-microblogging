<?php


namespace Dex\Microblog\Core\Application\Request;


use Dex\Microblog\Core\Domain\Model\PostId;

class ViewReplyByPostRequest
{
    public PostId $postId;

    public function __construct(PostId $postId)
    {
        $this->postId = $postId;
    }

}
