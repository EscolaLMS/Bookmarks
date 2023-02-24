<?php

namespace EscolaLms\Bookmarks\Http\Requests;

use Illuminate\Support\Facades\Gate;

class DeleteBookmarkRequest extends BookmarkRequest
{
    public function authorize(): bool
    {
        $bookmark = $this->getBookmark();

        return Gate::allows('delete', $bookmark) && $this->isOwner($bookmark);
    }

    public function getId(): ?int
    {
        return $this->route('id');
    }
}
