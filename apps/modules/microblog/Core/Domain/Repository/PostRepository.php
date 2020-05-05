<?php


namespace Dex\Microblog\Core\Domain\Repository;


use Dex\Microblog\Core\Domain\Model\PostId;
use Dex\Microblog\Core\Domain\Model\PostModel;
use Dex\Microblog\Core\Domain\Model\UserId;

interface PostRepository
{
    public function byId(PostId $postId): ?PostModel;

    public function byUserId(UserId $userId): ?PostModel;

    public function getAll();

    public function incCounter(): ?PostModel;

    public function decCounter(): ?PostModel;

    public function savePost(PostModel $post);

    public function getTitle(PostId $postId): ?PostModel;

    public function getFile(PostId $postId): ?PostModel;
}
