<?php

namespace App\Http\Controllers\admin;

use Sentinel;
use App\Models\Type;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\VendorModel as VendorModel;
use App\Models\ServiceModel as ServiceModel;
use App\Models\Category_model as Category_model;

class ProductController extends Controller
{

  public function get_child_category_by_ajax(Request $request)
  {

    $value_d = $request->value;
    $data = DB::table('categories')->where('parent_id', $value_d)->get();
    echo "<option value=''>select child category</option>";
    foreach ($data as $key => $value) {
      echo "<option value='" . $value->id . "'>" . $value->category_name . "</option>";
    }
  }


  public function select_custome_city(Request $request)
  {

    $value_d = $request->value;
    $data = DB::table('tbl_pincode')->where('city_id', $value_d)->get();
    // echo "<option value=''>select postal code</option>";
    foreach ($data as $key => $value) {
      echo "<option value='" . $value->pincode . "'>" . $value->pincode . "</option>";
    }
  }


  function add_product()
  {

    $data['category'] = Category_model::all();
    $data['tax_pay'] = DB::table('tax_pay')->get();
    $type_data = Type::where(['status' => 1])->get();
    $types = array('' => 'Select Type');
    foreach ($type_data as $type) {
      $types[$type->id] = $type->name;
    }

    $role_customers = Sentinel::findRoleBySlug('vendor');
    $customers = $role_customers->users()->get();
    $data['vendors'] = $customers;
    $data['types'] = $types;
    $data['page_title'] = 'Add Service';
    $data['all_product'] = ServiceModel::all();
    $data['subattributes'] = DB::table('sub_attributes')->where('status', '1')->get();

    $data['custom_city'] = DB::table('tbl_custom_city')->where('status', '1')->get();
    $data['country'] = DB::table('countries')->get();

    return view('admin/add_product', $data);
  }


  function get_subcategory(Request $request)
  {
    $parent_id = $request->cat;
    $data = Category_model::where('parent_id', $parent_id)->get();
    echo "<option value=''>Select Subcategory</option>";
    foreach ($data as $key => $value) {
      echo "<option value='" . $value->id . "'>" . $value->category_name . "</option>";
    }
  }
  function get_vendor_bycategory1(Request $request)
  {
    $parent_id = $request->cat;
    $data = VendorModel::where('parent_category', $parent_id)->get();
    echo "<option value=''>Select Vendor</option>";
    foreach ($data as $key => $value) {
      echo "<option value='" . $value->id . "'>" . $value->name . " " . $value->last_name . "</option>";
    }
  }
  function get_service_bysubcategory(Request $request)
  {
    $subcat = $request->cat;

    $data = ServiceModel::where('subcategory', $subcat)->get();

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
  function get_vendor_details(Request $request)
  {
    $id = $request->vendor_id;
    $data = VendorModel::find($id);
    echo '
     <b>Name</b>: ' . $data->name . '  ' . $data->last_name . ',
     <b>Phone</b>: ' . $data->phone . ',
     <b>Address</b>: ' . $data->address . ',
     ';
  }
  function base_url()
  {
    $base_url = $_SERVER["DOCUMENT_ROOT"];
    return $base_url;
  }

  function insert_service(Request $request)
  {

    if ($request->gift_wrap) {
      $gift_wrap = 'YES';
    } else {
      $gift_wrap = 'NO';
    }

    $custom_city_id = $request->custom_city;
    $pincode_id = json_encode($request->postalcode);

    if ($request->parent_category && @$request->service_name) {

      if ($request->hasFile('image')) {
        $image = $request->file('image');
        $image_name_product = time() . '.' . $image->getClientOriginalExtension();
        $destinationPath = $this->base_url() . '/uploads/service/';
        $image->move($destinationPath, $image_name_product);
      }

      $data = array(
        'parent_category' => $request->parent_category,
        'subcategory' => $request->subcategory,
        'child_id' => $request->childcategory,
        'service_name' => $request->service_name,
        'product_tax' => @$request->product_tax,
        'tax_include' => @$request->tax_include,
        'length' => @$request->length,
        'breadth' => @$request->breadth,
        'height' => @$request->height,
        'weight' => @$request->weight,
        'sku' => @$request->sku,
        'mfg_date' => @$request->mfg_date,
        'expiry_date' => @$request->expiry_date,
        'country_origin' => @$request->country_origin,
        'meta_title' => $request->meta_title,
        'seller_price' => @$request->whole_seller_price,
        'related_product' => json_encode($request->related_product),
        'type_id' => $request->type_id,
        'custom_city_id' => $custom_city_id,
        'pincode_id' => $pincode_id,
        'key_feature' => $request->key_feature,
        'packing_type' => $request->packing_type,
        'disclaimer' => $request->disclaimer,
        'meta_keyword' => $request->meta_keyword,
        'meta_description' => $request->meta_description,
        'slug' => str_replace(' ', '-', $request->slug),
        'short_description' => $request->short_description,
        'description' => $request->description,
        'created_date' => date('Y-m-d H:i:s'),
        'updated_date' => date('Y-m-d H:i:s'),
        'image' => @$image_name_product,
        'status' => '1',
        'add_by' => isset($request->vendors) ? $request->vendors : $users = Sentinel::getUser()->id,
        'gift_wrap' => $gift_wrap,
      );

      if ($request->type_id == 10) {
        $data['comdown_start'] = @$request->comdown_start;
        $data['comdown_end'] = @$request->comdown_end;
      }

      $id = DB::table('services')->insertGetId($data);

      $sale_price = $request->sale_price;
      $item_mrp_price = $request->service_price;
      $unit = $request->unit;
      $type = $request->type;
      $stock = $request->stock;
      $color = $request->color;
      $size = $request->size;
      $unit_value = $request->unit_value;
      // DB::table('product_items')->where('product_id',$id)->delete();
      if (array_filter($sale_price)) {
        $item_image = $request->file('item_image');

        if ($request->hasFile('item_image')) :
          $image_item_data = array();
          foreach ($item_image as $item):
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $imageName = $time . '-' . $item->getClientOriginalName();
            $destinationPath1 = $this->base_url() . '/uploads/items/';
            $item->move($destinationPath1, $imageName);
            $image_item_data[] = $imageName;
          endforeach;
        else:

        endif;
        // print_r($image_item_data); die;


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
          $destinationPath1 = $this->base_url() . '/uploads/service/';
          $item->move($destinationPath1, $imageName);
          $image_data = array('product_id' => $id, 'image' => $imageName);
          DB::table('product_images')->insert($image_data);

        endforeach;

      else:

      endif;

      if (isset($request->postalcode)) {
        foreach ($request->postalcode as $value) {
          DB::table('product_area_pincode')->insert([
            "product_id" => $id,
            "pincode" => $value
          ]);
        }
      }

      Session::flash('success', 'Serive insert Successfully....');
      return Redirect('admin/add_product');
    } else {
      Session::flash('error', 'All Star fields are required...');
      return $this->add_product();
    }
  }

  function index()
  {
    $slug = Auth::user()->role_id;
    if ($slug == User::VENDOR) {
      $users = Auth::user()->id;
      $data['product_list'] = DB::table('services')
        ->select('services.*', 'categories.category_name', 'sb.category_name as subcategory_name', 'users.name')
        ->where('services.add_by', $users)
        ->leftJoin('categories', 'services.parent_category', '=', 'categories.id')
        ->leftJoin('categories as sb', 'services.subcategory', '=', 'sb.id')
        ->leftJoin('users', 'services.add_by', '=', 'users.id')
        ->orderBy('services.id', 'desc')
        ->get();
    } else {
      $data['product_list'] = DB::table('services')
        ->select('services.*', 'categories.category_name', 'sb.category_name as subcategory_name', 'users.name')
        ->leftJoin('categories', 'services.parent_category', '=', 'categories.id')
        ->leftJoin('categories as sb', 'services.subcategory', '=', 'sb.id')
        ->leftJoin('users', 'services.add_by', '=', 'users.id')
        ->orderBy('services.id', 'desc')
        ->get();
    }

    //dd($data);

    $data['page_title'] = 'All Products';
    return view('admin.product.index', $data);
  }


  function product_active($id)
  {
    $data = array('status' => '0');
    DB::table('services')->where('id', $id)->update($data);
    Session::flash('success', 'Status Update successfully...');
    return Redirect('admin/product_list');
  }
  function product_deactive($id)
  {
    $data = array('status' => '1');
    DB::table('services')->where('id', $id)->update($data);
    Session::flash('success', 'Status Update successfully...');
    return Redirect('admin/product_list');
  }
  function product_delete($id)
  {

    $edit_data = ServiceModel::find($id);
    $image_path = public_path('uploads/service/' . $edit_data->image);
    if ($edit_data->image) {
      if (file_exists($image_path)) {
        unlink($image_path);
      }
    }
    DB::table('services')->where('id', $id)->delete();
    Session::flash('success', 'Delete successfully...');
    return Redirect('admin/product_list');
  }

  function edit_product($id)
  {


    $data['tax_pay'] = DB::table('tax_pay')->get();
    $type_data = Type::where(['status' => 1])->get();
    $types = array();
    foreach ($type_data as $type) {
      $types[$type->id] = $type->name;
    }

    $data['types'] = $types;

    $data['edit_data'] = ServiceModel::find($id);
    $data['all_product'] = ServiceModel::all();
    $data['gallery'] = DB::table('product_images')->where('product_id', $id)->get();
    $data['items'] = DB::table('product_items')->where('product_id', $id)->get();
    $data['category'] = Category_model::all();
    $data['subattributes'] = DB::table('sub_attributes')->where('status', '1')->get();
    $role_customers = Sentinel::findRoleBySlug('vendor');
    $customers = $role_customers->users()->get();
    $data['vendors'] = $customers;
    ///print_r( $data['subattributes']); die;
    $data['custom_city'] = DB::table('tbl_custom_city')->where('status', '1')->get();

    $data['country'] = DB::table('countries')->get();
    $data['child_category'] = Category_model::where('parent_id', $data['edit_data']->subcategory)->get();

    //dd($data);

    $data['page_title'] = 'Edit Product';
    return view('admin/edit_product', $data);
  }


  function view_product($id)
  {

    $data['edit_data'] = ServiceModel::find($id);
    $data['category'] = Category_model::all();
    $data['page_title'] = 'View Product';
    return view('admin/view_product', $data);
  }

  public function store1(request $request)
  {

    $input = $request->all();
    $images = array();
    if ($files = $request->file('images')) {
      foreach ($files as $file) {
        $name = $file->getClientOriginalName();
        $file->move('image', $name);
        $images[] = $name;
      }
    }
  }


  function update_service(Request $request)
  {

    //return $request->child_category;

    //dd($request->all());

    $id = $request->update_id;
    $base_url = $_SERVER["DOCUMENT_ROOT"];

    $custom_city_id = $request->custom_city;
    $pincode_id = json_encode($request->postalcode);

    if ($request->parent_category && $request->service_name && $request->service_price) {


      $images = $request->file('images');
      if ($request->hasFile('images')) :
        foreach ($images as $item):
          $var = date_create();
          $time = date_format($var, 'YmdHis');
          $imageName = $time . '-' . $item->getClientOriginalName();
          $destinationPath1 = $this->base_url() . '/uploads/service/';
          $item->move($destinationPath1, $imageName);
          $image_data = array('product_id' => $id, 'image' => $imageName);
          DB::table('product_images')->insert($image_data);

        endforeach;

      else:

      endif;

      if ($request->hasFile('image')) {
        $image = $request->file('image');
        $image_name_product = time() . '.' . $image->getClientOriginalExtension();

        $destinationPath = $this->base_url() . '/uploads/service/';
        $image->move($destinationPath, $image_name_product);
        $edit_data = ServiceModel::find($id);
        $image_path = $this->base_url() . '/uploads/service/' . $edit_data->image;
        if ($edit_data->image) {
          if (file_exists($image_path)) {
            unlink($image_path);
          }
        }
      }
      if (@$image_name_product) {

        $data = array(
          'parent_category' => $request->parent_category,
          'subcategory' => $request->subcategory,
          'child_id' => $request->child_category,
          'service_name' => $request->service_name,
          'product_tax' => @$request->product_tax,
          'tax_include' => @$request->tax_include,
          'sku' => @$request->sku,
          'mfg_date' => @$request->mfg_date,
          'expiry_date' => @$request->expiry_date,
          'country_origin' => @$request->country_origin,
          'related_product' => json_encode($request->related_product),
          'meta_title' => $request->meta_title,
          'seller_price' => @$request->whole_seller_price,
          'custom_city_id' => @$custom_city_id,
          'pincode_id' => @$pincode_id,
          'type_id' => $request->type_id,
          'key_feature' => $request->key_feature,
          'packing_type' => $request->packing_type,
          'disclaimer' => $request->disclaimer,
          'meta_keyword' => $request->meta_keyword,
          'meta_description' => $request->meta_description,
          'slug' => str_replace(' ', '-', $request->slug),
          'short_description' => $request->short_description,
          'description' => $request->description,
          'updated_date' => date('Y-m-d H:i:s'),
          'image' => @$image_name_product,
          'add_by' => isset($request->vendors) ? $request->vendors : $users = Sentinel::getUser()->id,
        );
      } else {
        $data = array(
          'parent_category' => $request->parent_category,
          'subcategory' => $request->subcategory,
          'child_id' => $request->child_category,
          'service_name' => $request->service_name,
          'product_tax' => @$request->product_tax,
          'tax_include' => @$request->tax_include,
          'sku' => @$request->sku,
          'mfg_date' => @$request->mfg_date,
          'expiry_date' => @$request->expiry_date,
          'country_origin' => @$request->country_origin,
          'related_product' => json_encode($request->related_product),
          'meta_title' => $request->meta_title,
          'seller_price' => @$request->whole_seller_price,
          'custom_city_id' => @$custom_city_id,
          'pincode_id' => @$pincode_id,
          'type_id' => $request->type_id,
          'key_feature' => $request->key_feature,
          'packing_type' => $request->packing_type,
          'disclaimer' => $request->disclaimer,
          'meta_keyword' => $request->meta_keyword,
          'meta_description' => $request->meta_description,
          'slug' => str_replace(' ', '-', $request->slug),
          'short_description' => $request->short_description,
          'description' => $request->description,
          'updated_date' => date('Y-m-d H:i:s'),
          'add_by' => isset($request->vendors) ? $request->vendors : $users = Sentinel::getUser()->id,
        );
      }


      if ($request->type_id == 10) {
        $data['comdown_start'] = @$request->comdown_start;
        $data['comdown_end'] = @$request->comdown_end;
      }

      //dd($data);

      DB::table('services')->where('id', $id)->update($data);

      $sale_price = ($request->sale_price);
      $service_price = ($request->service_price);
      $unit = ($request->unit);
      $type = $request->type;
      $stock = $request->stock;
      $color = $request->color;
      $minimum_order_qty = $request->minimum_order_qty;
      $size = $request->size;
      $item_id = ($request->item_id);
      ///print_r($item_id); die;
      $unit_value = ($request->unit_value);
      //DB::table('product_items')->where('product_id',$id)->delete();
      if (($item_id)) {

        /*$item_image = $request->file('item_image');
            if ($request->hasFile('item_image')) :
                $image_item_data=array();
                $z=0;
                    foreach ($item_image as $item):
                        $var = date_create();
                        $time = date_format($var, 'YmdHis');
                        $imageName1 = $z.$time . '-' . $item->getClientOriginalName();
                        $destinationPath1 = $this->base_url().'/uploads/items/';
                        $item->move($destinationPath1, $imageName1);
                         $image_item_data[]=$imageName1;
                    $z++;
                    endforeach;
            else:
                    
            endif;*/
        $i = 1;
        foreach (($item_id) as $key => $items) {
          // echo $key;  echo "<br>"; echo $image_item_data[$key]; die;
          $check_items = DB::table('product_items')->where('id', $items)->first();
          //$check_items_array=$check_items->toArray();
          /*echo "<pre>";
             print_r($items); 
             print_r($check_items); 
             if(@$image_item_data[$key]){
              $item_array=array(
                   
                    'item_price'=>$sale_price[$key],
                    'item_mrp_price'=>$service_price[$key],
                    'item_unit'=>$unit[$key],
                    'minimum_order_qty'=>$minimum_order_qty[$key],
                    'type'=>$type[$key],
                    'stock'=>$stock[$key],
                    'color'=>$color[$key],
                    'image'=>$image_item_data[$key],
                    'size'=>$size[$key],
                    'item_unit_value'=>$unit_value[$key],
                    'product_id'=>$id,
                    'short'=>$i,
                  );
             }else{*/
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
          //}

          if ($check_items) {

            DB::table('product_items')->where('product_id', $id)->where('id', @$item_id[$key])->update($item_array);
          } else {

            //DB::table('product_items')->insert($item_array);
          }
          $i++;
        }
        //die;
      }


      //$POSTALCODE = $request->postalcode;

      $avi = DB::table('product_area_pincode')->where('product_id', $id)->get();

      if (!empty($avi)) {
        DB::table('product_area_pincode')->where('product_id', $id)->delete();
        if (isset($request->postalcode)) {
          foreach ($request->postalcode as $value) {
            DB::table('product_area_pincode')->insert([
              "product_id" => $id,
              "pincode" => $value
            ]);
          }
        }
      } else {
        foreach ($request->postalcode as $value) {
          DB::table('product_area_pincode')->insert([
            "product_id" => $id,
            "pincode" => $value
          ]);
        }
      }

      Session::flash('success', 'Product Update Successfully....');
      return Redirect('admin/edit_product/' . $id);
    } else {
      Session::flash('error', 'All Star fields are required...');
      return $this->edit_product($id);
    }
  }

  function uploadvariantimages(Request $request)
  {

    //return $request->variant_id; 

    $images = $request->file('file');
    $variant_id = $request->variant_id;
    if ($request->hasFile('file')) {
      $image = $request->file('file');
      $image_name_product = time() . '.' . $image->getClientOriginalExtension();
      //return $image_name_product;
      $destinationPath = $this->base_url() . '/uploads/items/';
      $image->move($destinationPath, $image_name_product);
      $edit_data = DB::table('product_items')->where('id', $variant_id)->first();
      $image_path = $this->base_url() . '/uploads/items/' . $edit_data->image;
      if ($edit_data->image) {
        if (file_exists($image_path)) {
          unlink($image_path);
        }
      }
    }
    DB::table('product_items')->where('id', $variant_id)->update(['image' => $image_name_product]);
    echo $image_name_product;
  }



  function add_itemsinproduct(Request $request)
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
                <a href="#" class="btn btn-primary btn-sm remove_items">Remove</a>
                </div>
            </div>
            <hr>
     ';
  }
  function deleteProductitems(Request $request)
  {
    DB::table('product_items')->where('id', $request->id)->delete();
  }

  function findProducts(Request $request)
  {

    $term = $request->term;
    $page = $request->page - 1;

    $slugUser = Sentinel::getUser()->roles()->first()->slug;

    $search = DB::table('services');

    if ($slugUser == 'vendor') {

      $user = Sentinel::getUser();
      $search = $search->where('add_by', $user->id);
    }


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

    //  $rows['results']= $search->whereLike([
    //                         "services.description",
    //                         "services.description",
    //                         "services.meta_title",
    //                         "services.slug",
    //                         "services.meta_keyword",
    //                         "product_items.item_unit_value",
    //                         "product_items.item_unit",
    //                         ], $term)
    //                         ->offset($page)->limit($request->limit)
    //                         ->select(DB::raw("
    //                         services.id,product_items.id as item_id,
    //                         concat(services.service_name,' ',product_items.item_unit_value,' ',product_items.item_unit) as text,
    //                         product_items.item_unit_value,
    //                         product_items.item_unit
    //                         "))
    //                         ->leftJoin('product_items', 'services.id', '=', 'product_items.product_id')
    //                         ->get();

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
}
