<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'shopping_list_id' => $this->shopping_list_id,
            'status' => $this->status,
            'default_stock' => $this->default_stock,
            'stock' => $this->stock,
        ];
    }
}
