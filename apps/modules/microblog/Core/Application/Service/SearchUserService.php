<?php


namespace Dex\Microblog\Core\Application\Service;

use Dex\Microblog\Core\Application\Request\SearchUserRequest;
use Dex\Microblog\Core\Application\Response\CreateUserAccountResponse;
use Dex\Microblog\Core\Application\Response\SearchUserResponse;
use Dex\Microblog\Core\Domain\Repository\UserRepository;
use Phalcon\Di\Injectable;

class SearchUserService extends Injectable
{

    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(SearchUserRequest $request): SearchUserResponse
    {

        $result = $this->userRepository->searchUsername($request->keyword);

        return new SearchUserResponse($result,'Search use Result',200,false);
    }

}
