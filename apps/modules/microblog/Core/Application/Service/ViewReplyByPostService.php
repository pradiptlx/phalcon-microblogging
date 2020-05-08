<?php


namespace Dex\Microblog\Core\Application\Service;


use Dex\Microblog\Core\Application\Request\ViewReplyByPostRequest;
use Dex\Microblog\Core\Application\Response\ViewReplyByPostResponse;
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

        $replies = $this->replyPostRepository->byPostId($postId);

        return new ViewReplyByPostResponse($replies, "Not error", 200, false);
    }

}