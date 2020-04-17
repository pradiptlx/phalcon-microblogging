<?php


namespace Dex\microblog\Core\Application;


use Dex\Microblog\Core\Domain\Model\UserId;

class CreateUserAccountRequest
{

    public string $userId;
    public string $username;
    public string $fullname;
    public string $email;

    public function __construct(
        UserId $userId,
        string $username,
        string $fullname,
        string $email
    )
    {
        $this->userId = $userId;
        $this->userId = $username;
        $this->fullname = $fullname;
        $this->email = $email;
    }

}
