<?php


namespace Dex\Microblog\Core\Application\Request;

class SearchUserRequest
{

    public string $keyword;

    public function __construct(
        string $keyword
    )
    {
        $this->keyword = $keyword;
    }

}
