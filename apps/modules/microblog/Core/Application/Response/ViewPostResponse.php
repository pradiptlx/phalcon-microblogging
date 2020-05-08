<?php


namespace Dex\Microblog\Core\Application\Response;


use Dex\Microblog\Core\Domain\Model\PostModel;

class ViewPostResponse extends GenericResponse
{

    public function __construct($data, $message, $code = 200, $error = null)
    {
        parent::__construct($this->parsingModel($data), $message, $code, $error);
    }

    private function parsingModel(PostModel $postModel)
    {
        return (object)[
            'id' => $postModel->getId()->getId(),
            'title' => $postModel->getTitle(),
            'content' => $postModel->getContent(),
            'created_at' => $postModel->getCreatedDate(),
            'username' => $postModel->getUser()->getUsername(),
            'fullname' => $postModel->getUser()->getFullname(),
            'reply_counter' => $postModel->getReplyCounter(),
            'share_counter' => $postModel->getShareCounter(),
            'repost_counter' => $postModel->getRepostCounter()
        ];
    }


}
