<?php


namespace Dex\Microblog\Core\Application\Request;


use Dex\Microblog\Core\Domain\Model\FileManagerId;
use Phalcon\Http\Request\File;

class FileManagerRequest
{

    public FileManagerId $fileManagerId;
    public string $filename;
    public File $file;
    public string $path;

    public function __construct(
        string $filename,
        File $file,
        string $path = ""
    )
    {
        $this->fileManagerId = new FileManagerId();
        $this->filename = $filename;
        $this->file = $file;
        $this->path = $path;
    }

}
