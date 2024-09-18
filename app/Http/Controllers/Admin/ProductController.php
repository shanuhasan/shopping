<?php

namespace App\Http\Controllers\admin;

use Sentinel;
use App\Models\Type;
use App\Models\User;
use App\Models\Media;
use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;

class ProductController extends Controller
{
  public function index()
  {
    $data['product_list'] = DB::table('services')
      ->select('services.*', 'categories.category_name', 'sb.category_name as subcategory_name', 'users.name')
      ->leftJoin('categories', 'services.parent_category', '=', 'categories.id')
      ->leftJoin('categories as sb', 'services.subcategory', '=', 'sb.id')
      ->leftJoin('users', 'services.add_by', '=', 'users.id')
      ->orderBy('services.id', 'desc')
      ->get();

    $data['page_title'] = 'All Products';
    return view('admin.product.index', $data);
  }

  public function create()
  {
    $data['category'] = Category::getCategory();
    $data['tax_pay'] = DB::table('tax_pay')->get();
    $data['types'] = Type::where(['status' => 1])->get();
    $data['page_title'] = 'Add Service';
    $data['all_product'] = Service::all();
    $data['subattributes'] = DB::table('sub_attributes')->where('status', '1')->get();

    return view('admin.product.create', $data);
  }

  public function store(Request $request)
  {
    if ($request->gift_wrap) {
      $gift_wrap = 'YES';
    } else {
      $gift_wrap = 'NO';
    }

    $validator = Validator::make($request->all(), [
      'parent_category' => 'required',
      'service_name' => 'required',
    ]);

    if ($validator->passes()) {
      $model = new Service();
      $model->parent_category = $request->parent_category;
      $model->subcategory = $request->subcategory;
      $model->child_id = $request->childcategory;
      $model->service_name = $request->service_name;
      $model->product_tax = $request->product_tax;
      $model->tax_include = $request->tax_include;
      $model->length = $request->length;
      $model->breadth = $request->breadth;
      $model->height = $request->height;
      $model->weight = $request->weight;
      $model->sku = $request->sku;
      $model->mfg_date = $request->mfg_date;
      $model->expiry_date = $request->expiry_date;
      $model->meta_title = $request->meta_title;
      $model->seller_price = $request->whole_seller_price;
      $model->related_product = json_encode($request->related_product);
      $model->type_id = $request->type_id;
      $model->key_feature = $request->key_feature;
      $model->packing_type = $request->packing_type;
      $model->disclaimer = $request->disclaimer;
      $model->meta_keyword = $request->meta_keyword;
      $model->meta_description = $request->meta_description;
      $model->slug = str_replace(' ', '-', $request->slug);
      $model->short_description = $request->short_description;
      $model->description = $request->description;
      $model->created_date = date('Y-m-d H:i:s');
      $model->updated_date = date('Y-m-d H:i:s');
      $model->status = 1;
      $model->add_by = Auth::user()->id;
      $model->gift_wrap = $gift_wrap;

      if ($request->type_id == 10) {
        $model->comdown_start = @$request->comdown_start;
        $model->comdown_end = @$request->comdown_end;
      }

      $model->save();

      //save image
      if (!empty($request->image_id)) {
        $tempImage = Media::find($request->image_id);
        $extArray = explode('.', $tempImage->name);
        $ext = last($extArray);

        $newImageName = $model->id . time() . '.' . $ext;
        $sPath = public_path() . '/temp/' . $tempImage->name;
        $dPath = public_path() . '/uploads/service/' . $newImageName;
        File::copy($sPath, $dPath);

        //generate thumb
        $dPath = public_path() . '/uploads/service/thumb/' . $newImageName;
        $manager = new ImageManager(new Driver());
        $img = $manager->read($sPath);
        $img->cover(450, 600);
        $img->save($dPath);

        $model->image = $newImageName;
        $model->save();
      }

      $id = $model->id;

      $sale_price = $request->sale_price;
      $item_mrp_price = $request->service_price;
      $unit = $request->unit;
      $type = $request->type;
      $stock = $request->stock;
      $color = $request->color;
      $size = $request->size;
      $unit_value = $request->unit_value;

      if (array_filter($sale_price)) {
        $item_image = $request->file('item_image');
        if ($request->hasFile('item_image')) :
          $image_item_data = array();
          foreach ($item_image as $item):
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $imageName = $time . '-' . $item->getClientOriginalName();
            $destinationPath1 = public_path() . '/uploads/items/';
            $item->move($destinationPath1, $imageName);
            $image_item_data[] = $imageName;
          endforeach;
        endif;

        foreach (array_filter($sale_price) as $key => $items) {
          $item_array = array(
            'item_price' => $sale_price[$key],
            'item_mrp_price' => $item_mrp_price[$key],
            'item_unit' => $unit[$key],
            'type' => $type[$key],
            'stock' => $stock[$key],
            'color' => $color[$key],
            'image' => $image_item_data[$key],
            'size' => $size[$key],
            'item_unit_value' => $unit_value[$key],
            'product_id' => $id,
            'short' => $key,
          );
          DB::table('product_items')->insert($item_array);
        }
      }

      $images = $request->file('images');
      if ($request->hasFile('images')) :
        foreach ($images as $item):
          $var = date_create();
          $time = date_format($var, 'YmdHis');
          $imageName = $time . '-' . $item->getClientOriginalName();
          $destinationPath1 = public_path() . '/uploads/service/';
          $item->move($destinationPath1, $imageName);
          $image_data = array('product_id' => $id, 'image' => $imageName);
          DB::table('product_images')->insert($image_data);
        endforeach;
      endif;

      Session::flash('success', 'Service Added Successfully.');
      return redirect()->route('admin.product');
    } else {
      Session::flash('error', 'Please fill the required fields.');
      return redirect()->back()
        ->withErrors($validator)
        ->withInput();
    }
  }

  public function edit($id)
  {
    $data['tax_pay'] = DB::table('tax_pay')->get();

    $data['types'] = Type::where(['status' => 1])->get();

    $data['edit_data'] = Service::find($id);
    $data['all_product'] = Service::all();
    $data['gallery'] = DB::table('product_images')->where('product_id', $id)->get();
    $data['items'] = DB::table('product_items')->where('product_id', $id)->get();

    $data['category'] = Category::getCategory();
    $data['subcategory'] = Category::getSubCategory($data['edit_data']->parent_category);
    $data['child_category'] = Category::where('parent_id', $data['edit_data']->subcategory)->get();

    $data['subattributes'] = DB::table('sub_attributes')->where('status', '1')->get();



    //dd($data);

    $data['page_title'] = 'Edit Product';
    return view('admin.product.edit', $data);
  }

  public function update(Request $request)
  {
    $id = $request->update_id;

    if ($request->gift_wrap) {
      $gift_wrap = 'YES';
    } else {
      $gift_wrap = 'NO';
    }

    $model = Service::findById($id);

    $validator = Validator::make($request->all(), [
      'parent_category' => 'required',
      'service_name' => 'required',
    ]);

    if ($validator->passes()) {

      $model->parent_category = $request->parent_category;
      $model->subcategory = $request->subcategory;
      $model->child_id = $request->childcategory;
      $model->service_name = $request->service_name;
      $model->product_tax = $request->product_tax;
      $model->tax_include = $request->tax_include;
      $model->length = $request->length;
      $model->breadth = $request->breadth;
      $model->height = $request->height;
      $model->weight = $request->weight;
      $model->sku = $request->sku;
      $model->mfg_date = $request->mfg_date;
      $model->expiry_date = $request->expiry_date;
      $model->meta_title = $request->meta_title;
      $model->seller_price = $request->whole_seller_price;
      $model->related_product = json_encode($request->related_product);
      $model->type_id = $request->type_id;
      $model->key_feature = $request->key_feature;
      $model->packing_type = $request->packing_type;
      $model->disclaimer = $request->disclaimer;
      $model->meta_keyword = $request->meta_keyword;
      $model->meta_description = $request->meta_description;
      $model->slug = str_replace(' ', '-', $request->slug);
      $model->short_description = $request->short_description;
      $model->description = $request->description;
      $model->updated_date = date('Y-m-d H:i:s');
      $model->status = 1;
      $model->add_by = Auth::user()->id;
      $model->gift_wrap = $gift_wrap;

      if ($request->type_id == 10) {
        $model->comdown_start = @$request->comdown_start;
        $model->comdown_end = @$request->comdown_end;
      }

      $model->save();

      $oldImage = $model->image;

      //save image
      if (!empty($request->image_id)) {
        $tempImage = Media::find($request->image_id);
        $extArray = explode('.', $tempImage->name);
        $ext = last($extArray);

        $newImageName = $model->id . time() . '.' . $ext;
        $sPath = public_path() . '/temp/' . $tempImage->name;
        $dPath = public_path() . '/uploads/service/' . $newImageName;
        File::copy($sPath, $dPath);

        //generate thumb
        $dPath = public_path() . '/uploads/service/thumb/' . $newImageName;
        $manager = new ImageManager(new Driver());
        $img = $manager->read($sPath);
        $img->cover(450, 600);
        $img->save($dPath);

        $model->image = $newImageName;
        $model->save();

        //delete old image
        File::delete(public_path() . '/uploads/category/thumb/' . $oldImage);
        File::delete(public_path() . '/uploads/category/' . $oldImage);
      }

      $sale_price = $request->sale_price;
      $service_price = $request->service_price;
      $unit = $request->unit;
      $type = $request->type;
      $stock = $request->stock;
      $color = $request->color;
      $minimum_order_qty = $request->minimum_order_qty;
      $size = $request->size;
      $item_id = $request->item_id;
      $unit_value = $request->unit_value;

      if ($item_id) {
        $i = 1;
        foreach ($item_id as $key => $items) {

          $check_items = DB::table('product_items')->where('id', $items)->first();
          $item_array = array(
            'item_price' => $sale_price[$key],
            'item_mrp_price' => $service_price[$key],
            'item_unit' => $unit[$key],
            'minimum_order_qty' => $minimum_order_qty[$key],
            'type' => $type[$key],
            'stock' => $stock[$key],
            'color' => $color[$key],
            'size' => $size[$key],
            'item_unit_value' => $unit_value[$key],
            'product_id' => $id,
            'short' => $i,
          );

          if ($check_items) {
            DB::table('product_items')->where('product_id', $id)->where('id', @$item_id[$key])->update($item_array);
          }
          $i++;
        }
      }

      $images = $request->file('images');
      if ($request->hasFile('images')) :
        foreach ($images as $item):
          $var = date_create();
          $time = date_format($var, 'YmdHis');
          $imageName = $time . '-' . $item->getClientOriginalName();
          $destinationPath1 = public_path() . '/uploads/service/';
          $item->move($destinationPath1, $imageName);
          $image_data = array('product_id' => $id, 'image' => $imageName);
          DB::table('product_images')->insert($image_data);
        endforeach;
      endif;

      Session::flash('success', 'Service update Successfully....');
      return redirect()->route('admin.product.edit', $id);
    } else {
      Session::flash('error', 'Please fill the required fields.');
      return redirect()->back()
        ->withErrors($validator)
        ->withInput();
    }
  }

  public function view($id)
  {
    $data['edit_data'] = Service::find($id);
    $data['category'] = Category::all();
    $data['page_title'] = 'View Product';
    return view('admin.product.view', $data);
  }

  public function active($id)
  {
    $data = array('status' => '0');
    DB::table('services')->where('id', $id)->update($data);
    Session::flash('success', 'Status Update successfully...');
    return redirect()->route('admin.product');
  }

  public function deactive($id)
  {
    $data = array('status' => '1');
    DB::table('services')->where('id', $id)->update($data);
    Session::flash('success', 'Status Update successfully...');
    return redirect()->route('admin.product');
  }

  public function delete($id)
  {
    $edit_data = Service::find($id);
    File::delete(public_path() . '/uploads/service/thumb/' . $edit_data->image);
    File::delete(public_path() . '/uploads/service/' . $edit_data->image);
    DB::table('services')->where('id', $id)->delete();
    Session::flash('success', 'Delete successfully...');
    return redirect()->route('admin.product');
  }

  public function get_child_category_by_ajax(Request $request)
  {
    $value_d = $request->value;
    $data = DB::table('categories')->where('parent_id', $value_d)->get();
    echo "<option value=''>select child category</option>";
    foreach ($data as $key => $value) {
      echo "<option value='" . $value->id . "'>" . $value->category_name . "</option>";
    }
  }

  public function uploadvariantimages(Request $request)
  {
    $variant_id = $request->variant_id;
    if ($request->hasFile('file')) {
      $image = $request->file('file');
      $image_name_product = time() . '.' . $image->getClientOriginalExtension();
      //return $image_name_product;
      $destinationPath = public_path() . '/uploads/items/';
      $image->move($destinationPath, $image_name_product);
      $edit_data = DB::table('product_items')->where('id', $variant_id)->first();
      $image_path = public_path() . '/uploads/items/' . $edit_data->image;
      if ($edit_data->image) {
        if (file_exists($image_path)) {
          unlink($image_path);
        }
      }
    }
    DB::table('product_items')->where('id', $variant_id)->update(['image' => $image_name_product]);
    echo $image_name_product;
  }

  public function add_itemsinproduct(Request $request)
  {

    $data = DB::table('sub_attributes')->where('status', '1')->get();
    $id = $request->id;
    $item_array = array(
      'item_price' => 0,
      'item_mrp_price' => 0,
      'item_unit' => '',
      'minimum_order_qty' => 1,
      'type' => 'normal',
      'stock' => '0',
      'color' => '',
      'size' => '',
      'item_unit_value' => '',
      'product_id' => $id,
      'short' => '0',
    );
    $id = DB::table('product_items')->insertGetId($item_array);
    $color = '';
    foreach ($data as $colors) {
      if ($colors->attributes_id == '1') {
        $color .= '<option value="' . $colors->slug . '">' . $colors->sub_attributes_name . '</option>';
      }
    }
    $size = '';
    foreach ($data as $sizes) {
      if ($sizes->attributes_id == '2') {
        $size .= '<option value="' . $sizes->slug . '">' . $sizes->sub_attributes_name . '</option>';
      }
    }
    $unit = '';
    foreach ($data as $units) {
      if ($units->attributes_id == '3') {
        $unit .= '<option value="' . $units->slug . '">' . $units->sub_attributes_name . '</option>';
      }
    }
    echo '
     <div class="row">
                <div class="col-lg-2 col-6">
           <label>Product type</label> <span style="color:red">*</span>
            <select class="form-control" name="type[]">
             <option  value="normal">Normal</option>
            
             
           </select>
            
            </div>
                 <div class="col-lg-2 col-6">
           <label>Mrp Price</label> <span style="color:red">*</span>
           <input type="hidden" name="item_id[]" value="' . $id . '">
            <input type="number" name="service_price[]"  placeholder="Product Mrp Price" class="form-control"></div>
                <div class="col-lg-2 col-6">
           <label>Sale Price</label> <span style="color:red">*</span>
            <input type="number" placeholder="Product Sale Price" name="sale_price[]" class="form-control"></div>
             <div class="col-lg-2 col-6">
           <label>Stock</label> <span style="color:red">*</span>
            <input type="number" name="stock[]" placeholder="Stock" class="form-control"></div>
             <div class="col-lg-2 col-6">
           <label>Color</label> <span style="color:red">*</span>
          <select class="form-control" name="color[]">
              <option value="">Select Color</option>
              ' . $color . '
          </select>
          </div>
          <div class="col-lg-2 col-6">
           <label>Size</label> <span style="color:red">*</span>
          <select class="form-control" name="size[]">
              <option value="">Select Size</option>
            ' . $size . '
          </select>
          </div>
             <div class="col-lg-2 col-6">
           <label>Choose Unit</label>
           <select class="form-control" name="unit[]">
            <option value="">Select Unit</option>
           
             ' . $unit . '
           </select>
           </div>
            <div class="col-lg-2 col-6">
           <label>Unit Value</label> <span style="color:red">*</span>
            <input type="number" name="unit_value[]"   class="form-control" min="1"></div>
            <div class="col-lg-2 col-6">
           <label>Minimum Order qty</label> <span style="color:red">*</span>
            <input type="number" name="minimum_order_qty[]" value="1"  class="form-control" min="1"></div>
             <div class="col-lg-2 col-6">
           <label>Image</label> <span style="color:red">*</span>
            <input type="file" name="images" id="variant_image' . $id . '" data-id="' . $id . '"   class="form-control variantimageupload"></div>
            <div class="col-lg-2 col-6">
                <br>
                <a href="javascript:void(0);" class="btn btn-primary btn-sm remove_items">Remove</a>
                </div>
            </div>
            <hr>
     ';
  }

  public function deleteProductitems(Request $request)
  {
    DB::table('product_items')->where('id', $request->id)->delete();
  }

  public function get_subcategory(Request $request)
  {
    $parent_id = $request->cat;
    $data = Category::where('parent_id', $parent_id)->get();
    echo "<option value=''>Select Subcategory</option>";
    foreach ($data as $key => $value) {
      echo "<option value='" . $value->id . "'>" . $value->category_name . "</option>";
    }
  }

  public function get_service_bysubcategory(Request $request)
  {
    $subcat = $request->cat;

    $data = Service::where('subcategory', $subcat)->get();

    if ($data) {
      foreach ($data as $key => $value) {
        $img1 = asset('/') . 'uploads/service/' . $value->image;
        if ($value->image) {
          $img = '<img src="' . $img1 . '" style="width: 100%; padding:1%">';
        } else {
          $img = '';
        }
        echo ' <div class="col-lg-3 add_product" data-id="' . $value->id . '">
                ' . $img . '

                <h6>Rs. ' . $value->service_price . '</h6>
                <span>' . $value->service_name . '</span>
              </div>';
      }
    } else {
      echo "<li>No data</li>";
    }
  }

  public function findProducts(Request $request)
  {

    $term = $request->term;
    $page = $request->page - 1;

    $search = DB::table('services');

    $rows['results'] = $search->where("services.service_name", "LIKE", "%{$term}%")
      ->orWhere("services.description", "LIKE", "%{$term}%")
      ->orWhere("services.meta_title", "LIKE", "%{$term}%")
      ->orWhere("services.slug", "LIKE", "%{$term}%")
      ->orWhere("services.meta_keyword", "LIKE", "%{$term}%")
      ->orWhere("product_items.item_unit_value", "LIKE", "%{$term}%")
      ->orWhere("product_items.item_unit", "LIKE", "%{$term}%")
      ->offset($page)->limit($request->limit)
      ->select(DB::raw("
                            services.id,product_items.id as item_id, 
                            concat(services.service_name,' ',product_items.item_unit_value,' ',product_items.item_unit) as text,
                            product_items.item_unit_value,
                            product_items.item_unit
                            "))
      ->leftJoin('product_items', 'services.id', '=', 'product_items.product_id')
      ->get();

    $count = count(DB::table('services')->where("service_name", "LIKE", "%{$term}%")->orWhere("description", "LIKE", "%{$term}%")->orWhere("meta_title", "LIKE", "%{$term}%")->orWhere("slug", "LIKE", "%{$term}%")->orWhere("meta_keyword", "LIKE", "%{$term}%")->get());
    $rows['total_count'] = $count;
    $rows['incomplete_results'] = $count > 0 ? true : false;
    return response()->json($rows);
  }

  public function remove_product_images(Request $request)
  {
    ///echo $request->id; die;
    $edit_data = DB::table('product_images')->where('id', $request->id)->first();
    $image_profile = public_path('uploads/service/' . $edit_data->image);
    if ($edit_data->image) {
      if (file_exists($image_profile)) {
        unlink($image_profile);
      }
    }
    DB::table('product_images')->where('id', $request->id)->delete();
    echo "yes";
  }

  // attrute create 
  public function attribute()
  {
    $attributeData = DB::table('attributes')->get();

    foreach ($attributeData as $key => $value) {
      $attributeData[$key]->attribute_varition = DB::table('sub_attributes')->where('attributes_id', $value->id)->get()->toArray();
    }

    $data['attributes'] = $attributeData;
    $data['page_title'] = 'Attribute';

    return view('admin/attribute/attribute', $data);
  }

  public function attribute_varition_configer($id = '', $varition = '')
  {

    $data['varition'] = $varition;
    if ($varition != '') {
      $data['edit_varition'] = DB::table('sub_attributes')->where('id', $varition)->first();
    }
    $data['attributes'] = DB::table('attributes')->where('id', $id)->first();
    $data['attributes_varition'] = DB::table('sub_attributes')->where('attributes_id', $id)->get()->toArray();

    $data['page_title'] = 'Attribute Varition';
    //dd($data);
    return view('admin/attribute/attribute_varition', $data);
  }

  public function add_varition(Request $request)
  {
    $attribute_varition_id = $request->attribute_varition_id;
    $attributes_id = $request->attributes_id;
    $name = $request->name;
    $slug = @$request->slug;

    if ($attribute_varition_id > 0) {

      DB::table('sub_attributes')->where('id', $attribute_varition_id)->update([
        'attributes_id' => $attributes_id,
        'sub_attributes_name' => $name,
        'slug' => $slug
      ]);

      Session::flash('success', 'update successfully');
    } else {

      DB::table('sub_attributes')->insert([
        'attributes_id' => $attributes_id,
        'sub_attributes_name' => $name,
        'slug' => $slug,
        'status' => 1
      ]);

      Session::flash('success', 'Create successfully');
    }

    return back();
  }

  public function complaint()
  {
    $complain = DB::table('complains')->get();
    $data['page_title'] = 'Complain';
    $data['complains'] = $complain;
    return view('admin.complain.complain', $data);
  }

  public function review()
  {

    if (!Auth::check()) {
      return redirect('/');
    }

    $productsReview = DB::table('services')
      ->select(DB::raw(
        'services.id AS id, 
              services.service_name, 
              services.add_by
              '
      ))
      ->leftJoin('order_items', 'services.id', '=', 'order_items.product_id')
      ->whereNotNull('order_items.review')
      ->get();

    $data['review'] = $productsReview;
    $data['page_title'] = 'Review List';

    //dd($data);
    return view('admin.review', $data);
  }

  public function review_list_detail($id)
  {

    if (!Sentinel::check()) {
      return redirect('/');
    }

    $productsReview = DB::table('order_items')->where('product_id', $id)->whereNotNull('order_items.review')->get();
    foreach ($productsReview  as $review) {

      $order = DB::table('orders')->where('id', $review->order_id)->first();
      $userName = DB::table('users')->where('id', $order->user_id)->first();
      $rev[] = [
        "id" => $review->id,
        "order_id" => $review->order_id,
        "product_id" => $review->product_id,
        "review" => $review->review,
        "rating" => $review->rating,
        "username" => $userName->first_name . ' ' . $userName->last_name,
      ];
    }

    $data['review'] = $rev;
    // $data['vendors']=$user;
    $data['page_title'] = 'Review List';

    //dd($data);
    return view('admin/vendors/review_detail', $data);
  }
}
