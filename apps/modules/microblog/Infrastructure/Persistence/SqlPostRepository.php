<?php


namespace Dex\microblog\Infrastructure\Persistence;


use Dex\Microblog\Core\Domain\Model\PostId;
use Dex\Microblog\Core\Domain\Model\PostModel;

class SqlPostRepository implements \Dex\Microblog\Core\Domain\Model\Repository\PostRepository
{

    public function byId(PostId $postId): ?PostModel
    {
        // TODO: Implement byId() method.
    }

    public function savePost(PostModel $post)
    {
        // TODO: Implement savePost() method.

        return false;
    }

    public function getTitle(PostId $postId): ?PostModel
    {
        // TODO: Implement getTitle() method.
    }

    public function getFile(PostId $postId): ?PostModel
    {
        // TODO: Implement getFile() method.
    }
}
