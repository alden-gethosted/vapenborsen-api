<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CompanyResource;
use App\Traits\UploadTrait;
use Illuminate\Support\Str;

class CompanyController extends Controller
{

    use UploadTrait;

    public function index($user_id)
    {
        try {
            $user = User::find( $user_id );

            if( !$user ) {
                return response()->json('User Not Found');
            }

            $companies = Company::where( 'users_id', $user_id )->orderBy('id', 'DESC')->get();
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return CompanyResource::collection($companies);
    }


    public function store( $user_id, Request $request )
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|min:3|unique:companies,name',
            'status'        => 'required|boolean',
            'logo'          => 'sometimes|nullable',
            'description'   => 'sometimes|nullable',
            'contact'       => 'sometimes|nullable|max:15|string',
            'contact_person'=> 'sometimes|nullable|string',
            'website'       => 'sometimes|nullable|string'
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try {
            $user = User::find( $user_id );

            if( !$user ) {
                return response()->json('User Not Found');
            }

            $company = new Company();

            $company->name           = $request->name;
            $company->status         = $request->status;
            $company->users_id       = $user_id;
            $company->description    = $request->description;
            $company->contact        = $request->contact;
            $company->contact_person =  $request->contact_person;
            $company->website        = $request->website;

            if ( $request->has('logo') ) {
                // Get image file
                $image = $request->file('logo');
                // Make a image name based on user name and current timestamp
                $name = Str::slug($request->input('name')) . '_' . time();
                // Define folder path
                $folder = '/uploads/company/';
                // Make a file path where image will be stored [ folder path + file name + file extension]
                $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
                // Upload image
                $this->uploadOne($image, $folder, 'public', $name);
                // Set user profile image path in database to filePath
                $company->logo = $filePath;
            }

            $company->save();

        } catch (\Exception $ex) {
            //dd($ex);
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new CompanyResource($company);
        //return response()->json($area);
    }


    public function show( $user_id, $id )
    {
        try{
            $user = User::find( $user_id );

            if( !$user ) {
                return response()->json('User Not Found');
            }

            $company = Company::where( 'users_id', $user_id )->find($id);

        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new CompanyResource($company);
        // return response()->json($area);
    }


    public function update( $user_id, Request $request, $id )
    {

        $validator = Validator::make($request->all(), [
            'name'           => 'required|string|min:3|unique:companies,name,'. $id,
            'status'         => 'required|boolean',
            'logo'           => 'sometimes|nullable',
            'description'    => 'sometimes|nullable',
            'contact'        => 'sometimes|nullable|max:15|string',
            'contact_person' => 'sometimes|nullable|string',
            'website'        => 'sometimes|nullable|string'
        ]);

        if ( $validator->fails() ) return response()->json( $validator->errors(), config('naz.validation') );

        try {

            $user = User::find( $user_id );

            if( !$user ) {
                return response()->json('User Not Found');
            }


            $company = Company::where( 'users_id', $user_id )->where('id',$id)->first();

            $company->name           = $request->name;
            $company->status         = $request->status;
            $company->users_id       = $user_id;
            $company->description    = $request->description;
            $company->contact        = $request->contact;
            $company->contact_person = $request->contact_person;
            $company->website        = $request->website;

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

            $company->save();

        } catch (\Exception $ex) {
            //dd($ex);
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new CompanyResource($company);

        // return response()->json( $area );
    }


    public function destroy( $user_id, $id )
    {
        try{
            $user = User::find( $user_id );

            if( !$user ) {
                return response()->json('User Not Found');
            }

            Company::where( 'users_id', $user_id )->where('id', $id)->delete();
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json(config('naz.del'));
    }
}
