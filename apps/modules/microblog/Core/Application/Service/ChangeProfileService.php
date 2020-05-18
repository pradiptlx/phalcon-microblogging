<?php


namespace Dex\Microblog\Core\Application\Service;


use Dex\Microblog\Core\Application\Request\ChangeProfileRequest;
use Dex\Microblog\Core\Application\Response\ChangeProfileResponse;
use Dex\Microblog\Core\Domain\Model\UserId;
use Dex\Microblog\Core\Domain\Repository\UserRepository;

class ChangeProfileService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(ChangeProfileRequest $request): ChangeProfileResponse{
        $datas = [
            'username' => $request->username,
            'fullname' => $request->fullname,
            'email' => $request->email,
            'oldPassword' => $request->oldPassword,
            'newPassword' => $request->newPassword,
            'updated_at' => (new \DateTime())->format('Y-m-d H:i:s')
        ];

        $res = $this->userRepository->changeProfile($datas, new UserId($request->userId));

        return new ChangeProfileResponse($res, "Change Profile OK", 200, false);
    }

}
