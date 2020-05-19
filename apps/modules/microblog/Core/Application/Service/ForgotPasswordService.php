<?php


namespace Dex\Microblog\Core\Application\Service;

use Dex\Microblog\Core\Application\Request\ForgotPasswordRequest;
use Dex\Microblog\Core\Application\Request\GetAllFilesRequest;
use Dex\Microblog\Core\Application\Response\ForgotPasswordResponse;
use Dex\Microblog\Core\Application\Response\GetAllFilesResponse;
use Dex\Microblog\Core\Domain\Repository\FileManagerRepository;
use Dex\Microblog\Core\Domain\Repository\UserRepository;
use Phalcon\Di\Injectable;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class ForgotPasswordService extends Injectable
{
    private UserRepository $userRepository;
    private Swift_Mailer $mailer;
    public function __construct(UserRepository $userRepository, Swift_Mailer $mailer)
    {
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
    }

    public function execute(ForgotPasswordRequest $request): ForgotPasswordResponse
    {
        $url = $this->di->get('url')->getBaseUri();
        // $user = $this->userRepository->isEmaliExist($request->email);
        $message = (new Swift_Message('Forgot Password'))
        ->setFrom(['noreply@microblog.com' => 'no-reply'])
        ->setTo($request->email)
        ->setBody('Hello! this is your reset token, dont close the browser '.$url.'user/forgotPassword/12345');

        $this->mailer->send($message);

        return new ForgotPasswordResponse(null, 'Email has been sent', 200, false);
    }

}
