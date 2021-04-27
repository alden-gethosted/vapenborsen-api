<?php

namespace App\Http\Controllers\Advertisement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdsReview;
use App\Http\Resources\AdReviewsResource;
use Illuminate\Support\Facades\Validator;

class AdReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($ad_id)
    {
        try{
            $reviews = AdsReview::where( 'ads_id', $ad_id )->orderBy('id', 'DESC')->get();
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return AdReviewsResource::collection($reviews);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( $ad_id, Request $request )
    {
        $validator = Validator::make($request->all(), [
            'comment'  => 'required',
            'rating'   => 'required',
            'users_id' => 'required',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{
            $adReview = new AdsReview();

            $adReview->comment  =  $request->comment;
            $adReview->rating   =  $request->rating;
            $adReview->ads_id   =  $ad_id;
            $adReview->users_id =  $request->users_id;

            $adReview->save();

        } catch (\Exception $ex) {
            dd($ex);
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new AdReviewsResource($adReview);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($ad_id, $id)
    {
        try{
            $adReview = AdsReview::where( 'ads_id', $ad_id )->find($id);
            if(!$adReview)
                return response()->json(config('naz.n_found'), config('naz.not_found'));
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new AdReviewsResource($adReview);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update( $ad_id, Request $request, $id )
    {

        $validator = Validator::make($request->all(), [
            'comment'  => 'required',
            'rating'   => 'required',
            'users_id' => 'required',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{
            $adReview = AdsReview::find($id);

            $adReview->comment  =  $request->comment;
            $adReview->rating   =  $request->rating;
            $adReview->ads_id   =  $ad_id;
            $adReview->users_id =  $request->users_id;

            $adReview->save();

        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new AdReviewsResource($adReview);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( $ad_id, $id )
    {
        try{
            AdsReview::where( 'ads_id', $ad_id )->where('id', $id)->delete();
        }catch (\Exception $ex) {
            dd($ex);
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json(config('naz.del'));
    }
}
