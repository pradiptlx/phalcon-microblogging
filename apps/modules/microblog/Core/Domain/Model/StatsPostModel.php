<?php


namespace Dex\Microblog\Core\Domain\Model;


class StatsPostModel
{

    protected int $reblogCounter;

    protected int $commentCounter;

    public function __construct()
    {
        $this->reblogCounter = 0;
        $this->commentCounter = 0;
    }

    public function incReblogCounter()
    {
        $this->reblogCounter++;
    }

    public function decReblogCounter()
    {
        $this->reblogCounter--;
    }

    public function incCommentCounter()
    {
        $this->commentCounter++;
    }

    public function decCommentCounter()
    {
        $this->commentCounter--;
    }

    public function getReblogCounter(): int
    {
        return $this->reblogCounter;
    }

    public function getCommentCounter(): int
    {
        return $this->commentCounter;
    }


}
