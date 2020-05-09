<?php


namespace Dex\Microblog\Infrastructure\Persistence;


use Dex\Microblog\Core\Domain\Model\FileManagerId;
use Dex\Microblog\Core\Domain\Model\FileManagerModel;
use Dex\Microblog\Core\Domain\Repository\FileManagerRepository;
use Dex\Microblog\Infrastructure\Persistence\Record\FileManagerRecord;
use Phalcon\Mvc\Model\Transaction\Failed;
use Phalcon\Mvc\Model\Transaction\Manager;

class SqlFileRepository extends \Phalcon\Di\Injectable implements FileManagerRepository
{

    public function byId(FileManagerId $fileManagerId): ?FileManagerModel
    {
        // TODO: Implement byId() method.
    }

    public function saveFile(FileManagerModel $fileManagerModel)
    {
        try {
            if (!mkdir('files/' . $fileManagerModel->getPath(), 0755, true))
                throw new Failed('Error create directory');

            $transx = (new Manager())->get();
            $fileRecord = new FileManagerRecord();
            $fileRecord->id = $fileManagerModel->getId();
            $fileRecord->file_name = $fileManagerModel->getFileName();
            $fileRecord->path = $fileManagerModel->getPath();
            $fileRecord->post_id = $fileManagerModel->getPostId();

            if ($fileRecord->save()) {
                $transx->commit();
                return true;
            }

            $transx->rollback();
            throw new Failed((string)$transx->getMessages());

        } catch (Failed $exception) {
            return new Failed($exception->getMessage());
        }

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
