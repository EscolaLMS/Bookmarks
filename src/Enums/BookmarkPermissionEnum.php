<?php

namespace EscolaLms\Bookmarks\Enums;

use EscolaLms\Core\Enums\BasicEnum;

class BookmarkPermissionEnum extends BasicEnum
{
    public const CREATE_BOOKMARK = 'bookmark_create';
    public const UPDATE_BOOKMARK = 'bookmark_update';
    public const DELETE_BOOKMARK = 'bookmark_delete';
    public const LIST_BOOKMARK = 'bookmark_list';
    public const LIST_ALL_BOOKMARK = 'bookmark_list-all';

    public static function studentPermissions(): array
    {
        return [
            self::CREATE_BOOKMARK,
            self::UPDATE_BOOKMARK,
            self::DELETE_BOOKMARK,
            self::LIST_BOOKMARK,
        ];
    }

    public static function adminPermissions(): array
    {
        return self::asArray();
    }
}
