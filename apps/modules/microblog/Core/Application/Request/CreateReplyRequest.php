<?php


namespace Dex\Microblog\Core\Application\Request;


class CreateReplyRequest
{
    public string $title;
    public string $content;
    public object $postObject;
    public string $postId;
    public string $userId;
    public ?int $reply_counter;

    public function __construct(
        string $title,
        string $content,
        object $post
    )
    {
        $this->title = $title;
        $this->content = $content;
        $this->postObject = $post;
        $this->postId = $post->id;
        $this->userId = $post->user_id;
        $this->reply_counter = $post->reply_counter;
    }

}
