<?php


namespace Dex\Microblog\Core\Domain\Model;

use Phalcon\Mvc\Model;

class UserModel extends Model {

    protected string $username;

    protected string $email;

    protected string $numberOfPost;

    

}