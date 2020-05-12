<?php


namespace Dex\Microblog\Core\Domain\Model;


class PostModel
{

    /**
     * @var PostId $id
     */
    protected PostId $id;

    /**
     * @var string $title
     */
    protected string $title;

    /**
     * @var string $content ;
     */
    protected string $content;

    protected int $repost_counter;
    protected int $share_counter;
    protected int $reply_counter;

    protected ?UserModel $user;
//    protected string $user_id;

    protected string $created_at;

    public function __construct(
        PostId $id,
        string $title,
        string $content,
        UserModel $user = null,
        int $repost_counter = 0,
        int $share_counter = 0,
        int $reply_counter = 0,
        string $created_at = ""
    )
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->repost_counter = $repost_counter;
        $this->reply_counter = $reply_counter;
        $this->share_counter = $share_counter;
        $this->user = $user;
        $this->created_at = $created_at;
    }

    public function getId(): PostId
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getUser(): UserModel
    {
        return $this->user;
    }

    public function getCreatedDate(): string
    {
        return $this->created_at;
    }

    public function getRepostCounter(): int
    {
        return $this->repost_counter;
    }

    public function getReplyCounter(): int
    {
        return $this->reply_counter;
    }

    public function getShareCounter(): int
    {
        return $this->share_counter;
    }

    public function incRepostCounter()
    {
        //TODO: Event Subscriber

        return $this->repost_counter++;
    }

    public function decRepostCounter()
    {

        return $this->repost_counter--;
    }

    public function incReplyCounter(): int
    {
        $this->reply_counter++;
        return $this->reply_counter;
    }

    public function decReplyCounter()
    {

        return $this->reply_counter--;
    }

    public function incShareCounter()
    {

        return $this->share_counter++;
    }

    public function decShareCounter()
    {
        return $this->share_counter--;
    }

}
