<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\Invoice as InvoiceResource;
use \Carbon\Carbon ;

use App\Invoice;
use App\Meal;

class InvoiceControllerAPI extends Controller
{
  public function create($id){
      $meal = Meal::findOrFail($id);

      $invoice = new Invoice();
      $date = Carbon::now();
      $invoice->state = 'pending';
      $invoice->meal_id = $id;
      $invoice->date = $date->toDateString();
      $invoice->total_price = $meal->total_price_preview;
      $invoice->save();
      return response()->json(new InvoiceResource($invoice), 201);
  }

  public function update(Request $request, $id)
  {
      $request->validate([
              'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
              'nif' => 'required|regex: /^\d{9}?$/'
          ]);
      $invoice = Invoice::findOrFail($id);
      $invoice->name = $request->name;
      $invoice->nif = $request->nif;
      $invoice->save();
      return response()->json(new InvoiceResource($invoice), 201);
  }

  public function getPendingInvoices(){ //user id
    return InvoiceResource::collection(Invoice::where('state', 'pending')->paginate(10));
  }

  public function closeInvoice($id){
    $invoice = Invoice::findOrFail($id);
    $meal = Meal::findOrFail($invoice->meal_id);
    $invoice->state = 'paid';
    $meal->state = 'paid';
    $meal->save();
    $invoice->save();
    return response()->json(null, 200);
  }
}
