<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Order extends JsonResource
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
           'item_id' => $this->item_id,
           'item' => $this->item->name,
           'meal_id' => $this->meal_id,
           'responsible_cook_id' => $this->responsible_cook_id,
           'start' => $this->start,
           'end' => $this->end,
           'user' => $this->responsible_cook_id == null ? 'Not assigned' : $this->user->name,
         ];
     }
}
