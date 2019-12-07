<?php


namespace UonSoftware\RefreshTokens\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'name'  => $this->resource->name,
            'email' => $this->resource->email,
        ];
    }
}