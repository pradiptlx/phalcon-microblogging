<?php


namespace Dex\microblog\Core\Application\Service;

use Dex\microblog\Core\Application\Request\UserLoginRequest;
use Dex\Microblog\Core\Domain\Model\Repository\UserRepository;
use Phalcon\Di\Injectable;

class UserLoginService extends Injectable
{

    public function __construct()
    {

    }

    public function execute(UserLoginRequest $request){
        $userRepository = $this->di->get('sqlUserRepository');

        /**
         * @var UserRepository $userRepository
         */
        $userModel = $userRepository->loginUser($request->username, $request->password);

        if($userModel != null){
            $this->di->setShared('user', $userModel);

            return true;
        }

        return false;
    }


}
