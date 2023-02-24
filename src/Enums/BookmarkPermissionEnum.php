<?php

namespace EscolaLms\Bookmarks\Enums;

use EscolaLms\Core\Enums\BasicEnum;

class BookmarkPermissionEnum extends BasicEnum
{
    const CREATE_BOOKMARK = 'bookmark_create';
    const UPDATE_BOOKMARK = 'bookmark_update';
    const DELETE_BOOKMARK = 'bookmark_delete';
    const LIST_BOOKMARK = 'bookmark_list';
}
