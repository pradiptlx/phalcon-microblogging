<?php


namespace Dex\Microblog\Infrastructure\Persistence;


use Dex\Microblog\Core\Domain\Model\PostId;
use Dex\Microblog\Core\Domain\Model\PostModel;
use Dex\Microblog\Core\Domain\Model\ReplyPostModel;
use Dex\Microblog\Core\Domain\Model\UserId;
use Dex\Microblog\Core\Domain\Model\UserModel;
use Dex\Microblog\Core\Domain\Repository\ReplyPostRepository;
use Dex\Microblog\Infrastructure\Persistence\Record\ReplyPostRecord;
use Phalcon\Mvc\Model\Transaction\Failed;
use Phalcon\Mvc\Model\Transaction\Manager;

class SqlReplyPostRepository extends \Phalcon\Di\Injectable implements ReplyPostRepository
{

    public function byPostId(PostId $postId): array
    {
        $query = "SELECT r.id as RepId, r.post_id as RepPost, 
                u.fullname as RepFullname, u.username as RepUsername,
                p.reply_counter, p.share_counter, p.share_counter, 
                p.title, p.content, p.created_at, r.original_post_id
                FROM Dex\Microblog\Infrastructure\Persistence\Record\ReplyPostRecord r
                JOIN Dex\Microblog\Infrastructure\Persistence\Record\PostRecord p on r.post_id = p.id
                JOIN Dex\Microblog\Infrastructure\Persistence\Record\UserRecord u on u.id=p.user_id
                WHERE r.original_post_id = :id: ORDER BY p.created_at";

        $modelManager = $this->modelsManager->createQuery($query);

        $repliesTmp = $modelManager->execute([
            'id' => $postId->getId()
        ]);


        $replies = [];

        foreach ($repliesTmp as $reply) {
            $replies[] = new ReplyPostModel(
                $reply->RepId,
                new PostModel(
                    new PostId($reply->post_id),
                    $reply->title,
                    $reply->content,
                    new UserModel(
                        new UserId($reply->user_id),
                        $reply->username,
                        $reply->fullname,
                        "",
                        ""
                    ),
                    $reply->repost_counter,
                    $reply->share_counter,
                    $reply->reply_counter,
                    $reply->created_at
                ),
                $reply->original_post_id
            );

        }

        return $replies;
    }

    public function save(ReplyPostModel $replyPostModel)
    {
        $transx = (new Manager())->get();

        $replyRecord = new ReplyPostRecord();

//        $replyRecord->id = $replyPostModel->getId();
        $replyRecord->post_id = $replyPostModel->getReply()->getId()->getId();
        $replyRecord->original_post_id = $replyPostModel->getOriginalPostId();
        if ($replyRecord->save()) {
            $transx->commit();

            return true;
        }

        $transx->rollback();

        return new Failed("Failed save reply post " . $replyRecord->getMessages()[0]);
    }

    public function deleteReply(string $repId)
    {
        $transx = (new Manager())->get();

        $replyRecord = ReplyPostRecord::findFirstById($repId);

        if ($replyRecord) {
            if ($replyRecord->delete()) {
                $transx->commit();
                return true;
            }

            $transx->rollback();
        }

        return false;
    }

    public function deleteReplyByPost(PostId $postId)
    {
        $transx = (new Manager())->get();

        $replyRecord = ReplyPostRecord::findByPostId($postId->getId());

        if (isset($replyRecord)) {
            if ($replyRecord->delete()) {
                $transx->commit();

                return true;
            }

            $transx->rollback();
            return new Failed("Can't Delete Reply");
        }

        return false;
    }
}
