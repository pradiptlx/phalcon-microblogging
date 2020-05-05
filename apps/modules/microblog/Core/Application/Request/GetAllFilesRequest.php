<?php


namespace Dex\Microblog\Core\Application\Request;


class GetAllFilesRequest
{
    public string $post_id;
    public ?string $path;
    public ?string $filename;

    public function __construct(
        string $post_id,
        string $path = null,
        string $filename = null
    )
    {
        $this->post_id = $post_id;
        $this->path = $path;
        $this->filename = $filename;
    }

}
