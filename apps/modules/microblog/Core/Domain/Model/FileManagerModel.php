<?php


namespace Dex\Microblog\Core\Domain\Model;


class FileManagerModel
{
    protected string $id;

    protected string $fileName;

    protected string $path;

    protected string $postId;

    public function __construct(
        FileManagerId $fileManagerId,
        string $fileName,
        string $path,
        string $postId
    )
    {
        $this->id = $fileManagerId;
        $this->fileName = $fileName;
        $this->path = $path;
        $this->postId = $postId;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getPostId(): string
    {
        return $this->postId;
    }


}
