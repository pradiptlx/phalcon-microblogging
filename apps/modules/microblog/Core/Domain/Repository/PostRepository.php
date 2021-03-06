<?php


namespace Dex\Microblog\Core\Domain\Repository;


use Dex\Microblog\Core\Domain\Model\PostId;
use Dex\Microblog\Core\Domain\Model\PostModel;
use Dex\Microblog\Core\Domain\Model\UserId;

interface PostRepository
{
    public function byId(PostId $postId): ?PostModel;

    public function byUserId(UserId $userId);

    public function getAll();

    public function savePost(PostModel $post, int $isReply = 0, PostModel $originalPost = null);

    public function deletePost(PostId $postId);

}
