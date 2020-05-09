<?php


namespace Dex\Microblog\Core\Domain\Repository;


use Dex\Microblog\Core\Domain\Model\FileManagerId;
use Dex\Microblog\Core\Domain\Model\FileManagerModel;
use Dex\Microblog\Core\Domain\Model\PostId;

interface FileManagerRepository
{
    public function byId(FileManagerId $fileManagerId): ?FileManagerModel;

    public function byPostId(string $postId);

    public function saveFile(FileManagerModel $fileManagerModel);

    public function getPost(FileManagerId $fileManagerId);

    public function deleteFileByPost(PostId $postId);

}
