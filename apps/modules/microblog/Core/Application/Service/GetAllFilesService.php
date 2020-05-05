<?php


namespace Dex\Microblog\Core\Application\Service;


use Dex\Microblog\Core\Application\Request\GetAllFilesRequest;
use Dex\Microblog\Core\Application\Response\GetAllFilesResponse;
use Dex\Microblog\Core\Domain\Repository\FileManagerRepository;

class GetAllFilesService
{
    private FileManagerRepository $fileManagerRepository;

    public function __construct(FileManagerRepository $fileManagerRepository)
    {
        $this->fileManagerRepository = $fileManagerRepository;
    }

    public function execute(GetAllFilesRequest $request): GetAllFilesResponse
    {
        $posts = $this->fileManagerRepository->byPostId($request->post_id);

        return new GetAllFilesResponse($posts, 'Files received', 200, false);
    }

}
