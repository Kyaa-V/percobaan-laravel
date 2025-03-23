<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    protected $message;
    protected $status;
    protected $token;

    public function __construct($resource, $message = "", $status = 200, $token = null)
    {
        parent::__construct($resource);
        $this->message = $message;
        $this->status = $status;
        $this->token = $token;
    }

    public function toArray(Request $request): array
    {
        return [
            "message" => $this->message,
            "status_code" => $this->status,
            "token" => $this->when(!empty($this->token), $this->token),
            "data" => [
                "id" => $this->id,
                "name" => $this->name,
                "role" => $this->whenLoaded('role', fn() => $this->role->role_name, "USER"),
                "created_at" => $this->whenNotNull($this->created_at, fn() => $this->created_at->format('Y-m-d H:i:s')),
                "updated_at" => $this->whenNotNull($this->updated_at, fn() => $this->updated_at->format('Y-m-d H:i:s')),
            ]
        ];
    }
}
