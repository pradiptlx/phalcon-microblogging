<?php


namespace Dex\Microblog\Core\Application\Service;


use Dex\Microblog\Core\Application\Request\ViewReplyByPostRequest;
use Dex\Microblog\Core\Application\Response\ViewReplyByPostResponse;
use Dex\Microblog\Core\Domain\Model\ReplyPostModel;
use Dex\Microblog\Core\Domain\Repository\ReplyPostRepository;

/**
 * Class ViewReplyByPostService
 * @package Dex\Microblog\Core\Application\Service
 *
 * View All Reply in A Post
 */
class ViewReplyByPostService
{
    private ReplyPostRepository $replyPostRepository;

    public function __construct(ReplyPostRepository $replyPostRepository)
    {
        $this->replyPostRepository = $replyPostRepository;
    }

    public function execute(ViewReplyByPostRequest $request): ViewReplyByPostResponse
    {
        $postId = $request->postId;
        $repliesAllMerged = [];

        /**
         * @var array $replies
         */
        $replies = $this->replyPostRepository->byPostId($postId);

        if (is_null($replies))
            return new ViewReplyByPostResponse(null, "Not Found", 500, true);

        /**
         * @var ReplyPostModel $reply
         */
        foreach ($replies as $reply) {
            if ($reply->getReply()->isReply() === 1) {
                $repRep = $this->replyPostRepository->byPostId($reply->getReply()->getId());
                if (!empty($repRep))
                    foreach ($repRep as $re){
                        $repliesAllMerged[] = $re;
                    }
            }
//            $replies[] = $repliesAllMerged;
        }

        if (isset($repliesAllMerged)) {
            $replies = array_merge($repliesAllMerged, $replies);
        }

        return new ViewReplyByPostResponse($replies, "Not error", 200, false);
    }

}
