<?php


namespace Dex\Microblog\Core\Application\Response;


use Dex\Microblog\Core\Domain\Model\PostModel;

class ShowAllPostResponse extends GenericResponse
{
    public function __construct($data, $message, $code = 200, $error = null)
    {
        $posts = $this->parsingModel($data);
        parent::__construct($posts, $message, $code, $error);
    }

    private function parsingModel(array $datas)
    {
        $listPosts = [];
//        $listFiles = [];

        // Not efficient
        /*foreach ($datas[1] as $data) {
            if (!empty($data)) {
                $listFiles[] = (object)[
                    'path' => $data[0]->getPath(),
                    'filename' => $data[0]->getFilename()
                ];
            }
        }*/

        for ($i = 0; $i < sizeof($datas[0]); $i++) {
            if (!empty($datas[1][$i])) {
                $listPosts[] = (object)[
                    'id' => $datas[0][$i]->getId()->getId(),
                    'title' => $datas[0][$i]->getTitle(),
                    'content' => $datas[0][$i]->getContent(),
                    'replyCounter' => $datas[0][$i]->getReplyCounter(),
                    'shareCounter' => $datas[0][$i]->getShareCounter(),
                    'repostCounter' => $datas[0][$i]->getRepostCounter(),
                    'created_at' => $datas[0][$i]->getCreatedDate(),
                    'username' => $datas[0][$i]->getUser()->getUsername(),
                    'fullname' => $datas[0][$i]->getUser()->getFullname(),
                    'file' => (object)[
                        'path' => $datas[1][$i][0]->getPath() . '/' . $datas[1][$i][0]->getFilename(),
                        'filename' => $datas[1][$i][0]->getFilename()
                    ],
                    'user_id' => $datas[0][$i]->getUser()->getId()->getId()
                ];
            } else {
                $listPosts[] = (object)[
                    'id' => $datas[0][$i]->getId()->getId(),
                    'title' => $datas[0][$i]->getTitle(),
                    'content' => $datas[0][$i]->getContent(),
                    'replyCounter' => $datas[0][$i]->getReplyCounter(),
                    'shareCounter' => $datas[0][$i]->getShareCounter(),
                    'repostCounter' => $datas[0][$i]->getRepostCounter(),
                    'created_at' => $datas[0][$i]->getCreatedDate(),
                    'username' => $datas[0][$i]->getUser()->getUsername(),
                    'fullname' => $datas[0][$i]->getUser()->getFullname(),
                    'file' => null,
                    'user_id' => $datas[0][$i]->getUser()->getId()->getId()
                ];
            }
        }

        return $listPosts;
    }

}
