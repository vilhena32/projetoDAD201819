<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Item;

class InvoiceItem extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'quantity',
        'item_id',
        'unit_price',
        'sub_total_price',
    ];

    public function item(){
      return $this->belongsTo(Item::class);
    }
}
