<?php

namespace App\Http\Controllers;
use App\Http\Resources\Meal as MealResource;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\Order as OrderResource;
use App\Http\Resources\Item as ItemResource;
use \Carbon\Carbon ;
use App\Meal;
use App\Item;
use App\User;
use App\Order;


use Illuminate\Http\Request;

class MealControllerAPI extends Controller
{
  public function index(Request $request){

      return MealResource::collection(Meal::paginate(10));

  }

  public function getAllMeals(Request $request){
    return MealResource::collection(Meal::paginate(10));
  }

  public function getUserMeals($id){
    $user = User::findOrFail($id);
    $meals;
    if($user->type == 'waiter'){
        $meals = MealResource::collection(Meal::where([
          ['state', 'active'],
          ['responsible_waiter_id', $id]
          ])->paginate(10));
          return $meals;
    }elseif($user->type == 'manager'){
      return MealResource::collection(Meal::orderBy('state', 'asc')->paginate(10));
    }

  }

  public function getMeal($id){ //$id da meal
    $meal = Meal::findOrFail($id);
    $orders = OrderResource::collection(Order::where('meal_id', $id)->paginate(10));
    $price = 0;
    foreach ($orders as $order) {
      $item = Item::findOrFail($order->item_id);
      $price += $item->price;
    }
    $price = round($price, 2);
    $meal->total_price_preview = $price;
    $meal->save();
    return $meal;
  }

  public function getMealItems($id){
    $items = [];
    $orders = OrderResource::collection(Order::where('meal_id', $id)->paginate(10));
    foreach ($orders as $order) {
      $items[] = Item::findOrFail($order->item_id);
    }
    return $items;
  }

  public function getMealOrders($id){
    $orders = OrderResource::collection(Order::where('meal_id', $id)->paginate(10));
    return $orders;
  }

  public function terminateMeal($id){ // $id é o id da meal
      $orders = Self::getMealOrders($id);
      $total_price = 0;
      foreach ($orders as $order) {
        if($order->state != 'delivered'){
          $order->state = 'not delivered';
          $order->save();
        }elseif ($order->state == 'delivered') {
          $item = Item::findOrFail($order->item_id);
          $total_price = $total_price + $item->price;
        }
      }
      $meal = Meal::findOrFail($id);
      $meal->state = 'terminated';
      $meal->end = Carbon::now();
      $meal->total_price_preview = $total_price;
      $meal->save();
      return response()->json(new MealResource($meal), 201);
  }

  public function store(Request $request){
      $meals = MealResource::collection(Meal::where('state', 'active')->get());
      foreach ($meals as $meal){
        if($meal->table_number == $request->table_number){
          return 'Table already in use';
        }
      }
      $request->validate([
              'table_number' => 'required|min:1|numeric',
          ]);
      $mytime = Carbon::now();
      $meal = new Meal();
      $meal->table_number = $request->table_number;
      $meal->state = 'active';
      $meal->start = $mytime;
      $meal->responsible_waiter_id = $request->userid;
      $meal->save();
      return response()->json(new MealResource($meal), 201);
  }
}
