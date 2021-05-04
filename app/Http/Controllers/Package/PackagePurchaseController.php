<?php

namespace App\Http\Controllers\Package;

use App\Http\Controllers\Controller;
use App\Http\Resources\PackagePurchaseResource;
use App\Models\AdsPackage;
use App\Models\PurchasePackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PackagePurchaseController extends Controller
{

    public function index()
    {
        try{
            $table = PurchasePackage::orderBy('id', 'DESC')->get();
        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return PackagePurchaseResource::collection($table);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coupons_id'      => 'sometimes|nullable|numeric',
            'ads_packages_id'      => 'required|numeric|exists:ads_packages,id',
            'users_id'      => 'required|numeric|exists:users,id',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{
            $today = date('Y-m-d H:i:s');

            $package = AdsPackage::find($request->ads_packages_id);

            $table = new PurchasePackage();
            $table->name        = $package->name;
            $table->types    = $package->types;
            $table->quantity    = $package->quantity;
            $table->amount    = $package->price;
            $table->expire    = date('Y-m-d H:i:s', strtotime($today. ' + '.$package->expire_day.' days'));
            $table->discount    = $request->discount ?? 0;
            $table->coupons_id     = $request->coupons_id;
            $table->ads_packages_id  = $request->ads_packages_id;
            $table->users_id       = $request->users_id;
            $table->save();

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }
        return new PackagePurchaseResource($table);
    }

    public function show($id)
    {
        try{

            $table = PurchasePackage::find($id);

            if(!$table)
                return response()->json(config('naz.n_found'), config('naz.not_found'));

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new PackagePurchaseResource($table);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'coupons_id'      => 'sometimes|nullable|numeric',
            'ads_packages_id'      => 'required|numeric|exists:ads_packages,id',
            'users_id'      => 'required|numeric|exists:users,id',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{

            $table = PurchasePackage::find($id);
            $ads_packages_id = $table->ads_packages_id;
            if($ads_packages_id != $request->ads_packages_id){
                $package = AdsPackage::find($request->ads_packages_id);

                $table->expire    = date('Y-m-d H:i:s', strtotime($table->created_at. ' + '.$package->expire_day.' days'));

                $table->name = $package->name;
                $table->types = $package->types;
                $table->quantity  = $package->quantity;
                $table->amount = $package->price;
            }

            $table->ads_packages_id = $request->ads_packages_id;
            $table->discount = $request->discount ?? 0;
            $table->coupons_id = $request->coupons_id;
            $table->users_id = $request->users_id;
            $table->save();

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }
        return new PackagePurchaseResource($table);
    }

    public function destroy($id)
    {
        try{
            PurchasePackage::destroy($id);
        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json(config('naz.del'));
    }

    public function my_order()
    {
        try{
            $table = PurchasePackage::orderBy('id', 'DESC')->where('users_id', Auth::id())->get();
        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return PackagePurchaseResource::collection($table);
    }
}