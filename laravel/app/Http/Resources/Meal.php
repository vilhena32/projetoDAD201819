<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Meal extends JsonResource
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
           'state' => $this->state,
           'table_number' => $this->table_number,
           'responsible_waiter_id' => $this->responsible_waiter_id,
           'user' => $this->user->name,
           'start' => $this->start,
           'end' => $this->end,
           'total_price'=> $this->total_price_preview
         ];
     }
}
