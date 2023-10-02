<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecordingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string)$this->id,
            'file_name' => $this->file_name,
            'title' => $this->title,
            'file_size' => $this->file_size,
            'file_length' => $this->file_length,
            'url' => $this->url,
            'transcription' => $this->transcription,
            'slug' => $this->slug,
            'uploaded_at' => $this->created_at->format('Y-m-d H:i:s')
        ];
    }
}
