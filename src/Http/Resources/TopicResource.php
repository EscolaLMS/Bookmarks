<?php

namespace EscolaLms\Bookmarks\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TopicResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->topicable_type,
            'lesson_id' => $this->lesson?->getKey(),
            'course_id' => $this->lesson?->course?->getKey()
        ];
    }
}
