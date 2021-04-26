<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CompanyResource;
use App\Traits\UploadTrait;
use Illuminate\Support\Str;

class CompanyController extends Controller
{

    use UploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index($user_id)
    {
        try {
            $companies = Company::where( 'users_id', $user_id )->orderBy('id', 'DESC')->get();
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return CompanyResource::collection($companies);
        //return response()->json($areas);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( $user_id, Request $request )
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|unique:Areas,name',
            'status' => 'required|integer'
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try {
            $company = new Company();
            
            $company->name     = $request->name;
            $company->status   = $request->status;
            $company->users_id = $user_id;
            
            if( isset( $request->description ) ) {
                $company->description = $request->description;
            }

            if ( $request->has('logo') ) {
                // Get image file
                $image = $request->file('logo');
                // Make a image name based on user name and current timestamp
                $name = Str::slug($request->input('name')) . '_' . time();
                // Define folder path
                $folder = '/uploads/categories/';
                // Make a file path where image will be stored [ folder path + file name + file extension]
                $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
                // Upload image
                $this->uploadOne($image, $folder, 'public', $name);
                // Set user profile image path in database to filePath
                $company->logo = $filePath;
            }

            if( isset( $request->contact ) ) {
                $company->contact = $request->contact; 
            }
            
            if( isset( $request->contact_person ) ) {
                $company->contact_person =  $request->contact_person; 
            }

            if( isset( $request->contact_person ) ) {
                $company->contact_person = $request->contact_person;
            }
        
            $company->save();

        } catch (\Exception $ex) {
            dd($ex);
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new CompanyResource($company);
        //return response()->json($area);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( $user_id, $id )
    {
        try{
            $company = Company::where( 'users_id', $user_id )->find($id);

        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new CompanyResource($company);
        // return response()->json($area);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update( $user_id, Request $request, $id )
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|unique:Areas,name',
        ]);

        if ( $validator->fails() ) return response()->json( $validator->errors(), config('naz.validation') );

        try {

            $company = Company::where( 'users_id', $user_id )->find($id);
          
            $company->name     = $request->name;
            $company->status   = $request->status;
            $company->users_id = $user_id;
            
            if( isset( $request->description ) ) {
                $company->description = $request->description;
            }

            if ( $request->has('logo') ) {
                // Get image file
                $image = $request->file('logo');
                // Make a image name based on user name and current timestamp
                $name = Str::slug($request->input('name')) . '_' . time();
                // Define folder path
                $folder = '/uploads/categories/';
                // Make a file path where image will be stored [ folder path + file name + file extension]
                $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
                // Upload image
                $this->uploadOne($image, $folder, 'public', $name);
                // Set user profile image path in database to filePath
                $company->logo = $filePath;
            }

            if( isset( $request->contact ) ) {
                $company->contact = $request->contact; 
            }
            
            if( isset( $request->contact_person ) ) {
                $company->contact_person =  $request->contact_person; 
            }

            if( isset( $request->contact_person ) ) {
                $company->contact_person = $request->contact_person;
            }
        
            $company->save();

        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new CompanyResource($company);

        // return response()->json( $area );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( $user_id, $id )
    {
        try{
            Company::where( 'users_id', $user_id )->where('id', $id)->delete();
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }
       
        return response()->json(config('naz.del'));
    }
}
