  @extends('admin.layout')
@section('content')
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><?=@$page_title?></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="">Home</a></li>
              <li class="breadcrumb-item"><a href="{{url('admin/product_list')}}">Product List</a></li>
              <li class="breadcrumb-item active"><?=@$page_title?></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
      {{Form::open(array('url'=>'admin/insert_service','method'=>'post','enctype'=>'multipart/form-data'))}}
         
        <div class="card">
        <div class="row" style="padding:2%">
            <div class="col-lg-3 col-6">
            <label>Vendors</label> <span style="color:red">*</span>
           <select name="vendors" class="form-control">
            <option value="">Select Vendor</option>
            <?php foreach ($vendors as $key => $vendor_value) {
            ?>
              <option value="<?=$vendor_value->id?>"><?=$vendor_value->first_name?> <?=$vendor_value->last_name?></option>
            <?php  }?>
            </select>
         </div>
         
          <div class="col-lg-3 col-6">
            <label>Category</label> <span style="color:red">*</span>
            <select name="parent_category" class="form-control parent_category">
            <option value="">Select Category</option>
            <?php foreach ($category as $key => $value) {
            if($value->parent_id=='0' || $value->parent_id==''){ ?>
            	<option value="<?=$value->id?>"><?=$value->category_name?></option>
            <?php } }?>
            </select>
         </div>
         
         <div class="col-lg-3 col-6">
            <label>Subcategory</label> <span style="color:red"></span>
            <select name="subcategory" class="form-control" id="sub_category">
            <option value="">Select SubCategory</option>
           
            </select>
         </div>
         
         <div class="col-lg-3 col-6">
            <label>Child Category</label> <span style="color:red"></span>
           <select name="childcategory" class="form-control" id="show_child_category">
            <option value="">select child category</option>
           
            </select>
         </div>
         
         <div class="col-lg-12 col-12"><br>
            <div class="row"> 
            
                <div class="col-lg-4 col-6">
                     <label>SKU</label> <span style="color:red">*</span>
                     <input type="text" name="sku" class="form-control">
                </div>
                
               <div class="col-lg-4 col-6">
                    <label>Product Name</label> <span style="color:red">*</span>
                    <input type="text" name="service_name" value="<?=@$_POST['service_name']?>" placeholder="Product Name" class="form-control" required>
                </div>
                    <!--<label>Whole Seller Price</label> <span style="color:red">*</span>-->
                    <input type="hidden" name="whole_seller_price" value="<?=@$_POST['whole_seller_price']?>"  class="form-control">
               
                <div class="col-md-4">
                <div class="form-group">
                  <label>Type</label>
                   {{Form::select('type_id', $types, '',['class'=>'form-control','id'=>'type_id'])}}
                </div>
              </div>
              
              <div class="col-lg-6 col-6">
                     <label>Product Tax</label> <span style="color:red">*</span>
                     <select name="product_tax" class="form-control">
                          <option value="0"> Tax Free </option>
                          @foreach($tax_pay as $taxPay)
                          <option value="{{$taxPay->id}}"> {{$taxPay->title}} </option>
                          @endforeach
                     </select>
                </div>
                
                <div class="col-lg-6 col-6">
                     <label>Tax include / excluded</label> <span style="color:red">*</span>
                     <select name="tax_include" class="form-control">
                          <option value="1"> include </option>
                          <option value="0"> excluded </option>
                     </select>
                </div>
              
             </div>
        </div>
        
        <div class="col-lg-12 col-12"><br>
        <div class="row">
            
            <div class="col-sm-3">
                <label>Length</lebel>
                <input type="number" name="length"  class="form-control">
            </div>
            <div class="col-sm-3">
                <label>Breadth</lebel>
                <input type="number" name="breadth"  class="form-control">
            </div>
            <div class="col-sm-2">
                <label>Height</lebel>
                <input type="number" name="height"  class="form-control">
            </div>
            <div class="col-sm-2">
                <label>Weight</lebel>
                <input type="number" name="weight"  class="form-control">
            </div>
            
            <div class="col-sm-2">
                <label>Gift Wrap </lebel>
                <input type="checkbox" name="gift_wrap"  class="form-control">
            </div>
            
        </div><br>
        </div>
        
          
      <div class="col-lg-12" id="comdown_show" style="display:nonen">
           <div class="row" >
               
             <div class="col-lg-6 col-6">
                <label>Com down Start</label> <span style="color:red">*</span>
                 <input type="datetime-local" name="comdown_start" class="form-control">
            </div>
            <div class="col-lg-6 col-6">
                 <label>Com down End</label> <span style="color:red">*</span>
                 <input type="datetime-local" name="comdown_end" class="form-control">
            </div>
          </div><br>
       </div> 
          
    <div class="col-lg-12 col-12">
        <div class="row" >
         <div class="col-lg-6 col-6">
             <label>MFG Date</label> <span style="color:red">*</span>
             <input type="date" name="mfg_date" class="form-control">
          </div>
        
        <div class="col-lg-6 col-6">
             <label>Expiry Date</label> <span style="color:red">*</span>
             <!--<input type="text" name="expiry_date" class="form-control" id="expiry_date">-->
              <input type="date" name="expiry_date" class="form-control">
        </div>
        </div><br>
    </div>
    
           <!-- <div class="col-lg-4 col-6">
           <label>Stock *</label> -->
            <input type="hidden" name="stock" value="<?=@$_POST['stock']?>" class="form-control"><!--</div>-->
            <div class="col-md-12">
            <div class="row">
                <div class="col-lg-2 col-6">
                <label>Product type</label> <span style="color:red">*</span>
                <select class="form-control" name="type[]">
                <option  value="normal">Normal</option>
                <!-- <option  value="bulk">Bulk</option>-->
                </select>
            
            </div>
                  <div class="col-lg-2 col-6">
           <label>Mrp Price</label> <span style="color:red">*</span>
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
               <?php foreach($subattributes as $colors){ 
                if($colors->attributes_id=='1'){
              ?>
              <option value="<?=$colors->slug?>"><?=$colors->sub_attributes_name?></option>
              <?php } } ?>
          </select>
          </div>
          <div class="col-lg-2 col-6">
           <label>Size</label> <span style="color:red">*</span>
          <select class="form-control" name="size[]">
              <option value="">Select size</option>
              <?php foreach($subattributes as $size){ 
                if($size->attributes_id=='2'){
              ?>
              <option value="<?=$size->slug?>"><?=$size->sub_attributes_name?></option>
              <?php } } ?>
          </select>
          </div>
             <div class="col-lg-2 col-6">
           <label>Choose Unit</label>
           <select class="form-control" name="unit[]">
               <option value="">Select unit</option>
             <?php foreach($subattributes as $unit){ 
                if($unit->attributes_id=='3'){
              ?>
              <option value="<?=$unit->slug?>"><?=$unit->sub_attributes_name?></option>
              <?php } } ?>
           </select>
           </div>
            <div class="col-lg-2 col-6">
           <label>Unit Value</label> <span style="color:red">*</span>
            <input type="number" name="unit_value[]"   class="form-control"></div>
             <div class="col-lg-2 col-6">
           <label>Image</label> <span style="color:red">*</span>
            <input type="file" name="item_image[]"   class="form-control" required></div>
            <div class="col-lg-2 col-6">
                <br>
                <a href="#" class="btn btn-primary btn-sm add_more">Add More</a>
                </div>
            </div>
            <hr>
            </div>
            
            <div class="col-md-12 append_html"> </div>
            
            <!--// product belongs to city or zipcode-->
               
               
               <div class="col-lg-6 col-6">
                   <label> Country origin </label> 
                   <select name="country_origin" class="form-control" id="select_custome_city_b">
                       <option value=""> --select country--</option>
                       @if(!empty($country))
                       @foreach($country as $c)
                            <option value="{{$c->id}}"> {{ $c->name }} </option>
                       @endforeach
                       @endif
                   </select>
                   <br>
                </div>
                
                <div class="col-lg-6 col-6">
                   <label> Select City </label> 
                   <select name="custom_city" class="form-control" id="select_custome_city">
                       <option value=""> --select city--</option>
                       @if(!empty($custom_city))
                       @foreach($custom_city as $city)
                            <option value="{{$city->id}}"> {{ $city->city }} </option>
                       @endforeach
                       @endif
                   </select>
                   <br>
                </div>
                
                <div class="col-lg-6 col-6">
                   <label> Delivery Area </label> 
                   <select name="postalcode[]" class="form-control postal_code" id="postal_code" multiple="multiple">
                       <option value=""> </option>
                   </select>
                   <br>
                </div>
             <hr> 
        
            
           <div class="col-lg-6 col-6">
           <label>Meta Title</label> 
            <input type="text" name="meta_title" value="<?=@$_POST['meta_title']?>" class="form-control"></div>
             <div class="col-lg-6 col-6">
           <label>Meta Keyword</label> 
            <input type="text" name="meta_keyword" value="<?=@$_POST['meta_keyword']?>" class="form-control"></div>
             <div class="col-lg-12 col-6">
           <label>Meta Description</label> 
            <input type="text" name="meta_description" value="<?=@$_POST['meta_description']?>" class="form-control"></div>
            <div class="col-lg-12 col-6">
           <label>Slug</label> 
            <input type="text" name="slug" value="<?=@$_POST['slug']?>" class="form-control"></div>
          <div class="col-lg-12">
             <label>Short Description</label>
            <textarea class="form-control" name="short_description"><?=@$_POST['short_description']?></textarea>
          </div>
          <div class="col-lg-12">
             <label>Description</label>
            <textarea class="form-control textarea" name="description"><?=@$_POST['description']?></textarea>
          </div>
          <div class="col-lg-12">
             <label>Key feature</label>
            <textarea class="form-control textarea" name="key_feature"><?=@$edit_data->key_feature?></textarea>
          </div>
           <div class="col-lg-12">
             <label>Packing type</label>
            <textarea class="form-control textarea" name="packing_type"><?=@$edit_data->packing_type?></textarea>
          </div>
          <div class="col-lg-12">
             <label>Disclaimer</label>
            <textarea class="form-control textarea" name="disclaimer"><?=@$edit_data->disclaimer?></textarea>
          </div>
          <div class="col-lg-12">
             <label>Product Image</label>
            <input type="file" name="image" id="view_img" class="form-control">
            <div class="viewimg" style="display: none"><img id="v_img" src="#" alt="your image" style="width: 22%; padding: 1%;"></div>
          </div>
          <div class="col-lg-12">
             <label>Gallery</label>
            <input type="file" name="images[]" multiple id="view_img" class="form-control">
            <div class="viewimg" style="display: none"><img id="v_img" src="#" alt="your image" style="width: 22%; padding: 1%;"></div>
          </div>
           <div class="col-lg-12 col-6">
           <label>Related Product</label> 
            <select class="form-control" name="related_product[]" multiple>
            <option>Select Related Product</option>  
            <?php foreach($all_product as $val){ ?>
            <option <?php if(@$_POST['related_product']){
            if(in_array($val->id,@$_POST['related_product'])){echo "selected";}
            }?> value="<?=$val->id?>"><?=$val->service_name?></option>
            <?php } ?>
            </select>
            </div>
          <div class="col-lg-12" style="margin-top: 2%">
            <input type="submit" value="submit" class="btn btn-primary" style="float: right;">
          </div>
          <!-- ./col -->
        </div>
        </div>
       </form>
       
       <div class="col-md-12 get_html" style="display:none">
            <div class="row">
            <div class="col-lg-2 col-6">
                <label>Product type</label> <span style="color:red">*</span>
                <select class="form-control" name="type[]">
                <option  value="normal">Normal</option>
                <!--<option  value="bulk">Bulk</option>-->
           </select>
            
            </div>
                 <div class="col-lg-2 col-6">
           <label>Mrp Price</label> <span style="color:red">*</span>
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
               <?php foreach($subattributes as $colors){ 
                if($colors->attributes_id=='1'){
              ?>
              <option value="<?=$colors->slug?>"><?=$colors->sub_attributes_name?></option>
              <?php } } ?>
          </select>
          </div>
          <div class="col-lg-2 col-6">
           <label>Size</label> <span style="color:red">*</span>
          <select class="form-control" name="size[]">
              <option value="">Select size</option>
              <?php foreach($subattributes as $size){ 
                if($size->attributes_id=='2'){
              ?>
              <option value="<?=$size->slug?>"><?=$size->sub_attributes_name?></option>
              <?php } } ?>
          </select>
          </div>
             <div class="col-lg-2 col-6">
           <label>Choose Unit</label>
           <select class="form-control" name="unit[]">
               <option value="">Select Unit</option>
             <?php foreach($subattributes as $unit){ 
                if($unit->attributes_id=='3'){
              ?>
              <option value="<?=$unit->slug?>"><?=$unit->sub_attributes_name?></option>
              <?php } } ?>
           </select>
           </div>
            <div class="col-lg-2 col-6">
           <label>Unit Value</label> <span style="color:red">*</span>
            <input type="number" name="unit_value[]"   class="form-control"></div>
             <div class="col-lg-2 col-6">
           <label>Image</label> <span style="color:red">*</span>
            <input type="file" name="item_image[]"   class="form-control"></div>
            <div class="col-lg-2 col-6">
                <br>
                <a href="#" class="btn btn-primary btn-sm remove_items">Remove</a>
                </div>
            </div>
            <hr>
            </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 
 @stop
 
