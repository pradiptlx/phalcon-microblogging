<?php


namespace Dex\Microblog\Core\Domain\Repository;


use Dex\Microblog\Core\Domain\Model\PostId;
use Dex\Microblog\Core\Domain\Model\ReplyPostModel;

interface ReplyPostRepository
{
    public function byPostId(PostId $postId);

    public function save(ReplyPostModel $replyPostModel);

    public function deleteReplyByPost(PostId $postId);

    public function deleteReply(string $repId);

}
