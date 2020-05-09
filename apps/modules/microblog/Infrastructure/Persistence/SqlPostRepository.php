<?php


namespace Dex\Microblog\Infrastructure\Persistence;


use Dex\Microblog\Core\Domain\Model\PostId;
use Dex\Microblog\Core\Domain\Model\PostModel;
use Dex\Microblog\Core\Domain\Model\UserId;
use Dex\Microblog\Core\Domain\Model\UserModel;
use Dex\Microblog\Core\Domain\Repository\PostRepository;
use Dex\Microblog\Infrastructure\Persistence\Record\PostRecord;
use Phalcon\Di;
use Phalcon\Mvc\Model\Transaction\Failed;
use Phalcon\Mvc\Model\Transaction\Manager;

class SqlPostRepository extends Di\Injectable implements PostRepository
{
    /*private function parsingRecord(PostRecord $postRecord): ?PostModel
    {
        return new PostModel(
            new PostId($postRecord->id),
            $postRecord->title,
            $postRecord->content,
            new UserModel(
                new PostId($postRecord->user_id),
                $postRecord->
            ),
            $postRecord->repost_counter,
            $postRecord->share_counter,
            $postRecord->reply_counter
        );

    }*/

    public function byId(PostId $postId): ?PostModel
    {
        $query = $this->modelsManager->createQuery(
            "SELECT p.id, p.title, p.content, p.created_at,
                p.repost_counter, p.share_counter, p.reply_counter, u.fullname, p.user_id, u.username,
                u.email, u.password
                FROM Dex\Microblog\Infrastructure\Persistence\Record\PostRecord p
                JOIN Dex\Microblog\Infrastructure\Persistence\Record\UserRecord u on p.user_id = u.id
                WHERE p.id = :id:"
        );

        $postRecords = $query->execute(
            [
                'id' => $postId->getId()
            ]
        );

        foreach ($postRecords as $postRecord) {
            return new PostModel(
                new PostId($postRecord->id),
                $postRecord->title,
                $postRecord->content,
                new UserModel(
                    new UserId($postRecord->user_id),
                    $postRecord->username,
                    $postRecord->fullname,
                    $postRecord->email,
                    $postRecord->password,
                ),
                $postRecord->repost_counter,
                $postRecord->reply_counter,
                $postRecord->share_counter,
                $postRecord->created_at
            );
        }
    }

    public function byUserId(UserId $userId): array
    {
        $query = $this->modelsManager->createQuery(
            'SELECT p.id, p.title, p.content, p.created_at, p.updated_at, p.repost_counter, 
                    p.share_counter, p.reply_counter, u.fullname, u.username, p.user_id,
                    u.email, u.password
                    FROM Dex\Microblog\Infrastructure\Persistence\Record\PostRecord p
                    JOIN Dex\Microblog\Infrastructure\Persistence\Record\UserRecord u 
                    on p.user_id = :userId:'
        );

        $postsRecord = $query->execute(
            [
                'userId' => $userId->getId()
            ]
        );

        $posts = [];
        foreach ($postsRecord as $post) {
            $posts[] = new PostModel(
                new PostId($post->id),
                $post->title,
                $post->content,
                new UserModel(
                    new UserId($post->user_id),
                    $post->username,
                    $post->fullname,
                    $post->email,
                    $post->password
                ),
                $post->repost_counter,
                $post->share_counter,
                $post->reply_counter,
                $post->created_at
            );
        }

        return $posts;
    }


    public function getAll(): array
    {
        $query = $this->modelsManager->createQuery(
            'SELECT p.id, p.title, p.content, p.created_at, p.updated_at, p.repost_counter, 
                    p.share_counter, p.reply_counter, u.fullname, u.username, p.user_id,
                    u.email, u.password
                    FROM Dex\Microblog\Infrastructure\Persistence\Record\PostRecord p
                    JOIN Dex\Microblog\Infrastructure\Persistence\Record\UserRecord u 
                    on p.user_id = u.id'
        );

        $postsRecord = $query->execute();

        $posts = [];
        foreach ($postsRecord as $post) {
            $posts[] = new PostModel(
                new PostId($post->id),
                $post->title,
                $post->content,
                new UserModel(
                    new UserId($post->user_id),
                    $post->username,
                    $post->fullname,
                    $post->email,
                    $post->password
                ),
                $post->repost_counter,
                $post->share_counter,
                $post->reply_counter,
                $post->created_at
            );
        }

        return $posts;

    }

    public function savePost(PostModel $post, int $isReply = 0)
    {
        $transx = (new Manager())->get();
        $postRecord = new PostRecord();

        $postRecord->id = $post->getId()->getId();
        $postRecord->title = $post->getTitle();
        $postRecord->content = $post->getContent();
        $postRecord->created_at = (new \DateTime())->format('Y-m-d H:i:s');
        $postRecord->updated_at = (new \DateTime())->format('Y-m-d H:i:s');
        $postRecord->user_id = $post->getUser()->getId()->getId();
        $postRecord->isReply = $isReply;
        $postRecord->share_counter = $post->getShareCounter();
        $postRecord->repost_counter = $post->getRepostCounter();
        if ($isReply) {
            $postRecord->reply_counter = $post->incReplyCounter();
        } else {
            $postRecord->reply_counter = $post->getReplyCounter();
        }

        if ($postRecord->save()) {
            $transx->commit();

            return true;
        }

        $transx->rollback();

        return new Failed("Failed create post");
    }

    public function deletePost(PostId $postId)
    {
        $postRecord = PostRecord::findFirstById($postId->getId());

        $title = $postRecord->title;

        $transx = (new Manager())->get();

        if ($postRecord->delete()) {
            $transx->commit();
            return true;
        }

        $transx->rollback();

        return new Failed("Failed Delete Post " . $title);
    }

    public function getTitle(PostId $postId): ?PostModel
    {
        // TODO: Implement getTitle() method.
    }

    public function getFile(PostId $postId): ?PostModel
    {
        // TODO: Implement getFile() method.
    }

    public function incCounter(): ?PostModel
    {
        // TODO: Implement incCounter() method.
    }

    public function decCounter(): ?PostModel
    {
        // TODO: Implement decCounter() method.
    }
}
