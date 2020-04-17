<?php


namespace Dex\Microblog\Core\Domain\Model\Repository;


use Dex\Microblog\Core\Domain\Model\StatsId;
use Dex\Microblog\Core\Domain\Model\StatsPostModel;

interface StatsRepository
{
    public function byId(StatsId $statsId): ?StatsPostModel;

    public function saveStats(StatsPostModel $statsPostModel);
}
