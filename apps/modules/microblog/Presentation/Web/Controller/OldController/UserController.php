<?php
declare(strict_types=1);

namespace Dex\Microblog\Controller;

use Dex\Microblog\Models\Post;
use Dex\Microblog\Models\Role;
use Dex\Microblog\Models\User;
use Phalcon\Mvc\Controller;
use Ramsey\Uuid\Uuid;

class UserController extends Controller
{

    public function initialize()
    {
        if (is_null($this->router->getActionName())) {
            $this->response->redirect('/user/login');
        }


        if ($this->session->has('user_id') && $this->session->has('username')) {
            $this->view->setVar('username', $this->session->get('username'));
            $this->view->setVar('user_id', $this->session->get('user_id'));
        }
    }

    public function dashboardAction()
    {
        $this->session->set('last_url', $this->router->getControllerName() . '/' . $this->router->getActionName());

        if (!$this->session->has('user_id')) {
            $this->flashSession->error('You must login first.');

            $this->dispatcher->forward([
                'controller' => 'user',
                'action' => 'login'
            ]);
        }

        $dashboardCollection = $this->assets->collection('dashboardCss');
        $dashboardCollection->addCss('/css/profile.css');

        $user = User::query()
            ->where('id=:id:')
            ->bind([
                'id' => $this->session->get('user_id')
            ])
            ->execute()->getFirst();

        $userPosts = Post::query()
            ->where('user_id=:user_id:')
            ->bind(
                [
                    'user_id' => $this->session->get('user_id')
                ]
            )
            ->execute();

        $this->view->setVar('posts', $userPosts);
        $this->view->setVar('self', true);
        $this->view->setVar('user', $user);
        $this->view->setVar('title', 'Dashboard');
        $this->view->pick('user/dashboard');
    }

    public function registerAction()
    {
        $this->view->title = "Register Account";
        $request = $this->request;

        if ($request->isPost()) {
            $username = $request->getPost('username', 'string');
            $fullname = $request->getPost('fullname', 'string');
            $email = $request->getPost('email', 'email');
            $password = $request->getPost('password', 'string');
            $rolename = $request->getPost('rolename', 'string') ?: 'admin';

            $usernameSearch = User::findFirstByUsername($username);

            if ($usernameSearch->username != null || $usernameSearch->email == $email) {
                $this->flashSession->error('Username has already taken');
                return $this->response->redirect('user/login');
            }

            $user = new User();

            $roleQuery = "SELECT id, role_name, permissions
                            FROM roles
                            WHERE role_name=:rolename";
            $result_role = $this->db->query($roleQuery, [
                'rolename' => $rolename
            ]);

            $role = $result_role->fetch();

            $roleModel = new Role();
            if (!$role) {
                $roleModel->assign([
                    'id' => Role::getRoleId($rolename),
                    'role_name' => $rolename,
                    'permissions' => Role::getPerms()
                ]);
                if (!$roleModel->create())
                    return new \Exception("Can't create new role");
            }

            // TODO: Change default rolename
            $user->assign([
                'id' => Uuid::uuid4()->toString(),
                'username' => $username,
                'fullname' => $fullname,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'role_id' => Role::getRoleId('admin'),
                'created_at' => (new \DateTime())->format('Y-m-d H:i:s'),
                'updated_at' => (new \DateTime())->format('Y-m-d H:i:s')
            ]);

            if ($user->create()) {
                $this->session->set('user_id', $user->id);
                $this->session->set('username', $user->username);
                $this->flashSession->success("Registration success");

                return $this->response->redirect('/home');
            }

        }

        return $this->view->pick('user/register');
    }

    public function loginAction()
    {
        $headerCollection = $this->assets->collection('headerCss');
        $headerCollection->addCss('/css/login/main.css');
        $headerCollection->addCss('/css/login/index.css');
        $this->view->setVar('title', 'Login Page');

        $request = $this->request;

        if ($request->isPost()) {
            $username = $request->getPost('username', 'string');
            $password = $request->getPost('password', 'string');

            $user = User::findByUsername($username)->getFirst();

            if (isset($user) && $this->checkingPassword($password, $user->password)) {
                $this->flashSession->success("Login Success");
                $this->session->set('user_id', $user->id);
                $this->session->set('username', $user->username);

                if ($this->session->has('last_url')) {
                    return $this->response->redirect($this->session->get('last_url'));
                }
                return $this->response->redirect('/home');
            } else {
                $this->flashSession->error("Login Failed");

                return $this->response->redirect('/user/login');
            }
        } else if ($request->isGet()) {

            $this->view->pick("user/login");
//            $this->view->render('user', 'login');
        }

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

        if ($this->session->has('last_url'))
            $this->session->remove('last_url');

        $this->flashSession->success("Successfully logout");
        return $this->response->redirect('/user/login');
    }

    public function resetPasswordAction()
    {
        $request = $this->request;

        if ($request->isPost()) {
            $oldPass = $request->getPost('oldPassword', 'string');
            $newPass = $request->getPost('newPassword', 'string');

            $user = User::findFirstById($this->session->get('user_id'));

            if (isset($user) && $this->checkingPassword($oldPass, $user->password)) {
                $hashed = password_hash($newPass, PASSWORD_BCRYPT);
                $user->password = (string)$hashed;
                $user->updated_at = (new \DateTime())->format('Y-m-d H:i:s');
                $user->update();

                $this->flashSession->success("Success change password");
                return $this->response->redirect('/user/dashboard');
            } else {
                $this->flashSession->error("Can't change password");

                return $this->response->redirect('/home');
            }
        }

        $this->flashSession->error("Doesn't Support GET Method");
        return $this->response->redirect('/user/dashboard');
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

            $this->db->begin();
            $user = User::findFirstById($this->session->get('user_id'));
            if (!empty($username)) {
                $user->username = $username;
            }
            if (!empty($fullname)) {
                $user->fullname = $fullname;
            }
            if (!empty($email)) {
                $user->email = $email;
            }
            if (!empty($newPass) && !empty($oldPass) && $this->checkingPassword($oldPass, $user->password)) {
                $hashed = password_hash($newPass, PASSWORD_BCRYPT);
                $user->password = (string)$hashed;
            }

            $user->updated_at = (new \DateTime())->format('Y-m-d H:i:s');
            if ($user->update()) {
                $this->db->commit();
                $this->flashSession->success("Success change profile");
            } else {
                $this->db->rollback();
                $this->flashSession->error("Can't Change Profile");
            }

            return $this->response->redirect('/user/dashboard');
        }

        $this->flashSession->error("Doesn't Support GET method");
        return $this->response->redirect('/user/dashboard');
    }

    public function findUserAction()
    {
        $usernameParam = $this->router->getParams()[0];

        $dashboardCollection = $this->assets->collection('dashboardCss');
        $dashboardCollection->addCss('/css/profile.css');

        if (isset($usernameParam)) {
            $userModel = User::findFirstByUsername($usernameParam);

            if (isset($usernameParam)) {

                $userPosts = Post::findByUserId($userModel->id);

                $this->view->setVar('posts', $userPosts);
                $this->view->setVar('self', false);
                $this->view->setVar('user', $userModel);
                $this->view->setVar('title', 'Dashboard');

                $this->view->pick('user/dashboard');
            }
        }
    }


    public function forgotPasswordAction()
    {
        $request = $this->request;
    }

    private function checkingPassword(string $password, string $passwordDb): bool
    {
        return password_verify($password, $passwordDb);

    }

}

