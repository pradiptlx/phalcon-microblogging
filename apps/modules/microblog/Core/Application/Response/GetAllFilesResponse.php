<?php


namespace Dex\Microblog\Core\Application\Response;


class GetAllFilesResponse extends GenericResponse
{
    public function __construct($data, $message, $code = 200, $error = null)
    {
        parent::__construct($data, $message, $code, $error);
    }

}
