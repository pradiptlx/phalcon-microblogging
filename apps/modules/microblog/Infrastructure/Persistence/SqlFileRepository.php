<?php


namespace Dex\microblog\Infrastructure\Persistence;


use Dex\microblog\Core\Domain\Model\FileManagerId;
use Dex\Microblog\Core\Domain\Model\FileManagerModel;

class SqlFileRepository implements \Dex\microblog\Core\Domain\Repository\FileManagerRepository
{

    public function byId(FileManagerId $fileManagerId): ?FileManagerModel
    {
        // TODO: Implement byId() method.
    }

    public function saveFile(FileManagerModel $fileManagerModel)
    {
        // TODO: Implement saveFile() method.

        return false;
    }

    public function getPost(FileManagerId $fileManagerId)
    {
        // TODO: Implement getPost() method.
    }
}
