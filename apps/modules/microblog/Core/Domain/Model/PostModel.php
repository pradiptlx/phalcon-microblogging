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

    /**
     * @var StatsPostModel $statsPost
     */
    protected StatsPostModel $statsPost;

    public function __construct(
        PostId $id,
        string $title,
        string $content
    )
    {

        $this->id = $id;
        $this->title = $title;
        $this->content = $content;

        $this->statsPost = new StatsPostModel();

    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return StatsPostModel
     */
    public function getStatsPost(): StatsPostModel
    {
        return $this->statsPost;
    }

}
