<?php


namespace Dex\Microblog\Core\Application\Service;


use Dex\Microblog\Core\Application\Request\GetAllFilesRequest;
use Dex\Microblog\Core\Application\Response\ShowAllPostResponse;
use Dex\Microblog\Core\Domain\Model\PostModel;
use Dex\Microblog\Core\Domain\Repository\PostRepository;

class ShowAllPostService extends \Phalcon\Di\Injectable
{
    private PostRepository $postRepository;
    private GetAllFilesService $getAllFilesService;

    public function __construct(PostRepository $postRepository)
    {
//        $this->getAllFilesService = $this->di->get('getAllFilesService');
        $this->postRepository = $postRepository;
    }

    public function execute(): ShowAllPostResponse
    {
        $posts = $this->postRepository->getAll();

        /**
         * @var PostModel $post
         */
        /*foreach ($posts as $post) {
            $fileRequest = new GetAllFilesRequest($post->getId()->getId());

            $response = $this->getAllFilesService->execute($fileRequest);

            if (!$response->getError()) {
                $files[] = $response->getData();
            }
        }*/

        return new ShowAllPostResponse($posts, 'Show All Post', 200, false);

    }

}
