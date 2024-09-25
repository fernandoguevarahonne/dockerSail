<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Ramsey\Uuid\Uuid;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'token',
            'id' => Uuid::uuid4()->toString(),
            'attributes' => [
                'message' => 'request successful',
                'user' => $this->when( $request->routeIs('login'), $this->name),
                'token' =>$this->when( $request->routeIs('login'), $this->token),
                'name' =>$this->when( $request->routeIs('register'), $this->name),
                'email' =>$this->when( $request->routeIs('register'), $this->email),

            ],
            'links' => [
                'self' => url()->current(),
            ],
        ];
    }
}
