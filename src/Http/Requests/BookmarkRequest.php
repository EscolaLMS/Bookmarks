<?php

namespace EscolaLms\Bookmarks\Http\Requests;

use EscolaLms\Bookmarks\Models\Bookmark;
use Illuminate\Foundation\Http\FormRequest;

abstract class BookmarkRequest extends FormRequest
{
    public function getBookmark(): Bookmark
    {
        return Bookmark::findOrFail($this->route('id'));
    }

    public function isOwner(?Bookmark $bookmark = null): bool
    {
        $bookmark = $bookmark ?? $this->getBookmark();

        return $bookmark->user_id === auth()->id();
    }

    public function rules(): array
    {
        return [
        ];
    }
}
