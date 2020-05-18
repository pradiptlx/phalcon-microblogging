<?php


namespace Dex\Microblog\Core\Application\Response;


use Dex\Microblog\Core\Domain\Model\ReplyPostModel;

class ViewReplyByPostResponse extends GenericResponse
{
    public function __construct($data, $message, $code = 200, $error = null)
    {
        parent::__construct($this->parsingModel($data), $message, $code, $error);
    }

    private function parsingModel(array $model = [])
    {
        if(!isset($model))
            return [];

        $replies = [];
        /**
         * @var ReplyPostModel $reply
         */
        foreach ($model as $reply) {
            $replies[] = (object)[
                'repId' => $reply->getId(),
                'postId' => $reply->getReply()->getId()->getId(),
                'repTitle' => $reply->getReply()->getTitle(),
                'repContent' => $reply->getReply()->getContent(),
                'repUsername' => $reply->getReply()->getUser()->getUsername(),
                'repFullname' => $reply->getReply()->getUser()->getFullname(),
                'repCreatedDate' => $reply->getReply()->getCreatedDate()
            ];
        }

        return $replies;
    }

}
