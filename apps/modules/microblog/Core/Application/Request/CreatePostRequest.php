<?php


namespace Dex\Microblog\Core\Application\Request;


use Dex\Microblog\Core\Domain\Model\PostId;

class CreatePostRequest
{
    public PostId $id;
    public string $title;
    public string $content;
    public array $files;
    public string $user_id;

    public function __construct(
        string $title,
        string $content,
        array $files,
        string $user_id
    )
    {
        $this->id = new PostId();
        $this->title = $title;
        $this->content = $content;
        // TODO: fix file manager model
        $this->files = $files;
        $this->user_id = $user_id;
    }

}
