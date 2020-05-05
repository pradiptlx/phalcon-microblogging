<?php


namespace Dex\Microblog\Infrastructure\Persistence;


use Dex\Microblog\Core\Domain\Repository\UserRepository;
use Dex\Microblog\Core\Domain\Model\RoleId;
use Dex\Microblog\Core\Domain\Model\RoleModel;
use Dex\Microblog\Core\Domain\Model\UserId;
use Dex\Microblog\Core\Domain\Model\UserModel;
use Dex\Microblog\Infrastructure\Persistence\Record\UserRecord;
use Phalcon\Di\DiInterface;
use Phalcon\Db;
use Phalcon\Mvc\Model\Transaction\Failed;
use Phalcon\Mvc\Model\Transaction\Manager;

class SqlUserRepository implements UserRepository
{
    protected DiInterface $di;

    public function __construct(DiInterface $di)
    {
        $this->di = $di;
    }

    private function parsingRecord(UserRecord $record)
    {
        return new UserModel(
            new UserId($record->id),
            $record->username,
            $record->fullname,
            $record->email,
            $record->password,
        );
    }

    public function byId(UserId $id): ?UserModel
    {
        $userRecord = UserRecord::findFirstById($id->getId());

        return $this->parsingRecord($userRecord);
    }

    public function byUsername(string $username): ?UserModel
    {
        $userRecord = UserRecord::findFirstByUsername($username);

        return $this->parsingRecord($userRecord);
    }

    public function saveUser(UserModel $user)
    {
        $trans = (new Manager())->get();

        try {
            $userRecord = new UserRecord();
            $userRecord->id = $user->getId()->getId();
            $userRecord->username = $user->getUsername();
            $userRecord->fullname = $user->getFullname();
            $userRecord->email = $user->getEmail();
            $userRecord->password = $user->getPassword();
            $userRecord->created_at = (new \DateTime())->format('Y-m-d H:i:s');
            $userRecord->updated_at = (new \DateTime())->format('Y-m-d H:i:s');

            if ($userRecord->save()) {
                $trans->commit();

                return true;
            }

            $trans->rollback();
            throw new Failed((string)$userRecord->getMessages());
        } catch (Failed $exception) {

        }
    }

    public function getPassword(UserId $userId): ?string
    {
        $user = UserRecord::findFirst([
            'conditions' => 'id=:id:',
            'bind' => [
                'id' => $userId->getId()
            ]
        ]);

        if ($user->password)
            return $user->password;

        return null;
    }


}
