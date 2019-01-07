<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Order as OrderResource;
use App\Http\Resources\Meal as MealResource;
use App\Http\Resources\User as UserResource;
use \Carbon\Carbon ;

use App\Meal;
use App\User;
use App\Order;

class OrderControllerAPI extends Controller
{
  public function index(Request $request){

      return OrderResource::collection(Order::paginate(10));

  }

  public function getUserOrders($id){
    $user = User::findOrFail($id);
    $newOrders = [];
    if($user->type == 'waiter'){
      // return OrderResource::collection(Order::orderBy('state', 'desc')
      //                                         ->whereIn('meal_id',function($query)  use($id){
      //                                                 $query->select('id')
      //                                                 ->from('meals')
      //                                                 ->where('responsible_waiter_id', $id);
      //                                                 })
      //                                         ->where('state','pending')
      //                                         ->orWhere('state','confirmed')
      //                                         ->get());

      // return OrderResource::collection(Order::orderBy('state', 'desc')
      //                                         ->whereIn('meal_id', function ($query) use ($id) {
      //                                             $query->select('id')
      //                                                 ->from('meals')
      //                                                 ->where('responsible_waiter_id', $id);
      //                                             })
      //                                         ->where(function ($q) {
      //                                               $q->where('state', 'pending')
      //                                                   ->orWhere('state', 'confirmed');
      //                                           ​})
      //                                         ->get());
    return OrderResource::collection(Order::orderBy('state','desc')
                                            ->whereIn('meal_id',function ($query) use ($id){
                                                $query->select('id')
                                                    ->from('meals')
                                                    ->where('responsible_waiter_id', $id);
                                            })
                                            ->where(function ($q) {
                                              $q->where('state','pending')
                                                ->orWhere('state','confirmed');
                                            })
                                            ->get());
    } elseif ($user->type == 'cook') {
      return OrderResource::collection(Order::orderBy('created_at', 'desc')->where([
                                                    ['responsible_cook_id', $id],
                                                    ['state','in preparation']
                                                  ])
                                                ->orWhere(function ($query) {
                                                          $query->where('state', 'confirmed')
                                                          ->whereNull('responsible_cook_id');
                                                         })
                                                ->get());
    } elseif($user->type == 'manager'){
      return OrderResource::collection(Order::orderBy('created_at', 'desc')->get());
    }
  }

  public function getPreparedOrders($id){
    $user = User::findOrFail($id);
    //$orders = OrderResource::collection(Order::all());
    $newOrders = [];
    if($user->type == 'waiter'){
      return OrderResource::collection(Order::whereIn('meal_id',function($query)  use($id){
                                                                    $query->select('id')
                                                                    ->from('meals')
                                                                    ->where('responsible_waiter_id', $id);
                                                                    })
                                                            ->where('state','prepared')
                                                            //->orWhere('state','prepared')
                                                            ->paginate(10));
    }
    if ($user->type == 'cook') {
      return OrderResource::collection(Order::orderBy('created_at', 'desc')->where([
                                                    ['responsible_cook_id', $id],
                                                    ['state','prepared']
                                                  ])
                                                ->orWhere(function ($query) {
                                                          $query->where('state', 'confirmed')
                                                          ->whereNull('responsible_cook_id');
                                                         })
                                                ->get());
    }
    if($user->type == 'manager'){
      return OrderResource::collection(Order::whereIn('meal_id',function($query)  use($id){
                                                                    $query->select('id')
                                                                    ->from('meals');

                                                                    })
                                                            ->where('state','pending')
                                                            ->orWhere('state','prepared')
                                                            ->paginate(10));
    }



  }

  public function takeOrder(Request $request, $id){
    $order = Order::findOrFail($request->id);
    $order->responsible_cook_id = $id;
    $order->state = 'in preparation';
    $order->save();
    return new OrderResource($order);
  }

  public function changeState($id){
    $order = Order::findOrFail($id);
    if($order->state == 'pending'){
      $order->state = 'confirmed';
    }elseif($order->state == 'in preparation'){
      $order->state = 'prepared';
    } elseif ($order->state == 'prepared') {
      $order->state = 'delivered';
    }
    $order->save();
    return new OrderResource($order);
  }

  public function store(Request $request){
      $request->validate([
              'meal_id' => 'required|min:1|numeric',
              'item_id' => 'required|min:1|numeric'
          ]);
      $mytime = Carbon::now();
      $order = new Order();
      $order->item_id = $request->item_id;
      $order->meal_id = $request->meal_id;
      $order->state = 'pending';
      $order->start = $mytime;;
      $order->save();
      return response()->json(new OrderResource($order), 201);
  }

  public function destroy($id)
  {
      $order = Order::findOrFail($id);
      $order->delete();
      return response()->json(null, 204);
  }
}
