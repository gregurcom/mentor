<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    /** @return array<string, string|array<string, string>> */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'information' => $this->information,
            'course' => $this->course->title,
            'files' => $this->files,
            'author' => $this->course->author,
            'created_at' => $this->created_at,
        ];
    }
}
