<?php


namespace Dex\Microblog\Core\Application\Request;

use Dex\Microblog\Core\Domain\Model\UserId;

class ShowDashboardRequest
{
    public UserId $userId;

    public function __construct(
        UserId $userId
    )
    {
        $this->userId = $userId;
    }

}
