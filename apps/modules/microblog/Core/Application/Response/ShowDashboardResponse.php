<?php


namespace Dex\Microblog\Core\Application\Response;


use Dex\Microblog\Core\Domain\Model\PostModel;

class ShowDashboardResponse extends GenericResponse
{
    public function __construct($user, $posts,$message, $code = 200, $error = null)
    {
        $posts = $this->parsingModel($posts);
        $data = [
            'user' => $user,
            'posts' => $posts
        ];
        parent::__construct($data, $message, $code, $error);
    }

    private function parsingModel(array $postModels)
    {
        $listPosts = [];

        /**
         * @var PostModel $postModel
         */
        foreach ($postModels as $postModel) {
            $listPosts[] = (object)[
                'id' => $postModel->getId()->getId(),
                'title' => $postModel->getTitle(),
                'content' => $postModel->getContent(),
                'replyCounter' => $postModel->getReplyCounter(),
                'shareCounter' => $postModel->getShareCounter(),
                'repostCounter' => $postModel->decRepostCounter(),
                'created_at' => $postModel->getCreatedDate(),
                'username' => $postModel->getUser()->getUsername(),
                'fullname' => $postModel->getUser()->getFullname(),

            ];
        }

        return $listPosts;
    }

}
