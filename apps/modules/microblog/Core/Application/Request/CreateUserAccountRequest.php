<?php


namespace Dex\microblog\Core\Application\Request;


use Dex\Microblog\Core\Domain\Model\UserId;

class CreateUserAccountRequest
{

    public UserId $userId;
    public string $username;
    public string $fullname;
    public string $email;
    public string $password;

    public function __construct(
        string $username,
        string $fullname,
        string $email,
        string $password
    )
    {
        $this->userId = new UserId();
        $this->username = $username;
        $this->fullname = $fullname;
        $this->email = $email;
        $this->password = $password;
    }

}
