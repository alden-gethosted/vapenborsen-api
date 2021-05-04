<?php

namespace App\Http\Controllers\Advertisement;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdsMessageResource;
use App\Models\AdsMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdMessageController extends Controller
{

    public function index(Request $request)
    {
        try{

            $tablex =  AdsMessage::orderBy('id', 'DESC');
                if (isset($request->ads_id)) {
                    $tablex->where('ads_id', $request->ads_id);
                }
            $table = $tablex->get();

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return AdsMessageResource::collection($table);
    }

    public function message($id)
    {
        try{

            $table =  AdsMessage::orderBy('id', 'DESC')->where('ads_id', $id)->get();

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return AdsMessageResource::collection($table);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message'       => 'required|string',
            'is_buyer'   => 'required|boolean',
            'ads_id'      => 'required|numeric|exists:ads,id',
            'users_id'      => 'required|numeric|exists:users,id',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try{

            $table = new AdsMessage();
            $table->message        = $request->message;
            $table->is_buyer       = $request->is_buyer;
            $table->ads_id    = $request->ads_id;
            $table->users_id  = $request->users_id;
            $table->save();

        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new AdsMessageResource($table);
    }

    public function destroy($id)
    {
        try{
            AdsMessage::destroy($id);
        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json(config('naz.del'));
    }
}
