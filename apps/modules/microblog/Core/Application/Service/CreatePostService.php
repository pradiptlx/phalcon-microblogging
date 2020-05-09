<?php


namespace Dex\Microblog\Core\Application\Service;

use Dex\Microblog\Core\Application\Request\CreatePostRequest;
use Dex\Microblog\Core\Application\Request\FileManagerRequest;
use Dex\Microblog\Core\Application\Response\CreatePostResponse;
use Dex\Microblog\Core\Domain\Model\FileManagerId;
use Dex\Microblog\Core\Domain\Model\FileManagerModel;
use Dex\Microblog\Core\Domain\Model\PostModel;
use Dex\Microblog\Core\Domain\Model\UserId;
use Dex\Microblog\Core\Domain\Model\UserModel;
use Dex\Microblog\Core\Domain\Repository\PostRepository;
use Dex\Microblog\Core\Domain\Repository\UserRepository;
use Dex\Microblog\Core\Domain\Repository\FileManagerRepository;
use Dex\Microblog\Infrastructure\Persistence\SqlPostRepository;
use Phalcon\Di\Injectable;
use Phalcon\Mvc\Model\Transaction\Failed;

class CreatePostService extends Injectable
{
    private PostRepository $postRepository;
    private UserRepository $userRepository;
    private FileManagerRepository $fileManagerRepository;

    public function __construct(
        $postRepository,
        $fileRepository
    )
    {
        $this->postRepository = $postRepository;
        $this->fileManagerRepository = $fileRepository;
    }

    public function execute(CreatePostRequest $request): CreatePostResponse
    {

        if (!$this->session->has('userModel'))
            return new CreatePostResponse(null, 'You must login', 400, true);

        $postModel = new PostModel(
            $request->id,
            $request->title,
            $request->content,
            $this->session->get('userModel')
        );

        $post = $this->postRepository->savePost($postModel);

        if (!empty($request->files) || isset($request->files)) {
            $fileStatus = false;
            /**
             * @var FileManagerRequest $file
             */
            foreach ($request->files as $file) {
                $path = 'files/' . $postModel->getUser()->getId()->getId() . '/' . $postModel->getId()->getId();
                $filePath = $path . '/' . $file->filename;
                $fileManagerModel = new FileManagerModel(
                    $file->fileManagerId,
                    $file->filename,
                    $path,
                    $postModel
                );
                $fileStatus = $this->fileManagerRepository->saveFile($fileManagerModel);

                if ($fileStatus === true)
                    $file->file->moveTo($filePath);
                elseif ($fileStatus instanceof Failed)
                    return new CreatePostResponse($fileStatus, $fileStatus->getMessage(), 500, true);
            }

        }


        if ($post === true) {
            // Set something like session
            return new CreatePostResponse(null, 'Create Post Success', 200, false);
        } elseif ($post instanceof Failed)
            return new CreatePostResponse($post, $post->getMessage(), 500, true);

        return new CreatePostResponse(null, 'Something was error', 500, true);
    }

}
