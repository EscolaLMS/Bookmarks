<?php

namespace EscolaLms\Bookmarks\Http\Controllers\Swagger;

use EscolaLms\Bookmarks\Http\Requests\AdminListBookmarkRequest;

interface AdminBookmarkControllerSwagger
{
    public function findAll(AdminListBookmarkRequest $request);
}
