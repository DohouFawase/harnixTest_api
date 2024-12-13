<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Feedback extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "product_service" =>$this->product_service,
            'rating' => $this->rating,
            "comment" => $this->comment,
            "response"  => $this->response,
            'status'  => $this->status,
        ];
    }
}
