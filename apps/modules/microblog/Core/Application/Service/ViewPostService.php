<?php


namespace Dex\Microblog\Core\Application\Service;


use Dex\Microblog\Core\Application\Request\ViewPostRequest;
use Dex\Microblog\Core\Application\Response\ViewPostResponse;
use Dex\Microblog\Core\Domain\Repository\PostRepository;
use Dex\Microblog\Core\Domain\Repository\UserRepository;

class ViewPostService
{
    private PostRepository $postRepository;

    public function __construct(
        PostRepository $postRepository
    )
    {
        $this->postRepository = $postRepository;
    }

    public function execute(ViewPostRequest $request): ViewPostResponse
    {
        $idPostModel = $request->postId;

        $post = $this->postRepository->byId($idPostModel);

        if (isset($post))
            return new ViewPostResponse($post, 'View Post ' . $post->getTitle(), 200, false);

        return new ViewPostResponse($post, 'Post Not Found', 200, false);
    }

}
