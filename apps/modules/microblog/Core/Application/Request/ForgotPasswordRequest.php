<?php


namespace Dex\Microblog\Core\Application\Request;

class ForgotPasswordRequest
{

    public string $email;

    public function __construct(
        string $email
    )
    {
        $this->email = $email;
    }

}
