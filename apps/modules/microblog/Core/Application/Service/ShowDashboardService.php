<?php


namespace Dex\Microblog\Core\Application\Service;

use Dex\Microblog\Core\Application\Request\ShowDashboardRequest;
use Dex\Microblog\Core\Application\Response\ShowDashboardResponse;
use Dex\Microblog\Core\Domain\Model\PostModel;
use Dex\Microblog\Core\Domain\Model\UserId;
use Dex\Microblog\Core\Domain\Model\UserModel;
use Dex\Microblog\Core\Domain\Repository\PostRepository;
use Dex\Microblog\Core\Domain\Repository\UserRepository;

class ShowDashboardService extends \Phalcon\Di\Injectable
{
    private PostRepository $postRepository;
    private UserRepository $userRepository;

    public function __construct(PostRepository $postRepository, UserRepository $userRepository)
    {
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
    }

    public function execute(ShowDashboardRequest $request): ShowDashboardResponse
    {
        $userId = $request->userId;
        /**
         * @var PostModel $post
         */
        $posts = $this->postRepository->byUserId($userId);

        /**
         * @var UserModel $post
         */
        $user = $this->userRepository->byId($userId);

        return new ShowDashboardResponse($user, $posts, 'Show Post by User', 200, false);

    }

}
