<?php

namespace EscolaLms\Bookmarks\Dtos;

use EscolaLms\Core\Dtos\Contracts\DtoContract;
use EscolaLms\Core\Dtos\Contracts\InstantiateFromRequest;
use Illuminate\Http\Request;

class CreateBookmarkDto implements DtoContract, InstantiateFromRequest
{
    private string $value;

    private int $userId;

    private string $bookmarkableType;

    private int $bookmarkableId;

    public function __construct(string $value, string $bookmarkableType, int $bookmarkableId)
    {
        $this->value = $value;
        $this->userId = auth()->id();
        $this->bookmarkableType = $bookmarkableType;
        $this->bookmarkableId = $bookmarkableId;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getBookmarkableType(): string
    {
        return $this->bookmarkableType;
    }

    public function getBookmarkableId(): int
    {
        return $this->bookmarkableId;
    }

    public function toArray(): array
    {
        return [
            'value' => $this->getValue(),
            'user_id' => $this->getUserId(),
            'bookmarkable_type' => $this->getBookmarkableType(),
            'bookmarkable_id' => $this->getBookmarkableId(),
        ];
    }

    public static function instantiateFromRequest(Request $request): self
    {
        return new self(
            $request->input('value'),
            $request->input('bookmarkable_type'),
            $request->input('bookmarkable_id'),
        );
    }
}
