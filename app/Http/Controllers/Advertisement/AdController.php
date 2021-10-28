<?php

namespace App\Http\Controllers\Advertisement;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdsResource;
use App\Models\Ads;
use App\Models\AdsAttribute;
use App\Models\AdsItem;
use App\Models\AdsPhoto;
use App\Models\AdsTag;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdController extends Controller
{
    use UploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $ads = Ads::all();
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return AdsResource::collection($ads);
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
            'name' => 'required|string|min:3|unique:ads,name',
            'state' => 'required|in:Weapon,Accessories,Other',
            'price' => 'required|numeric',
            'is_used' => 'required|boolean',
            'is_shipping' => 'required|boolean',
            'status' => 'required|in:Publish,Pending,Canceled,Expire',
            'expire' => 'required|date',
            'users_id' => 'required|exists:users,id',
            'photo' => 'sometimes|mimes:jpg,bmp,png',
            'attribute_vals' => 'sometimes|nullable|array',
            'tags' => 'sometimes|nullable|array',
            'items' => 'sometimes|nullable|array',
            'galleries' => 'sometimes|nullable|array',
            'seller' => 'sometimes|nullable|string',
            'areas_id' => 'integer|exists:areas,id',
            'product_brands_id' => 'integer|exists:product_brands,id',
            'product_categories_id' => 'integer|exists:product_categories,id',
            'product_types_id' => 'integer|exists:product_types,id',
            'companies_id' => 'integer|exists:companies,id',
            'ads_packages_id' => 'integer|exists:ads_packages,id',
            'products_id' => 'integer|exists:products,id',

            'email' => 'sometimes|nullable',
            'phone' => 'sometimes|nullable',
            'contact_time' => 'sometimes|nullable',
            'brand' => 'sometimes|nullable',
            'category' => 'sometimes|nullable',
            'product_types' => 'sometimes|nullable',
            'descriptions' => 'sometimes|nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), config('naz.validation'));
        }

        DB::beginTransaction();

        try {
            $ads = new Ads();

            $ads->name = $request->name;
            $ads->state = $request->state;
            $ads->price = $request->price;
            $ads->is_used = $request->is_used;
            $ads->is_shipping = $request->is_shipping;
            $ads->expire = date('Y-m-d', strtotime($request->expire));
            $ads->status = $request->status;
            $ads->users_id = $request->users_id;
            $ads->seller = $request->seller;

            $ads->areas_id = $request->areas_id;
            $ads->product_brands_id = $request->product_brands_id;
            $ads->product_categories_id = $request->product_categories_id;
            $ads->product_types_id = $request->product_types_id;
            $ads->companies_id = $request->companies_id;
            $ads->ads_packages_id = $request->ads_packages_id;
            $ads->products_id = $request->products_id;

            $ads->email = $request->email;
            $ads->phone = $request->phone;
            $ads->contact_time = $request->contact_time;
            $ads->brand = $request->brand;
            $ads->category = $request->category;
            $ads->product_types = $request->product_types;
            $ads->descriptions = $request->descriptions;

            if ($request->has('photo')) {
                $image = $request->file('photo');
                $photo_name = Str::slug($request->input('name')) . '_' . time();
                $folder = '/uploads/ads/';
                $filePath = $folder . $photo_name . '.' . $image->getClientOriginalExtension();

                $this->uploadOne($image, $folder, 'public', $photo_name);

                $ads->photo = $filePath;
            }

            $ads->save();

            if (isset($request->items) && \is_array($request->items)) {
                foreach ($request->items as $key => $item) {
                    $ads_item = new AdsItem();

                    $ads_item->quantity = $item['quantity'];
                    $ads_item->descriptions = $item['descriptions'];

                    $name = $ads->id . '_item_' . $key . time();
                    $folder = '/uploads/ads/';
                    $itemfilePath = $folder . $name . '.' . $item->getClientOriginalExtension();
                    $this->uploadOne($item['photo'], $folder, 'public', $name);

                    $ads_item->photo = $itemfilePath;
                    $ads_item->ads_id = $ads->id;
                    $ads_item->products_id = $item['products_id'];

                    $ads_item->save();
                }
            }

            if (isset($request->attribute_vals)) {
                foreach ($request->attribute_vals as $key => $attribute) {
                    $ads_attribute = new AdsAttribute();
                    $ads_attribute->name = $key;
                    $ads_attribute->values = json_encode($attribute);
                    $ads_attribute->ads_id = $ads->id;

                    $ads_attribute->save();
                }
            }

            if (isset($request->tags) && \is_array($request->tags)) {
                foreach ($request->tags as $tag) {
                    $ads_tag = new AdsTag();

                    $ads_tag->name = $tag;
                    $ads_tag->ads_id = $ads->id;

                    $ads_tag->save();
                }
            }

            if ($request->has('galleries')) {

                foreach ($request->file('galleries') as $key => $gallery) {
                    $ads_photo = new AdsPhoto();
                    $name = $ads->id . '_gallery_' . $key . time();
                    $folder = '/uploads/ads/';
                    $filePath = $folder . $name . '.' . $gallery->getClientOriginalExtension();
                    $this->uploadOne($gallery, $folder, 'public', $name);

                    $ads_photo->name = $filePath;
                    $ads_photo->ads_id = $ads->id;

                    $ads_photo->save();
                }
            }

        } catch (\Exception $ex) {
            DB::rollBack();
            dd($ex);
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        DB::commit();

        return new AdsResource($ads);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $ads = Ads::find($id);

        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new AdsResource($ads);
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
            'name' => 'required|string|min:3|unique:ads,name',
            'state' => 'required|in:Weapon,Accessories,Other',
            'price' => 'required|numeric',
            'is_used' => 'required|boolean',
            'is_shipping' => 'required|boolean',
            'status' => 'required|in:Publish,Pending,Canceled,Expire',
            'expire' => 'required|date',
            'users_id' => 'required|exists:users,id',
            'photo' => 'sometimes|mimes:jpg,bmp,png',
            'attribute_vals' => 'sometimes|nullable|array',
            'tags' => 'sometimes|nullable|array',
            'items' => 'sometimes|nullable|array',
            'galleries' => 'sometimes|nullable|array',
            'seller' => 'sometimes|nullable|string',
            'areas_id' => 'integer|exists:areas,id',
            'product_brands_id' => 'integer|exists:product_brands,id',
            'product_categories_id' => 'integer|exists:product_categories,id',
            'product_types_id' => 'integer|exists:product_types,id',
            'companies_id' => 'integer|exists:companies,id',
            'ads_packages_id' => 'integer|exists:ads_packages,id',
            'products_id' => 'integer|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), config('naz.validation'));
        }

        DB::beginTransaction();

        try {
            $ads = Ads::find($id);

            $ads->name = $request->name;
            $ads->state = $request->state;
            $ads->price = $request->price;
            $ads->is_used = $request->price;
            $ads->is_shipping = $request->price;
            $ads->expire = $request->expire;
            $ads->status = $request->status;
            $ads->seller = $request->seller;

            $ads->product_brands_id = $request->product_brands_id;
            $ads->product_categories_id = $request->product_categories_id;
            $ads->product_types_id = $request->product_types_id;
            $ads->companies_id = $request->companies_id;
            $ads->ads_packages_id = $request->ads_packages_id;
            $ads->products_id = $request->products_id;
            $ads->users_id = $request->users_id;

            $ads->email = $request->email;
            $ads->phone = $request->phone;
            $ads->contact_time = $request->contact_time;
            $ads->brand = $request->brand;
            $ads->category = $request->category;
            $ads->product_types = $request->product_types;
            $ads->descriptions = $request->descriptions;

            if ($request->has('photo')) {
                $image = $request->file('photo');
                $photo_name = Str::slug($request->input('name')) . '_' . time();
                $folder = '/uploads/ads/';
                $filePath = $folder . $photo_name . '.' . $image->getClientOriginalExtension();

                $this->uploadOne($image, $folder, 'public', $photo_name);

                if (File::exists($ads->photo)) {
                    File::delete($ads->photo);
                }

                $ads->photo = $filePath;
            }

            $ads->save();

            if (isset($request->items) && \is_array($request->items)) {
                foreach ($request->items as $key => $item) {
                    $ads_item = AdsItem::where('ads_id', $ads->id)->where('id', $item[id])->first();

                    $ads_item->quantity = $item['quantity'];
                    $ads_item->descriptions = $item['descriptions'];

                    $name = $ads->id . '_item_' . $key . time();
                    $folder = '/uploads/ads/';
                    $itemfilePath = $folder . $name . '.' . $item->getClientOriginalExtension();
                    $this->uploadOne($item, $folder, 'public', $name);

                    if (File::exists($ads_item->photo)) {
                        File::delete($ads_item->photo);
                    }

                    $ads_item->photo = $itemfilePath;
                    $ads_item->products_id = $item['products_id'];

                    $ads_item->save();
                }
            }

            if (isset($request->attribute_vals)) {
                foreach ($request->attribute_vals as $key => $attribute) {
                    $ads_attribute = AdsAttribute::where('ads_id', $ads->id)->where('id', $item[id])->first();

                    $ads_attribute->name = $key;
                    $ads_attribute->values = json_encode($attribute);
                    $ads_attribute->ads_id = $ads->id;

                    $ads_attribute->save();
                }
            }

            if (isset($request->tags) && \is_array($request->tags)) {
                foreach ($request->tags as $tag) {
                    $ads_tag = AdsTag::where('ads_id', $ads->id)->where('id', $item[id])->first();
                    $ads_tag->name = $tag;
                    $ads_tag->save();
                }
            }

            if ($request->has('galleries')) {
                foreach ($request->file('galleries') as $key => $gallery) {
                    $ads_photo = new AdsPhoto();
                    $name = $ads->id . '_gallery_' . $key . time();
                    $folder = '/uploads/ads/';
                    $filePath = $folder . $name . '.' . $gallery->getClientOriginalExtension();
                    $this->uploadOne($gallery, $folder, 'public', $name);

                    if (File::exists($ads_photo->name)) {
                        File::delete($ads_photo->name);
                    }

                    $ads_photo->name = $filePath;

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Ads::destroy($id);
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json(config('naz.del'));
    }
}
