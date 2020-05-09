<?php


namespace Dex\Microblog\Core\Application\Service;


use Dex\Microblog\Core\Application\Request\DeletePostRequest;
use Dex\Microblog\Core\Application\Response\DeletePostResponse;
use Dex\Microblog\Core\Domain\Model\PostId;
use Dex\Microblog\Core\Domain\Repository\FileManagerRepository;
use Dex\Microblog\Core\Domain\Repository\PostRepository;
use Dex\Microblog\Core\Domain\Repository\ReplyPostRepository;
use Phalcon\Mvc\Model\Transaction\Failed;

class DeletePostService
{
    private PostRepository $postRepository;
    private ReplyPostRepository $replyPostRepository;
    private FileManagerRepository $fileManagerRepository;

    public function __construct(PostRepository $postRepository, ReplyPostRepository $replyPostRepository,
                                FileManagerRepository $fileManagerRepository)
    {
        $this->postRepository = $postRepository;
        $this->replyPostRepository = $replyPostRepository;
        $this->fileManagerRepository = $fileManagerRepository;
    }

    public function execute(DeletePostRequest $request): DeletePostResponse
    {
        $postId = new PostId($request->postId);

        $deleteRep = $this->replyPostRepository->deleteReplyByPost($postId);
        if ($deleteRep instanceof Failed) {
            return new DeletePostResponse(null, $deleteRep->getMessage(), 500, true);
        }

        $deleteFile = $this->fileManagerRepository->deleteFileByPost($postId);
        if ($deleteFile instanceof Failed)
            return new DeletePostResponse($deleteFile, $deleteFile->getMessage(), 500, true);

        $delete = $this->postRepository->deletePost($postId);
        if ($delete instanceof Failed)
            return new DeletePostResponse($delete, $delete->getMessage(), 500, true);

//        if ($delete === true && $deleteFile === true && $deleteRep === true)
//            return new DeletePostResponse(null, 'Delete Post', 200, false);

        return new DeletePostResponse(null, 'Delete Post', 200, false);
    }

}
