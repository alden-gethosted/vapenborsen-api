<?php

namespace App\Http\Controllers\Advertisement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdsFavorites;
use App\Http\Resources\AdFavouriteResource;
use Illuminate\Support\Facades\Validator;

class AdFavouriteController extends Controller
{

    public function index( $users_id )
    {
        try{
            $adFavorite = AdsFavorites::where( 'users_id', $users_id )->orderBy('id', 'DESC')->get();
            if(!$adFavorite)
                return response()->json(config('naz.n_found'), config('naz.not_found'));
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return AdFavouriteResource::collection($adFavorite);
    }


    public function store( $users_id, Request $request )
    {
        $validator = Validator::make($request->all(), [
            'ad_id' => 'required|exists:ads,id',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try {
            $adFavourite           = new AdsFavorites();
            $adFavourite->ads_id   =  $request->ad_id;
            $adFavourite->users_id =  $users_id;

            $adFavourite->save();
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new AdFavouriteResource($adFavourite);
    }


    public function destroy( $users_id, $id )
    {
        try{
            AdsFavorites::where( 'users_id', $users_id )->where('id', $id)->delete();
        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json(config('naz.del'));
    }
}
