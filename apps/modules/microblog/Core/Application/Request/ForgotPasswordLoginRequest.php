<?php


namespace Dex\Microblog\Core\Application\Request;

class ForgotPasswordLoginRequest
{

    public string $email;

    public function __construct(
        string $email
    )
    {
        $this->email = $email;
    }

}
