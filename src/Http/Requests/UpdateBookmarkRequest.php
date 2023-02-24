<?php

namespace EscolaLms\Bookmarks\Http\Requests;

use EscolaLms\Bookmarks\Dtos\UpdateBookmarkDto;
use Illuminate\Support\Facades\Gate;

class UpdateBookmarkRequest extends BookmarkRequest
{
    public function authorize(): bool
    {
        $bookmark = $this->getBookmark();

        return Gate::allows('update', $bookmark) && $this->isOwner($bookmark);
    }

    public function rules(): array
    {
        return [
            'value' => ['required', 'string'],
            'bookmarkable_id' => ['required', 'integer'],
            'bookmarkable_type' => ['required', 'string'],
        ];
    }

    public function toDto(): UpdateBookmarkDto
    {
        return UpdateBookmarkDto::instantiateFromRequest($this);
    }
}
