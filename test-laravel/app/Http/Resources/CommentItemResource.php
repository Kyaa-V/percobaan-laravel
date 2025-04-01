<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'authors_id' => $this->authors_id,
            'content' => $this->content,
            'users_id' => $this->users_id,
            'parent_id' => $this->parent_id,
            'created_at' => $this->created_at,
            'authors_name' => $this->author->name,
            'authors_role' => $this->users->role->role_name,
            'replies' => CommentItemResource::collection($this->whenLoaded('replies')), // Rekursif
        ];
    }
}

