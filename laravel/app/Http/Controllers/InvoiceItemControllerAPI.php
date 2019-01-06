<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\InvoiceItem as InvoiceItemResource;

use App\InvoiceItem;
use App\Item;
use App\Order;

class InvoiceItemControllerAPI extends Controller
{
    public function store(Request $request, $id){
        $meal = $request;
        $items = [];
        $contadores = [];
        $orders = Order::where([
                          ['meal_id', $meal->id],
                          ['state', 'delivered']
                        ])->get();

        foreach ($orders as $order) {
          $item = Item::findOrFail($order->item_id);
          if(!in_array($item, $items)){
            $items[] = $item;
            $contadores[$item->id] = 1;
          }else {
            $contadores[$item->id] = $contadores[$item->id] + 1;
          }
        }
        foreach ($items as $item) {
          $invoiceItem = new InvoiceItem();
          $invoiceItem->invoice_id = $id;
          $invoiceItem->item_id = $item->id;
          $invoiceItem->quantity = $contadores[$item->id];
          $invoiceItem->unit_price = $item->price;
          $invoiceItem->sub_total_price = $invoiceItem->quantity * $invoiceItem->unit_price;
          $invoiceItem->save();
        }
        return response()->json(null, 201);
    }

    public function getInvoiceItems($id){
      return InvoiceItemResource::collection(InvoiceItem::where('invoice_id',$id)->get());
    }
}
