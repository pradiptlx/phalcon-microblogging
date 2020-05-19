<?php


namespace Dex\Microblog\Presentation\Web\Controller;

use Dex\Microblog\Core\Application\Request\ChangeProfileRequest;
use Dex\Microblog\Core\Application\Request\CreateUserAccountRequest;
use Dex\Microblog\Core\Application\Request\ForgotPasswordLoginRequest;
use Dex\Microblog\Core\Application\Request\ForgotPasswordRequest;
use Dex\Microblog\Core\Application\Request\SearchUserRequest;
use Dex\Microblog\Core\Application\Request\ShowDashboardRequest;
use Dex\Microblog\Core\Application\Request\UserLoginRequest;
use Dex\Microblog\Core\Application\Service\ChangeProfileService;
use Dex\Microblog\Core\Application\Service\CreateUserAccountService;
use Dex\Microblog\Core\Application\Service\ForgotPasswordLoginService;
use Dex\Microblog\Core\Application\Service\ForgotPasswordService;
use Dex\Microblog\Core\Application\Service\SearchUserService;
use Dex\Microblog\Core\Application\Service\ShowDashboardService;
use Dex\Microblog\Core\Application\Service\UserLoginService;
use Dex\Microblog\Core\Domain\Model\UserId;
use Phalcon\Mvc\Controller;
use Swift_Mailer;

class UserController extends Controller
{
    private CreateUserAccountService $createUserAccountService;
    private UserLoginService $userLoginService;
    private ShowDashboardService $showDasboardService;
    private SearchUserService $searchUserService;
    private ChangeProfileService $changeProfileService;
    private ForgotPasswordService $forgotPasswordService;
    private ForgotPasswordLoginService $forgotPasswordLoginService;

    public function initialize()
    {
        $this->createUserAccountService = $this->di->get('createUserAccountService');
        $this->userLoginService = $this->di->get('userLoginService');
        $this->showDasboardService = $this->di->get('showDashboardService');
        $this->searchUserService = $this->di->get('searchUserService');
        $this->changeProfileService = $this->di->get('changeProfileService');
        $this->forgotPasswordService = $this->di->get('forgotPasswordService');
        $this->forgotPasswordLoginService = $this->di->get('forgotPasswordLoginService');

        if (is_null($this->router->getActionName())) {
            $this->response->redirect('user/login');
        }


        if ($this->session->has('user_id') && $this->session->has('username')) {
            $this->view->setVar('username', $this->session->get('username'));
            $this->view->setVar('user_id', $this->session->get('user_id'));
        }
    }

    public function dashboardAction($user_id = '')
    {
        $this->session->set('last_url', $this->router->getControllerName() . '/' . $this->router->getActionName());

        if (!$this->session->has('user_id')) {
            $this->flashSession->error('You must login first.');

            // $check = $this->dispatcher->forward([
            //     'module' => 'microblog',
            //     'controller' => 'user',
            //     'action' => 'login',
            // ]);

            return $this->response->redirect('user/login');
        }

        if ($user_id == '') {
            $user_id = $this->session->get('user_id');
        }

        $dashboardCollection = $this->assets->collection('dashboardCss');
        $dashboardCollection->addCss('/css/profile.css');

        $request = new ShowDashboardRequest(
            new UserId($user_id)
        );

        $response = $this->showDasboardService->execute($request);
        if ($response->getError()) {
            $this->flashSession->error($response->getCode() . ' ' . $response->getMessage());
            return $this->response->redirect('/');
        } else {
            $resData = $response->getData();
            $userPosts = $resData['posts'];
            $user = $resData['user'];
            $this->view->setVar('posts', $userPosts);
            $this->view->setVar('self', true);
            $this->view->setVar('user', $user);
            $this->view->setVar('user_id', $user_id);
            $this->view->setVar('title', 'Dashboard');
            $this->view->pick('user/dashboard');
        }

    }

    public function accountSettingsAction()
    {
        $request = $this->request;

        if ($request->isPost()) {
            $username = $request->getPost('username', 'string');
            $fullname = $request->getPost('fullname', 'string');
            $email = $request->getPost('email', 'email');
            $oldPass = $request->getPost('oldPassword', 'string');
            $newPass = $request->getPost('newPassword', 'string');

            $req = new ChangeProfileRequest(
                $this->session->get('user_id'),
                $username,
                $fullname,
                $email,
                $oldPass,
                $newPass
            );

            $res = $this->changeProfileService->execute($req);

            $res->getError() ? $this->flashSession->error($res->getMessage())
                : $this->flashSession->success($res->getMessage());

        }

        return $this->response->redirect('user/dashboard');
    }

    public function resetPasswordAction()
    {
        $request = $this->request;

        if ($request->isPost()) {
            $oldPass = $request->getPost('oldPassword', 'string');
            $newPass = $request->getPost('newPassword', 'string');

            $req = new ChangeProfileRequest(
                $this->session->get('user_id'),
                null,
                null,
                null,
                $oldPass,
                $newPass
            );

            $res = $this->changeProfileService->execute($req);

            $res->getError() ? $this->flashSession->error($res->getMessage())
                : $this->flashSession->success($res->getMessage());

        }

        return $this->response->redirect('user/dashboard');
    }

    public function registerAction()
    {

        $this->view->setVar('title', 'Register Page');
        if ($this->request->isPost()) {
//            $service = new CreateUserAccountService()
            $request = new CreateUserAccountRequest(
                $this->request->getPost('username', 'string'),
                $this->request->getPost('fullname', 'string'),
                $this->request->getPost('email', 'string'),
                password_hash($this->request->getPost('password', 'string'), PASSWORD_BCRYPT)
            );

            // TODO: Create role registration
            $response = $this->createUserAccountService->execute($request);

            if ($response->getError()) {
                $this->flashSession->error($response->getCode() . ' ' . $response->getMessage());
                return $this->response->redirect('user/register');
            } else {
                $this->flashSession->success($response->getMessage());
            }

            return $this->response->redirect('/');
        }
    }

    public function loginAction()
    {
        $headerCollection = $this->assets->collection('headerCss');
        $headerCollection->addCss('/css/login/main.css');
        $headerCollection->addCss('/css/login/index.css');
        $this->view->setVar('title', 'Login Page');

        $request = $this->request;

        if ($request->isPost()) {
            if (!$this->security->checkToken()) {
                $this->flashSession->error('Session error! refresh halaman');
                return $this->response->redirect('user/login');
            }
            $username = $request->getPost('username', 'string');
            $password = $request->getPost('password', 'string');

            $userLoginRequest = new UserLoginRequest(
                $username,
                $password
            );

            $response = $this->userLoginService->execute($userLoginRequest);

            if ($response->getError()) {
                $this->flashSession->error($response->getCode() . ' ' . $response->getMessage());
                return $this->response->redirect('');
            } else {
                $this->flashSession->success($response->getMessage());
            }
            return $this->response->redirect('/');
        }

        return $this->view->pick("user/login");
    }

    public function logoutAction()
    {
        if ($this->di->has('user')) {
            $this->di->remove('user');
        }
        if ($this->session->has('user_id')) {
            $this->session->remove('user_id');
        }

        if ($this->session->has('username'))
            $this->session->remove('username');

        if ($this->session->has('fullname'))
            $this->session->remove('fullname');

        if ($this->session->has('last_url'))
            $this->session->remove('last_url');

        $this->flashSession->success("Successfully logout");
        return $this->response->redirect('user/login');
    }

    public function findUserAction() 
    {
        $keyword = $this->request->get('q');
        $request = new SearchUserRequest($keyword);
        $response = $this->searchUserService->execute($request);
        $json_response['results'] = [];
        if ($response->getData()) {
            $response_data = $response->getData();
            foreach ($response_data as $items) {
                $tmp_json[] = array(
                    'id' => $items->getId()->getId(),
                    'text' => $items->getUsername()
                );
                $json_response['results'] += $tmp_json;
            }
        }

        return $this->response->setJsonContent($json_response);
    }

    public function forgotPasswordAction($token='') {
        if ($this->request->isGet()) {
            if ($this->session->has('reset_token') && $this->session->has('email')) {
                if ($this->session->get('reset_token') == $token) {
                    $request = new ForgotPasswordLoginRequest($this->session->get('email'));
                    $response = $this->forgotPasswordLoginService->execute($request);
                    $this->session->remove('email');
                    $this->session->remove('reset_token');
                    if ($response->getError()) {
                        $this->flashSession->error($response->getCode() . ' ' . $response->getMessage());
                        return $this->response->redirect('user/login');
                    } else {
                        $this->flashSession->warning($response->getMessage());
                    }
                    return $this->response->redirect('user/dashboard');
                    
                }
            }
            return $this->response->redirect('user/login');
            
        } else if ($this->request->isPost()) {
            if (!$this->security->checkToken()) {
                $this->flashSession->error('Session error! refresh halaman');
                return $this->response->redirect('user/login');
            }
            $email = $this->request->getPost('email','string');
            $request = new ForgotPasswordRequest($email);

            $response = $this->forgotPasswordService->execute($request);
            $this->session->set('reset_token','12345');
            $this->session->set('email',$email);
            $this->flashSession->success("Email sudah dikirim");
            return $this->response->redirect('user/login');
        }
    }

}
