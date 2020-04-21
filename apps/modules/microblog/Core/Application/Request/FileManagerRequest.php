<?php


namespace Dex\microblog\Core\Application\Request;


use Dex\microblog\Core\Domain\Model\FileManagerId;

class FileManagerRequest
{

    public FileManagerId $fileManagerId;
    public string $filename;
    public string $path;

    public function __construct(
        string $filename,
        string $path
    )
    {
        $this->fileManagerId = new FileManagerId();
        $this->filename = $filename;
        $this->path = $path;
    }

}
