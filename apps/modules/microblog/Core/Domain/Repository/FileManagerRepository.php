<?php


namespace Dex\microblog\Core\Domain\Repository;


use Dex\microblog\Core\Domain\Model\FileManagerId;
use Dex\Microblog\Core\Domain\Model\FileManagerModel;

interface FileManagerRepository
{
    public function byId(FileManagerId $fileManagerId): ?FileManagerModel;

    public function saveFile();

    public function getPost(FileManagerId $fileManagerId);


}
