<?php

namespace EscolaLms\Bookmarks\Http\Controllers;

use EscolaLms\Bookmarks\Http\Controllers\Swagger\AdminBookmarkControllerSwagger;
use EscolaLms\Bookmarks\Http\Requests\AdminListBookmarkRequest;
use EscolaLms\Bookmarks\Http\Resources\BookmarkResource;
use EscolaLms\Bookmarks\Services\Contracts\BookmarkServiceContract;
use EscolaLms\Core\Http\Controllers\EscolaLmsBaseController;
use Illuminate\Http\JsonResponse;

class AdminBookmarkController extends EscolaLmsBaseController implements AdminBookmarkControllerSwagger
{
    private BookmarkServiceContract $bookmarkService;

    public function __construct(BookmarkServiceContract $bookmarkService)
    {
        $this->bookmarkService = $bookmarkService;
    }

    public function findAll(AdminListBookmarkRequest $request): JsonResponse
    {
        $results = $this->bookmarkService->findAll($request->getCriteria(), $request->getPage(), $request->getOrder());

        return $this->sendResponseForResource(BookmarkResource::collection($results));
    }
}
