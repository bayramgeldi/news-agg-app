<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $api_token = auth()->check() ? '' : $this->createToken($request->token_name);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'token' => $api_token->plainTextToken,
        ];
    }
}
