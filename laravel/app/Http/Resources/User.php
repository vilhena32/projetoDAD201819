<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
          'id' => $this->id,
          'name' => $this->name,
          'username' => $this->username,
          'email' => $this->email,
          'type' => $this->type,
          'photo_url' => $this->photo_url,
          'blocked' => $this->blocked,
          'remember_token' => $this->remember_token,
          'shift_active' =>$this->shift_active,
        ];
    }
}
