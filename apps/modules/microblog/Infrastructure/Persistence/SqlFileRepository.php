<?php


namespace Dex\Microblog\Infrastructure\Persistence;


use Dex\Microblog\Core\Domain\Model\FileManagerId;
use Dex\Microblog\Core\Domain\Model\FileManagerModel;
use Dex\Microblog\Core\Domain\Model\PostId;
use Dex\Microblog\Core\Domain\Model\PostModel;
use Dex\Microblog\Core\Domain\Model\UserId;
use Dex\Microblog\Core\Domain\Model\UserModel;
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
            if (!mkdir($fileManagerModel->getPath(), 0755, true))
                throw new Failed('Error create directory');

            $transx = (new Manager())->get();
            $fileRecord = new FileManagerRecord();
            $fileRecord->id = $fileManagerModel->getId()->getId();
            $fileRecord->file_name = $fileManagerModel->getFileName();
            $fileRecord->path = $fileManagerModel->getPath();
            $fileRecord->post_id = $fileManagerModel->getPost()->getId()->getId();

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

        $query = "SELECT f.id, f.path, f.file_name, f.post_id, u.id AS userId, u.username, u.fullname,
                        u.email, p.title, p.content
                        FROM Dex\Microblog\Infrastructure\Persistence\Record\FileManagerRecord f
                        JOIN Dex\Microblog\Infrastructure\Persistence\Record\PostRecord p on f.post_id = p.id
                        JOIN Dex\Microblog\Infrastructure\Persistence\Record\UserRecord u on u.id=p.user_id
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
                new PostModel(
                    new PostId($file->post_id),
                    $file->title,
                    $file->content,
                    new UserModel(
                        new UserId($file->userId),
                        $file->username,
                        $file->fullname,
                        $file->email,
                        ""
                    )
                )
            );
        }

        return $fileModels;
    }

    public function deleteFileByPost(PostId $postId)
    {
        $fileRecord = FileManagerRecord::findByPostId($postId->getId());
        $paths = [];

        foreach ($fileRecord as $file) {
            $paths[] = $file->path;
        }

        $transx = (new Manager())->get();

        if ($fileRecord->delete()) {
            foreach ($paths as $path) {
                if (strpos($path, "/") === 0) {
                    $path = substr_replace($path, '', 0, 1);
                }

                if (!unlink($path)) {
                    $transx->rollback();
                    return new Failed('Can not delete file');
                }
            }

            $transx->commit();
            return true;
        }

        return false;
    }
}
