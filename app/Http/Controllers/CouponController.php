<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $coupons = Coupon::all();
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return CouponResource::collection($coupons);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'             => 'required|string|min:3|unique:Coupons,code',
            'amount'           => 'required|numeric',
            'is_percent'       => 'required|boolean',
            'status'           => 'required|in:Active,Used,Inactive',
            'ads_packages_id'  => 'sometimes|nullable|exists:ads_packages,id',
            'users_id'         => 'sometimes|nullable|exists:users,id',
            'expire'           => 'sometimes|nullable|date',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try {

            $coupon = new Coupon();
            $coupon->ads_packages_id = $request->ads_packages_id;
            $coupon->users_id        = $request->users_id;
            $coupon->expire          = $request->expire;

            $coupon->code            = $request->code;
            $coupon->amount          = $request->amount;
            $coupon->is_percent      = $request->is_percent;
            $coupon->status          = $request->status;

            $coupon->save();

        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new CouponResource($coupon);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $coupon = Coupon::find($id);
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new CouponResource($coupon);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'code'             => 'required|string|min:3|unique:Coupons,code,'. $id,
            'amount'           => 'required|numeric',
            'is_percent'       => 'required|boolean',
            'status'           => 'required|in:Active,Used,Inactive',
            'ads_packages_id'  => 'sometimes|nullable|exists:ads_packages,id',
            'users_id'         => 'sometimes|nullable|exists:users,id',
            'expire'           => 'sometimes|nullable|date',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try {
           
            $coupon = Coupon::find($id);
           
            if( isset( $request->ads_packages_id ) ) {
                $coupon->ads_packages_id = $request->ads_packages_id;
            }

            if( isset( $request->ads_packages_id ) ) {
                $coupon->users_id        = $request->users_id;
            }

            if( isset( $request->expire ) ) {
                $coupon->expire = $request->expire;
            }

            $coupon->code            = $request->code;
            $coupon->amount          = $request->amount;
            $coupon->is_percent      = $request->is_percent;
            $coupon->status          = $request->status;

            $coupon->save();

        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new CouponResource($coupon);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            Coupon::destroy($id);
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }
       
        return response()->json(config('naz.del'));
    }
}
