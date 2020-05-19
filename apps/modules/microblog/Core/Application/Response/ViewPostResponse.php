<?php


namespace Dex\Microblog\Core\Application\Response;



class ViewPostResponse extends GenericResponse
{

    public function __construct($data, $message, $code = 200, $error = null)
    {
        parent::__construct($this->parsingModel($data), $message, $code, $error);
    }

    private function parsingModel(array $datas)
    {
        if (!empty($datas[1])) {
            return (object)[
                'id' => $datas[0]->getId()->getId(),
                'title' => $datas[0]->getTitle(),
                'content' => $datas[0]->getContent(),
                'created_at' => $datas[0]->getCreatedDate(),
                'username' => $datas[0]->getUser()->getUsername(),
                'user_id' => $datas[0]->getUser()->getId()->getId(),
                'fullname' => $datas[0]->getUser()->getFullname(),
                'reply_counter' => $datas[0]->getReplyCounter(),
                'share_counter' => $datas[0]->getShareCounter(),
                'repost_counter' => $datas[0]->getRepostCounter(),
                'file' => (object)[
                    'path' => $datas[1][0]->getPath() . '/' . $datas[1][0]->getFilename(),
                    'filename' => $datas[1][0]->getFilename()
                ]
            ];
        } else {
            return (object)[
                'id' => $datas[0]->getId()->getId(),
                'title' => $datas[0]->getTitle(),
                'content' => $datas[0]->getContent(),
                'created_at' => $datas[0]->getCreatedDate(),
                'username' => $datas[0]->getUser()->getUsername(),
                'user_id' => $datas[0]->getUser()->getId()->getId(),
                'fullname' => $datas[0]->getUser()->getFullname(),
                'reply_counter' => $datas[0]->getReplyCounter(),
                'share_counter' => $datas[0]->getShareCounter(),
                'repost_counter' => $datas[0]->getRepostCounter(),
                'file' => null
            ];
        }

    }


}
