<?php


namespace Dex\Microblog\Core\Domain\Model;


class FileManagerModel
{
    protected string $id;

    protected string $fileName;

    protected string $path;

    protected string $post;

    public function __construct(
        FileManagerId $fileManagerId,
        string $fileName,
        string $path,
        PostModel $post
    )
    {
        $this->id = $fileManagerId;
        $this->fileName = $fileName;
        $this->path = $path;
        $this->post = $post;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getPost(): PostModel
    {
        return $this->post;
    }

    // TODO: byId File manager
//    public function getPostById(FileManagerId $fileManagerId): ?PostModel
//    {
//
//    }


}
