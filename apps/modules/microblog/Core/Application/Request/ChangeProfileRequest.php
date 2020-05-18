<?php


namespace Dex\Microblog\Core\Application\Request;


class ChangeProfileRequest
{
    public ?string $username;
    public ?string $fullname;
    public ?string $email;
    public ?string $oldPassword;
    public ?string $newPassword;
    public string $userId;

    public function __construct(string $userId,$username = null, $fullname = null, $email = null, $oldPassword = null, $newPassword = null)
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->fullname = $fullname;
        $this->email = $email;
        $this->oldPassword = $oldPassword;
        $this->newPassword = $newPassword;
    }

}
