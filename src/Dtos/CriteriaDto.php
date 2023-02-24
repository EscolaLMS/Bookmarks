<?php

namespace EscolaLms\Bookmarks\Dtos;

use EscolaLms\Core\Dtos\Contracts\DtoContract;
use EscolaLms\Core\Dtos\Contracts\InstantiateFromRequest;
use EscolaLms\Core\Dtos\CriteriaDto as BaseCriteriaDto;
use EscolaLms\Core\Repositories\Criteria\Primitives\EqualCriterion;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CriteriaDto  extends BaseCriteriaDto implements DtoContract, InstantiateFromRequest
{
    public static function instantiateFromRequest(Request $request): self
    {
        $criteria = new Collection();

        if ($request->get('bookmarkable_type') && $request->get('bookmarkable_id')) {
            $criteria->push(new EqualCriterion('bookmarkable_type', $request->get('bookmarkable_type')));
            $criteria->push(new EqualCriterion('bookmarkable_id', $request->get('bookmarkable_id')));
        }
        if ($request->get('bookmarkable_type') && !$request->get('bookmarkable_id')) {
            $criteria[] = new EqualCriterion('bookmarkable_type', $request->get('bookmarkable_type'));
        }

        return new static($criteria);
    }
}
