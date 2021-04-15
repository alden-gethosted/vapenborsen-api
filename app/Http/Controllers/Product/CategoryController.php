<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\ProductCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try{

            $table = ProductCategories::select('id', 'name', 'parents_id')->orderBy('id', 'DESC')->get();
            $data = [];
            foreach ($table as $row){
                $rowData['id'] = $row->id;
                $rowData['name'] = $row->name;
                $rowData['parent'] = ProductCategories::find($row->parents_id)->name ?? null;
                $rowData['parents_id'] = $row->parents_id;
                $data[] = $rowData;
            }

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:8|unique:product_categories,name',
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

        return response()->json($table);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try{

            $table = ProductCategories::find($id);

            if($table){
                $data['id'] = $table->id;
                $data['name'] = $table->name;
                $data['parent'] = ProductCategories::find($table->parents_id)->name ?? null;
                $data['parents_id'] = $table->parents_id;
            }else{
                return response()->json(config('naz.n_found'), config('naz.not_found'));
            }


        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:8|unique:product_categories,name,'.$id,
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

        return response()->json($table);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
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
