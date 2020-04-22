<?php


namespace Dex\microblog\Core\Application\Request;


class UserLoginRequest
{
    public string $username;
    public string $password;

    public function __construct(
        string $username,
        string $password
    )
    {
        $this->username = $username;
        $this->password = $password;
    }

}
