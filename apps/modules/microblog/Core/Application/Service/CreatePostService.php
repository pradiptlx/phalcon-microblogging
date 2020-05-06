<?php


namespace Dex\Microblog\Core\Application\Service;

use Dex\Microblog\Core\Application\Request\CreatePostRequest;
use Dex\Microblog\Core\Domain\Model\FileManagerId;
use Dex\Microblog\Core\Domain\Model\FileManagerModel;
use Dex\Microblog\Core\Domain\Model\PostModel;
use Dex\Microblog\Core\Domain\Repository\PostRepository;
use Dex\Microblog\Core\Domain\Repository\UserRepository;
use Dex\Microblog\Core\Domain\Repository\FileManagerRepository;
use Dex\Microblog\Infrastructure\Persistence\SqlPostRepository;
use Phalcon\Di\Injectable;

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

    public function execute(CreatePostRequest $request)
    {
        $sqlPost = new SqlPostRepository();


        $postModel = new PostModel(
            $request->id,
            $request->title,
            $request->content,
            $request->user_id
        );

        $fileManagerModel = new FileManagerModel(
            $request->files->fileManagerId,
            $request->files->filename,
            $request->files->path,
            $postModel
        );

        $post = $this->postRepository->savePost($postModel);
        $files = $this->fileManagerRepository->saveFile($fileManagerModel);

        if($post && $files) {
            // Set something like session
            return true;
        }

        return false;
    }

}
