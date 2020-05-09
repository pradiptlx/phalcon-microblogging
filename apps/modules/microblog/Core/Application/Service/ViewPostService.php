<?php


namespace Dex\Microblog\Core\Application\Service;


use Dex\Microblog\Core\Application\Request\ViewPostRequest;
use Dex\Microblog\Core\Application\Response\ViewPostResponse;
use Dex\Microblog\Core\Domain\Repository\FileManagerRepository;
use Dex\Microblog\Core\Domain\Repository\PostRepository;

class ViewPostService
{
    private PostRepository $postRepository;
    private FileManagerRepository $fileManagerRepository;

    public function __construct(
        PostRepository $postRepository,
        FileManagerRepository $fileManagerRepository
    )
    {
        $this->postRepository = $postRepository;
        $this->fileManagerRepository = $fileManagerRepository;
    }

    public function execute(ViewPostRequest $request): ViewPostResponse
    {
        $idPostModel = $request->postId;

        $post = $this->postRepository->byId($idPostModel);
        $file = $this->fileManagerRepository->byPostId($idPostModel->getId());

        $data = [
            $post,
            $file
        ];

        if (isset($post))
            return new ViewPostResponse($data, 'View Post ' . $post->getTitle(), 200, false);

        return new ViewPostResponse($post, 'Post Not Found', 200, false);
    }

}
