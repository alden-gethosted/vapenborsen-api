<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCategoriesResource;
use App\Models\ProductCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    public function index()
    {
        try{

            $table = ProductCategories::orderBy('id', 'DESC')->get();

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return ProductCategoriesResource::collection($table);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191|unique:product_categories,name',
            'parents_id' => 'sometimes|nullable|exists:product_categories,id'
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{

            $table = new ProductCategories();
            $table->name = $request->name;
            if (isset($request->parents_id)) {
                $table->parents_id = $request->parents_id;
            }
            $table->save();

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new ProductCategoriesResource($table);
    }


    public function show($id)
    {
        try{

            $table = ProductCategories::find($id);

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new ProductCategoriesResource($table);
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191|unique:product_categories,name,'.$id,
            'parents_id' => 'sometimes|nullable|exists:product_categories,id'
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{

            $table = ProductCategories::find($id);
            $table->name = $request->name;
            if (isset($request->parents_id) && $request->parents_id != $id) {
                $table->parents_id = $request->parents_id;
            }else{
                $table->parents_id = null;
            }
            $table->save();

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new ProductCategoriesResource($table);
    }


    public function destroy($id)
    {
        try{
            ProductCategories::where('parents_id', $id)->update(['parents_id' => null]);

            ProductCategories::destroy($id);

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json(config('naz.del'));
    }

    public function category_tree(){
        try{
            $table = ProductCategories::with('parent', 'children')->where('id', 2)->get();
        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }
        return response()->json($table);
    }
}
