<?php


namespace Dex\Microblog\Core\Application\Service;


use Dex\Microblog\Core\Application\Request\CreateReplyRequest;
use Dex\Microblog\Core\Application\Response\CreateReplyResponse;
use Dex\Microblog\Core\Domain\Model\PostId;
use Dex\Microblog\Core\Domain\Model\PostModel;
use Dex\Microblog\Core\Domain\Model\ReplyPostModel;
use Dex\Microblog\Core\Domain\Model\UserId;
use Dex\Microblog\Core\Domain\Model\UserModel;
use Dex\Microblog\Core\Domain\Repository\PostRepository;
use Dex\Microblog\Core\Domain\Repository\ReplyPostRepository;
use Phalcon\Mvc\Model\Transaction\Failed;
use Ramsey\Uuid\Uuid;

class CreateReplyService extends \Phalcon\Di\Injectable
{
    private ReplyPostRepository $replyPostRepository;
    private PostRepository $postRepository;

    public function __construct(
        ReplyPostRepository $replyPostRepository,
        PostRepository $postRepository
    )
    {
        $this->replyPostRepository = $replyPostRepository;
        $this->postRepository = $postRepository;
    }

    public function execute(CreateReplyRequest $request): CreateReplyResponse
    {
        if (!$this->session->has('userModel')) {
            return new CreateReplyResponse(null, 'You must login', 403, true);
        }

        $userModel = $this->session->get('userModel');

        // REPLY
        $postModel = new PostModel(
            new PostId(),
            $request->title,
            $request->content,
            $userModel,
        );

        $originalPostModel = new PostModel(
            new PostId($request->postId),
            $request->postObject->title,
            $request->postObject->content,
            new UserModel(
                new UserId($request->userId),
                $request->postObject->username,
                $request->postObject->fullname,
                '',
                ''
            ),
            $request->postObject->repost_counter,
            $request->postObject->share_counter,
            $request->reply_counter,
            $request->postObject->created_at
        );

        $replyModel = new ReplyPostModel(
            Uuid::uuid4()->toString(),
            $postModel,
            $request->postId
        );

        $responsePost = $this->postRepository->savePost($postModel, 1, $originalPostModel);
        if ($responsePost instanceof Failed)
            return new CreateReplyResponse($responsePost, $responsePost->getMessage(), 500, true);

        $responseReply = $this->replyPostRepository->save($replyModel);
        if ($responseReply instanceof Failed)
            return new CreateReplyResponse($responseReply, $responseReply->getMessage(), 500, true);

        return new CreateReplyResponse(null, 'Create Reply Success', 200, false);
    }

}
