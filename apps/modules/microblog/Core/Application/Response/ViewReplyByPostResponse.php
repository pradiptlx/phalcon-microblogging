<?php


namespace Dex\Microblog\Core\Application\Response;


use Dex\Microblog\Core\Domain\Model\ReplyPostModel;

class ViewReplyByPostResponse extends GenericResponse
{
    public function __construct($data, $message, $code = 200, $error = null)
    {
        parent::__construct($this->parsingModel($data), $message, $code, $error);
    }

    private function parsingModel(array $model)
    {
        $replies = [];
        /**
         * @var ReplyPostModel $reply
         */
        foreach ($model as $reply) {
            $replies[] = (object)[
                'repTitle' => $reply->getPost()->getTitle(),
                'repContent' => $reply->getPost()->getContent(),
                'repUsername' => $reply->getUser()->getUsername(),
                'repFullname' => $reply->getUser()->getFullname(),
                'repCreatedDate' => $reply->getPost()->getCreatedDate()
            ];
        }

        return $replies;
    }

}
