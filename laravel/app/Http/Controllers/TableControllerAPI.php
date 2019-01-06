<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Table as TableResource;

use App\Table;

class TableControllerAPI extends Controller
{
  public function index(Request $request){
      if ($request->has('page')) {
          return TableResource::collection(Table::paginate(10));
      }else{
          return TableResource::collection(Table::all());
      }
  }

  public function store(Request $request){
    $request->validate([
            'table_number' => 'required|numeric|min:1'
        ]);
    $table = new Table();
    $table->table_number = $request->table_number;
    $table->save();
    return response()->json(new TableResource($table), 201);
  }

  public function destroy($id)
  {
    $table = Table::where('table_number', $id)->delete();
    return response()->json(null, 204);
  }
}
