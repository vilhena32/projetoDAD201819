<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Invoice extends JsonResource
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
        'meal_id' => $this->meal_id,
        'date' => $this->date,
        'state' => $this->state,
        'total_price' => $this->total_price,
        'nif' => $this->nif == null ? 'Not assigned' : $this->nif,
        'name' => $this->name == null ? 'Not assigned' : $this->name,
        'table_number' => $this->meal->table_number,
        'user' => $this->meal->user->name
      ];
    }
}
