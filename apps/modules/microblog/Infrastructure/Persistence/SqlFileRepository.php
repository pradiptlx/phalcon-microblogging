<?php


namespace Dex\Microblog\Infrastructure\Persistence;


use Dex\Microblog\Core\Domain\Model\FileManagerId;
use Dex\Microblog\Core\Domain\Model\FileManagerModel;
use Dex\Microblog\Core\Domain\Repository\FileManagerRepository;

class SqlFileRepository extends \Phalcon\Di\Injectable implements FileManagerRepository
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

    public function byPostId(string $postId)
    {

        $query = "SELECT f.*
                        FROM Dex\Microblog\Infrastructure\Persistence\Record\FileManagerRecord f
                        WHERE f.post_id=:post_id:";

        $modelManager = $this->modelsManager->createQuery($query);

        $files = $modelManager->execute(
            [
                'post_id' => $postId
            ]
        );

        $fileModels = [];
        foreach ($files as $file) {
            $fileModels[] = new FileManagerModel(
                new FileManagerId($file->id),
                $file->file_name,
                $file->path,
                $file->post_id
            );
        }

        return $fileModels;
    }
}
