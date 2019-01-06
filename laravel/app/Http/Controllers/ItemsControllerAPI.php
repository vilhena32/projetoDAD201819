<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;

use App\Http\Resources\Item as ItemResource;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

use App\Item;

class ItemsControllerAPI extends Controller
{
    public function index(Request $request){
        return ItemResource::collection(Item::paginate(10));
    }

    public function getAllItems(Request $request){
        return ItemResource::collection(Item::all());
    }

    public function store(Request $request){
        $request->validate([
                'name' => 'required|min:3', //|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/
                'description' => 'required|string|max:500',
                'type' => 'required|alpha',
                'price' => 'required',
                'photo_url' => 'required'
            ]);
        // return $request;
        $item = new Item();
        $item->name = $request->name;
        $item->description = $request->description;
        $item->type = $request->type;
        $item->price = $request->price;
        $item->photo_url = $request->photo_url;
        //$item->photo_url = Self::saveImg1($item, $requests);
        $item->save();
        return response()->json(new ItemResource($item), 201);
    }

    public function update(Request $request, $id){
        $request->validate([
                'name' => 'required|min:3', //|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/
                'description' => 'required|string|max:500',
                'type' => 'required|alpha',
                'price' => 'required'
            ]);
        $item = Item::findOrFail($id);
        if($item->photo_url){
          Storage::disk('public')->delete($item->photo_url);
        }
        $item->update($request->all());
        return new ItemResource($item);
    }

    public function destroy($id){
        $item = Item::findOrFail($id);
        Storage::disk('public')->delete($item->photo_url);
        $item->delete();
        return response()->json(null, 204);
    }

    public function getFileName(Request $request){
        $path = $request->file('file')->store('items', 'public');
        $path = str_replace("items/","",$path);
        return $path;
    }
}
