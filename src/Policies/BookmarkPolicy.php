<?php

namespace EscolaLms\Bookmarks\Policies;

use EscolaLms\Auth\Models\User;
use EscolaLms\Bookmarks\Enums\BookmarkPermissionEnum;
use EscolaLms\Bookmarks\Models\Bookmark;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookmarkPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->can(BookmarkPermissionEnum::CREATE_BOOKMARK);
    }

    public function update(User $user, Bookmark $bookmark): bool
    {
        return $user->can(BookmarkPermissionEnum::UPDATE_BOOKMARK);
    }

    public function delete(User $user, Bookmark $bookmark): bool
    {
        return $user->can(BookmarkPermissionEnum::DELETE_BOOKMARK);
    }

    public function list(User $user): bool
    {
        return $user->can(BookmarkPermissionEnum::LIST_BOOKMARK);
    }
}
