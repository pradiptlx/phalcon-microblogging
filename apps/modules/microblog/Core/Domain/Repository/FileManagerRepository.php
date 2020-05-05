<?php


namespace Dex\Microblog\Core\Domain\Repository;


use Dex\Microblog\Core\Domain\Model\FileManagerId;
use Dex\Microblog\Core\Domain\Model\FileManagerModel;

interface FileManagerRepository
{
    public function byId(FileManagerId $fileManagerId): ?FileManagerModel;

    public function saveFile(FileManagerModel $fileManagerModel);

    public function getPost(FileManagerId $fileManagerId);


}
