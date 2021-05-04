<?php

namespace App\Http\Controllers\Advertisement;

use App\Http\Controllers\Controller;
use App\Models\AdsPackage;
use Illuminate\Http\Request;
use App\Http\Resources\AdsResource;
use App\Models\Ads;
use App\Traits\UploadTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use App\Models\AdsItem;
use App\Models\AdsTag;
use App\Models\AdsAttribute;
use App\Models\AdsPhoto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdController extends Controller
{
    use UploadTrait;

    public function index(Request $request)
    {
        $today = date('Y-m-d');
        try {
            if(Auth::user()->types == 'Admin'){
                $table = Ads::orderBy('id', 'DESC');
                if(isset($request->users_id)){
                    $table->where('users_id', $request->users_id);
                }
                if(isset($request->status)){
                    $table->where('status', $request->status);
                }
                if(isset($request->expire)){
                    $table->where('expire', '>', $today);
                }
                if(isset($request->ads_packages_id)){
                    $table->where('companies_id', $request->ads_packages_id);
                }
                $ads = $table->get();
            }else{
                $table = Ads::orderBy('id', 'DESC')->where('users_id', Auth::id());

                if(isset($request->status)){
                    $table->where('status', $request->status);
                }
                if(isset($request->expire)){
                    $table->where('expire', '>', $today);
                }

                if(isset($request->ads_packages_id)){
                    $table->where('companies_id', $request->ads_packages_id);
                }

                if(isset($request->companies_id)){
                    $table->where('companies_id', $request->companies_id);
                }

                $ads = $table->get();
            }

        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return AdsResource::collection($ads);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'                  => 'required|string|min:3|unique:ads,name',
            'state'                 => 'required|in:Weapon,Accessories,Other',
            'price'                 => 'required|numeric',
            'is_used'               => 'required|boolean',
            'is_shipping'           => 'required|boolean',
            'status'                => 'required|in:Publish,Pending',
            'users_id'              => 'required|exists:users,id',
            'photo'                 => 'sometimes|mimes:jpg,bmp,png',
            'attribute_vals'        => 'sometimes|nullable|array',
            'tags'                  => 'sometimes|nullable|array',
            'items'                 => 'sometimes|nullable|array',
            'galleries'             => 'sometimes|nullable|array',
            'seller'                => 'sometimes|nullable|string',
            'areas_id'              => 'sometimes|nullable|integer|exists:areas,id',
            'product_brands_id'     => 'sometimes|nullable|integer|exists:product_brands,id',
            'product_categories_id' => 'sometimes|nullable|integer|exists:product_categories,id',
            'product_types_id'      => 'sometimes|nullable|integer|exists:product_types,id',
            'companies_id'          => 'sometimes|nullable|integer|exists:companies,id',
            'ads_packages_id'       => 'sometimes|nullable|integer|exists:ads_packages,id',
            'products_id'           => 'sometimes|nullable|integer|exists:products,id',
            'email'                 => 'sometimes|nullable|email'
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));


        DB::beginTransaction();

        try {
            $today = date('Y-m-d');

            if(isset($request->ads_packages_id)){
                $package = AdsPackage::where('id', $request->ads_packages_id)->where('expire', '>', $today)->count(); //Check it is expire or not

                if($package <= 0){
                    throw new \Exception('This package was expired!!');
                }
            }

            $ads = new Ads();
            $ads->name        = $request->name;
            $ads->state       = $request->state;
            $ads->price       = $request->price;
            $ads->is_used     = $request->is_used;
            $ads->is_shipping = $request->is_shipping;
            $ads->expire      = date('Y-m-d',  strtotime($today.'+60 days' )); //Expire date set to 60 based on current date
            $ads->users_id    = $request->users_id;
            $ads->seller      = $request->seller;
            $ads->areas_id              = $request->areas_id;
            $ads->product_brands_id     = $request->product_brands_id;
            $ads->product_categories_id = $request->product_categories_id;
            $ads->product_types_id      = $request->product_types_id;
            $ads->companies_id          = $request->companies_id;

            if (isset($request->ads_packages_id)) {
                $ads->ads_packages_id       = $request->ads_packages_id;
                $ads->status      = $request->status;
            }else{
                $ads->status       = 'Pending';
            }

            $ads->products_id           = $request->products_id;
            $ads->email         = $request->email;
            $ads->phone         = $request->phone;
            $ads->contact_time  = $request->contact_time;
            $ads->brand         = $request->brand;
            $ads->category      = $request->category;
            $ads->product_types = $request->product_types;
            $ads->descriptions  = $request->descriptions;

            if ($request->has('photo')) {
                $image      = $request->file('photo');
                $photo_name = Str::slug($request->input('name')) . '_' . time();
                $folder     = '/uploads/ads/';
                $filePath   = $folder . $photo_name . '.' . $image->getClientOriginalExtension();

                $this->uploadOne($image, $folder, 'public', $photo_name);

                $ads->photo = $filePath;
            }

            $ads->save();


            if( isset( $request->items ) && \is_array( $request->items ) ) {
                foreach( $request->items as $key => $item ) {
                    $ads_item = new AdsItem();

                    $ads_item->quantity     = $item['quantity'];
                    $ads_item->descriptions = $item['descriptions'];

                    $name          = $ads->id . '_item_' . $key . time();
                    $folder        = '/uploads/ads/';
                    $itemfilePath  = $folder . $name . '.' . $item->getClientOriginalExtension();
                    $this->uploadOne( $item['photo'], $folder, 'public', $name);

                    $ads_item->photo        = $itemfilePath;
                    $ads_item->ads_id       = $ads->id;
                    $ads_item->products_id  = $item['products_id'];

                    $ads_item->save();
                }
            }


            if( isset( $request->attribute_vals ) ) {
                foreach( $request->attribute_vals as $key => $attribute ) {
                    $ads_attribute          = new AdsAttribute();
                    $ads_attribute->name    = $key ;
                    $ads_attribute->values  = json_encode( $attribute );
                    $ads_attribute->ads_id  = $ads->id;

                    $ads_attribute->save();
                }
            }

            if ( isset( $request->tags ) && \is_array( $request->tags ) ) {
                foreach( $request->tags as $tag ) {
                    $ads_tag          = new AdsTag();

                    $ads_tag->name    = $tag;
                    $ads_tag->ads_id  = $ads->id;

                    $ads_tag->save();
                }
            }

            if ($request->has('galleries')) {

                foreach( $request->file('galleries') as $key => $gallery ) {
                    $ads_photo = new AdsPhoto();
                    $name      = $ads->id . '_gallery_' . $key . time();
                    $folder    = '/uploads/ads/';
                    $filePath  = $folder . $name . '.' . $gallery->getClientOriginalExtension();
                    $this->uploadOne( $gallery, $folder, 'public', $name);

                    $ads_photo->name   = $filePath;
                    $ads_photo->ads_id = $ads->id;

                    $ads_photo->save();
                }
            }


        } catch (\Exception $ex) {
            DB::rollBack();
            //dd($ex);
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        DB::commit();

        return new AdsResource($ads);
    }

    public function show($id)
    {
        try{
            $ads = Ads::find($id);

        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new AdsResource($ads);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name'                  => 'required|string|min:3|unique:ads,name',
            'state'                 => 'required|in:Weapon,Accessories,Other',
            'price'                 => 'required|numeric',
            'is_used'               => 'required|boolean',
            'is_shipping'           => 'required|boolean',
            'status'                => 'required|in:Publish,Pending,Canceled',
            'users_id'              => 'required|exists:users,id',
            'photo'                 => 'sometimes|mimes:jpg,bmp,png',
            'attribute_vals'        => 'sometimes|nullable|array',
            'tags'                  => 'sometimes|nullable|array',
            'items'                 => 'sometimes|nullable|array',
            'galleries'             => 'sometimes|nullable|array',
            'seller'                => 'sometimes|nullable|string',
            'areas_id'              => 'sometimes|nullable|integer|exists:areas,id',
            'product_brands_id'     => 'sometimes|nullable|integer|exists:product_brands,id',
            'product_categories_id' => 'sometimes|nullable|integer|exists:product_categories,id',
            'product_types_id'      => 'sometimes|nullable|integer|exists:product_types,id',
            'companies_id'          => 'sometimes|nullable|integer|exists:companies,id',
            'ads_packages_id'       => 'sometimes|nullable|integer|exists:ads_packages,id',
            'products_id'           => 'sometimes|nullable|integer|exists:products,id',
        ]);

        if ( $validator->fails() ) return response()->json( $validator->errors(), config('naz.validation') );

        DB::beginTransaction();

        try {
           $ads              = Ads::find($id);
           $ads->name        = $request->name;
           $ads->state       = $request->state;
           $ads->price       = $request->price;
           $ads->is_used     = $request->price;
           $ads->is_shipping = $request->price;
           $ads->status      = $request->status;
           $ads->seller      = $request->seller;

           $ads->product_brands_id     = $request->product_brands_id;
           $ads->product_categories_id = $request->product_categories_id;
           $ads->product_types_id      = $request->product_types_id;
           $ads->companies_id          = $request->companies_id;
           if (isset($request->status)) {
               $ads->status = $request->status;
           }
           $ads->products_id           = $request->products_id;
           $ads->users_id              = $request->users_id;
           $ads->email         = $request->email;
           $ads->phone         = $request->phone;
           $ads->contact_time  = $request->contact_time;
           $ads->brand         = $request->brand;
           $ads->category      = $request->category;
           $ads->product_types = $request->product_types;
           $ads->descriptions  = $request->descriptions;

           if ($request->has('photo')) {
            $image      = $request->file('photo');
            $photo_name = Str::slug($request->input('name')) . '_' . time();
            $folder     = '/uploads/ads/';
            $filePath   = $folder . $photo_name . '.' . $image->getClientOriginalExtension();

            $this->uploadOne($image, $folder, 'public', $photo_name);

            if( File::exists( $ads->photo ) ) {
                File::delete( $ads->photo );
            }

            $ads->photo = $filePath;
        }

        $ads->save();

        if( isset( $request->items ) && \is_array( $request->items ) ) {
            foreach( $request->items as $key => $item ) {
                $ads_item = AdsItem::where( 'ads_id', $ads->id )->where('id', $item[id])->first();

                $ads_item->quantity     = $item['quantity'];
                $ads_item->descriptions = $item['descriptions'];

                $name          = $ads->id . '_item_' . $key . time();
                $folder        = '/uploads/ads/';
                $itemfilePath  = $folder . $name . '.' . $item->getClientOriginalExtension();
                $this->uploadOne( $item, $folder, 'public', $name);

                if( File::exists( $ads_item->photo ) ) {
                    File::delete( $ads_item->photo );
                }

                $ads_item->photo        = $itemfilePath;
                $ads_item->products_id  = $item['products_id'];

                $ads_item->save();
            }
        }


        if( isset( $request->attribute_vals ) ) {
            foreach( $request->attribute_vals as $key => $attribute ) {
                $ads_attribute  = AdsAttribute::where( 'ads_id', $ads->id )->where('id', $item[id])->first();

                $ads_attribute->name    = $key ;
                $ads_attribute->values  = json_encode( $attribute );
                $ads_attribute->ads_id  = $ads->id;

                $ads_attribute->save();
            }
        }

        if ( isset( $request->tags ) && \is_array( $request->tags ) ) {
            foreach( $request->tags as $tag ) {
                $ads_tag = AdsTag::where( 'ads_id', $ads->id )->where('id', $item[id])->first();

                $ads_tag->name     = $tag;
                $ads_tag->save();
            }
        }

        if ($request->has('galleries')) {
            foreach( $request->file('galleries') as $key => $gallery ) {
                $ads_photo = new AdsPhoto();
                $name      = $ads->id . '_gallery_' . $key . time();
                $folder    = '/uploads/ads/';
                $filePath  = $folder . $name . '.' . $gallery->getClientOriginalExtension();
                $this->uploadOne( $gallery, $folder, 'public', $name);

                if( File::exists( $ads_photo->name ) ) {
                    File::delete( $ads_photo->name );
                }

                $ads_photo->name   = $filePath;

                $ads_photo->save();
            }
        }

        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        DB::commit();

        return new AdsResource($ads);
    }

    public function destroy($id)
    {
        try{
            Ads::destroy($id);
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json(config('naz.del'));
    }
}