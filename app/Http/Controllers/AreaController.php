<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\AreaResource;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index()
    {
        try {
            $areas = Area::all();
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return AreaResource::collection($areas);
        //return response()->json($areas);
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|unique:Areas,name',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try {

            $area       = new Area();
            $area->name = $request->name;
          
            if ( isset( $request->address ) ) {
                $area->address   = $request->address;
            }

            if ( isset( $request->longitude ) ) {
                $area->longitude = $request->longitude;
            }

            if ( isset( $request->latitude ) ) {
                $area->latitude = $request->latitude;
            }
            
            $area->save();

        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new AreaResource($area);
        //return response()->json($area);
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
            $area = Area::find($id);

        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new AreaResource($area);
        // return response()->json($area);
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|unique:Areas,name',
        ]);

        if ( $validator->fails() ) return response()->json( $validator->errors(), config('naz.validation') );

        try {

            $area = Area::find($id);
            $area->name = $request->name;

            if ( isset( $request->address ) ) {
                $area->address   = $request->address;
            }

            if ( isset( $request->longitude ) ) {
                $area->longitude = $request->longitude;
            }

            if ( isset( $request->latitude ) ) {
                $area->latitude = $request->latitude;
            }
          
            $area->save();

        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new AreaResource($area);

        // return response()->json( $area );
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
            Area::destroy($id);
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }
       
        return response()->json(config('naz.del'));
    }
}
