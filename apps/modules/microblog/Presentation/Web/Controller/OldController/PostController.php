<?php
declare(strict_types=1);

namespace Dex\Microblog\Controller;

use Dex\Microblog\Models\FileManager;
use Dex\Microblog\Models\Post;
use Dex\Microblog\Models\ReplyPost;
use Phalcon\Db\Exception;
use Phalcon\Http\Request\File;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Transaction\Failed;
use Phalcon\Mvc\Model\Transaction\Manager;
use Ramsey\Uuid\Uuid;

class PostController extends Controller
{
    private string $getToken;
    private string $getTokenKey;

    public function initialize()
    {
        if (!$this->session->has('user_id')) {
            $this->flashSession->error("You must login.");
            return $this->response->redirect('/user/login');
        }

        if ($this->session->has('user_id') && $this->session->has('username')) {
            $this->view->setVar('username', $this->session->get('username'));
            $this->view->setVar('user_id', $this->session->get('user_id'));
        }

        $postCssCollection = $this->assets->collection('postCss');
        $postCssCollection->addCss('/css/main.css');

        $this->getTokenKey = $this->security->getTokenKey();
        $this->getToken = $this->security->getToken();
        $this->view->setVar('getToken', $this->getToken);
        $this->view->setVar('getTokenKey', $this->getTokenKey);
    }

    public function indexAction()
    {
        $this->view->setVar('title', 'Home');

        $query = "SELECT p.id, p.title, p.content, p.created_at, p.updated_at, p.repost_counter, 
                    p.share_counter, p.reply_counter, u.fullname, u.username
                    FROM Dex\Microblog\Models\Post p
                    JOIN Dex\Microblog\Models\User u on p.user_id = u.id";

        $createQuery = new Query($query, $this->di);

        $posts = $createQuery->execute();

        $files = [];
        $urls = [];
        $repliesCounter = [];

        foreach ($posts as $post) {
            $urls[] = $this->url->get([
                'for' => 'view-post',
                'title' => 'View Post',
                'params' => $post->id
            ]);

            $query = "SELECT f.*
                        FROM Dex\Microblog\Models\FileManager f
                        WHERE f.post_id=:post_id:";
            $createQuery = new Query($query, $this->di);

            $files[] = $createQuery->execute([
                'post_id' => $post->id
            ])->getFirst();

            $repliesCounter[] = ReplyPost::findByPostId($post->id)->count();
        }

        $this->view->setVar('totalPost', $posts->count());
        $this->view->setVar('repliesCounter', $repliesCounter);
        $this->view->setVar('files', $files);
        $this->view->setVar('posts', $posts);
        $this->view->setVar('links', $urls);

        return $this->view->pick('post/home');
    }

    public function createPostAction()
    {
        $this->view->setVar('title', 'Create Post');
        $request = $this->request;

        if ($request->isPost()) {
            $title = $request->getPost('title', 'string');
            $content = $request->getPost('content', 'string');

            $user_id = $this->session->get('user_id');

            try {
                $manager = new Manager();
                $transaction = $manager->get();

                $postModel = new Post();
                $postModel->setTransaction($transaction);
                $postModel->title = $title;
                $postModel->content = $content;

                if ($this->request->hasFiles()) {
                    $files = $request->getUploadedFiles() ?: [];
                    foreach ($files as $file) {
                        $this->initializeFileManager($file, $postModel->id, $user_id);
                    }
                }

                $postModel->user_id = $user_id;
                $postModel->repost_counter = 0;
                $postModel->share_counter = 0;
                $postModel->reply_counter = 0;
                $postModel->created_at = (new \DateTime('now'))->format('Y-m-d H:i:s');
                $postModel->updated_at = (new \DateTime('now'))->format('Y-m-d H:i:s');

                if (!$postModel->save()) {
                    $transaction->rollback('Can not save post');
                    $this->flashSession->error('Can not save post');
                    throw new Failed("Error create post");
                } else
                    $transaction->commit();

                $this->flashSession->success('Create post success');
                return $this->response->redirect('/home');

            } catch (Failed $exception) {
                $this->flashSession->error($exception->getMessage());
                return $this->response->redirect('/home');
            }

        }

    }

    public function viewPostAction()
    {
        $this->session->set('last_url', $this->router->getControllerName() . '/' . $this->router->getActionName() . '/' . $this->router->getParams()[0]);
        $request = $this->request;

        $idPost = $this->router->getParams()[0];

        if (isset($idPost)) {
            if ($request->isGet()) {
                $query = "SELECT p.id, p.title, p.content, p.created_at, p.updated_at, 
                p.repost_counter, p.share_counter, p.reply_counter, u.fullname, p.user_id, u.username
                FROM Dex\Microblog\Models\Post p
                JOIN Dex\Microblog\Models\User u on p.user_id = u.id
                WHERE p.id = :id:";
                //TODO: Exception UUID not found -> some solution: hardcode parameter to use 16bytes GUID
                $modelManager = $this->modelsManager->createQuery($query);
                $post = $modelManager->execute(
                    [
                        'id' => $idPost
                    ]
                )->getFirst();

                $replyQuery = "SELECT r.id as RepId, r.content as RepContent,
                 r.user_id as RepUser, r.created_at as RepCreatedAt, u.fullname as RepFullname
                FROM Dex\Microblog\Models\ReplyPost r
                JOIN Dex\Microblog\Models\User u on r.user_id = u.id
                WHERE r.post_id = :id: ORDER BY r.created_at";
                $modelManager = $this->modelsManager->createQuery($replyQuery);
                $replies = $modelManager->execute([
                    'id' => $idPost
                ]);

                $filesQuery = "SELECT f.*
                        FROM Dex\Microblog\Models\FileManager f
                        WHERE f.post_id=:post_id:";
                $createQuery = new Query($filesQuery, $this->di);

                $files[] = $createQuery->execute([
                    'post_id' => $idPost
                ])->getFirst();

                $this->view->setVar('user_id', $this->session->get('user_id'));
                $this->view->setVar('files', $files);
                $this->view->setVar('replies', $replies);
                $this->view->setVar('title', $post->title);
                $this->view->setVar('post', $post);

                return $this->view->pick('post/viewPost');

            } elseif ($request->isPost()) {
                $content = $request->getPost('content', 'string');
                $userId = $this->session->get('user_id');

                $postModel = Post::findFirstById($idPost);
                $postModel->replyCounter++;
                $postModel->update();

                // Reply
                $this->db->begin();

                $replyModel = new ReplyPost();
                $replyModel->id = Uuid::uuid4()->toString();
                $replyModel->content = $content;
                $replyModel->user_id = $userId;
                $replyModel->post_id = $idPost;

                if (!$replyModel->save()) {
                    $this->db->rollback();
                    $this->flashSession->error("Failed to reply");
                    return $this->response->redirect('/post/viewPost/' . $idPost);
                }

                $this->db->commit();
                return $this->response->redirect('/post/viewPost/' . $idPost);
            }
        }

        $this->dispatcher->forward([
            'controller' => 'post',
            'action' => 'index'
        ]);
    }

    public function replyPostAction()
    {
        $request = $this->request;

        if ($request->isPost()) {
            $content = $request->getPost('content', 'string');
            $postId = $this->router->getParams()[0];
            $userId = $this->session->get('user_id');

            if (isset($postId) && isset($userId)) {
                $postModel = Post::findFirstById($postId);
                $postModel->reply_counter++;
                if (!$postModel->update()) {
                    var_dump('Cant update post model');
                    die();
                }

                $this->db->begin();
                $replyModel = new ReplyPost();
                $replyModel->id = Uuid::uuid4()->toString();
                $replyModel->content = $content;
                $replyModel->post_id = $postId;
                $replyModel->user_id = $userId;
                $replyModel->created_at = (new \DateTime())->format('Y-m-d H:i:s');
                $replyModel->updated_at = (new \DateTime())->format('Y-m-d H:i:s');

                if (!$replyModel->save()) {
                    $this->db->rollback();
                    $this->flashSession->error("Error Reply");
                    if ($this->session->has('last_url'))
                        return $this->response->redirect($this->session->get('last_url'));
                    return $this->response->redirect('/home');
                }
                $this->db->commit();
            }

        } else {
            $this->flashSession->error("Doesn't Support GET Method");
        }

        return $this->response->redirect('/home');
    }

    public function replyOfReplyAction()
    {
        $request = $this->request;

        $postId = $this->dispatcher->getParam('postId');
        $replyId = $this->dispatcher->getParam('replyId');

        if ($request->isPost() && isset($postId) && isset($replyId)) {

            $content = $request->getPost('content', 'string');
            $userId = $this->session->get('user_id');

            $this->db->begin();
            try {
                $replyModel = new ReplyPost();
                $replyModel->id = Uuid::uuid4()->toString();
                $replyModel->content = $content;
                $replyModel->user_id = $userId;
                $replyModel->post_id = $replyId;
                $replyModel->created_at = (new \DateTime())->format('Y-m-d H:i:s');

                if (!$replyModel->save()) {
                    $this->db->rollback();
                    throw new Failed("Failed to store reply of reply");
                }

                $this->db->commit();
                $this->flashSession->success("Reply Success");

            } catch (Failed $exception) {
                $this->flashSession->error($exception->getMessage());
                return $this->response->redirect('/post/viewPost/' . $postId);
            }

        }

        return $this->response->redirect('/post/viewPost/' . $postId);
    }

    public function deletePostAction()
    {
        $request = $this->request;

        if ($request->isPost()) {
            $postId = $request->getPost('postId', 'string');
            $this->db->begin();

            $postModel = Post::findFirstById($postId);
            $fileModel = FileManager::findByPostId($postId);
            $replyModel = ReplyPost::findByPostId($postId);

            $paths = [];
            foreach ($fileModel as $file) {
                $paths[] = $file->path;
            }

            if ($postModel->delete() && $fileModel->delete() && $replyModel->delete()) {
                foreach ($paths as $path) {
                    if (strpos($path, "/") === 0) {
                        $path = substr_replace($path, '', 0, 1);
                    }
                    if (!unlink($path)) {
                        $this->db->rollback();
                        $this->flashSession->error("Can't delete post " . $postModel->title);
                        return $this->response->redirect('/home');
                    }
                }

                $this->db->commit();
                $this->flashSession->success("Delete post " . $postModel->title . " Success");
            } else {
                $this->db->rollback();
                $this->flashSession->error("Can't delete post " . $postModel->title);
            }

        }

        if ($this->session->has('last_url')) {
            $lastUrl = $this->session->get('last_url');
            if ($lastUrl == 'user/dashboard')
                return $this->response->redirect($lastUrl);
        }

        return $this->response->redirect('/home');
    }

    private function initializeFileManager(File $file, string $post_id, string $user_id)
    {
        $fileModel = new FileManager();
        try {
            $manager = new Manager();
            $trx = $manager->get();

            $this->url->setBasePath('/files/');
            $path = $user_id . "/" . $post_id . "/" . $file->getName();
            try {
                if (!mkdir('files/' . $user_id . "/" . $post_id, 0755, true)) {
                    throw new \Phalcon\Url\Exception("Failed to mkdir");
                }
                $file->moveTo('files/' . $path);
            } catch (\Phalcon\Url\Exception $exception) {
                var_dump($exception->getMessage());
                throw new Failed('Failed save file. Missing Folder');
            }

            $fileModel->file_name = $file->getName();
            $fileModel->path = "/files/" . $path;
            $fileModel->post_id = $post_id;
            if (!$fileModel->save()) {
                $trx->rollback('Can not save file on file manager');
                throw new Failed("Failed Store Files");
            } else {
                $trx->commit();

            }
        } catch (Failed $exception) {
            $this->flashSession->error($exception->getMessage());

        }
    }

}

