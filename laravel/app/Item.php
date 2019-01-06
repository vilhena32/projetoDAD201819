<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Order;
use App\InvoiceItem;

class Item extends Model
{
  protected $fillable = [
      'name',
      'price',
      'type',
      'description',
      'photo_url',
  ];

  public function order()
  {
      return $this->hasMany(Order::class);
  }

  public function invoiceitem()
  {
      return $this->hasMany(InvoiceItem::class,'item_id');
  }
}
