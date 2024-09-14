 @extends('admin.layout')
@section('content')
  <style type="text/css">
    .deletegallery_image{position: absolute;
      cursor: pointer;color: red}
      .gallery{border:1px solid grey; border-radius: 10px; 
        padding:1%; margin-top:1%;}
  </style>
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
      
      
      <form action="{{url('admin/update_service')}}" method="post" enctype="multipart/form-data" id="form_data">
          
          @csrf
      
         <input type="hidden" name="update_id" value="<?=$edit_data->id?>" >
        <div class="card">
        <div class="row" style="padding:2%">
          <div class="col-lg-3 col-6">
    
              
            <label>Vendors</label> <span style="color:red">*</span>
           <select name="vendors" class="form-control">
            <option value="">Select Vendor</option>
            <?php foreach ($vendors as $key => $vendor_value) {
            ?>
              <option <?php if($edit_data->add_by==$vendor_value->id){echo "selected";}?> value="<?=$vendor_value->id?>"><?=$vendor_value->first_name?> <?=$vendor_value->last_name?></option>
            <?php  }?>
            </select>
         </div> <div class="col-lg-3 col-6">
            <label>Category</label> <span style="color:red">*</span>
           <select name="parent_category" class="form-control parent_category">
            <option value="">Select Category</option>
            
            <?php foreach ($category as $key => $value) {
            if($value->parent_id=='0' || $value->parent_id==''){ ?>
              <option <?php if($edit_data->parent_category==$value->id){echo "selected";}?> value="<?=$value->id?>"><?=$value->category_name?></option>
            <?php } }?>
            
            </select>
         </div>
         <div class="col-lg-3 col-6">
            <label>Subcategory</label> <span style="color:red">*</span>
           <select name="subcategory" class="form-control" id="sub_category">
            <option value="">Select SubCategory</option>
            <?php foreach ($category as $key => $value) {
            if($value->parent_id){ ?>
              <option <?php if($edit_data->subcategory==$value->id){echo "selected";}?> value="<?=$value->id?>"><?=$value->category_name?></option>
            <?php } }?>
            </select>
         </div>
         
         <div class="col-lg-3 col-6">
            <label>Child Category   {{ $edit_data->child_id }} </label> <span style="color:red"></span>
           <select name="child_category" class="form-control" id="show_child_category">
               <?php foreach ($child_category as $key => $child_cat) { ?>
                
                    <option value="{{ @$child_cat->id }}" {{$edit_data->child_id==@$child_cat->id?'selected':''}}>{{ @$child_cat->category_name }}</option>
              
              <?php  }?>
              
            </select>
         </div>
         
        <div class="col-lg-12 col-12"><br>
        <div class="row">
            
            
            <div class="col-lg-6 col-6">
                 <label>Product Tax</label> <span style="color:red">*</span>
                 <select name="product_tax" class="form-control">
                      <option value="0"> Tax Free </option>
                      @foreach($tax_pay as $taxPay)
                      <option value="{{$taxPay->id}}" {{@$edit_data->product_tax==$taxPay->id?'selected':''}}> {{$taxPay->title}} </option>
                      @endforeach
                 </select>
            </div>
            
            <div class="col-lg-6 col-6">
                 <label>Tax include / excluded</label> <span style="color:red">*</span>
                 <select name="tax_include" class="form-control">
                      <option value="1" {{@$edit_data->tax_include==1?'selected':''}}> include </option>
                      <option value="0" {{@$edit_data->tax_include==0?'selected':''}}> excluded </option>
                 </select>
            </div>
            
             <div class="col-lg-4 col-6">
                 <label>SKU</label> <span style="color:red">*</span>
                 <input type="text" name="sku" class="form-control" value="{{@$edit_data->sku}}">
            </div>
           <div class="col-lg-4 col-6">
           <label>Product Name</label> <span style="color:red">*</span>
            <input type="text" name="service_name" value="<?=@$edit_data->service_name?>" placeholder="Product Name" class="form-control">
            </div>
           <!--<label>Whole Seller Price</label> <span style="color:red">*</span>-->
            <input type="hidden" name="whole_seller_price" value="<?=$edit_data->seller_price?>"  class="form-control">
             
            <div class="col-lg-4 col-6">
            <div class="form-group">
              <label>Type</label>
              {{Form::select('type_id', $types, $edit_data->type_id,['class'=>'form-control','id'=>'type_id'])}}
            </div>
          </div>
        </div>
        </div>
        
        <div class="col-lg-12" id="comdown_show" style="display:{{$edit_data->type_id==10?'block':'none'}}">
            
           <div class="row" >
             <div class="col-lg-6 col-6">
                <label>Com down Start</label> <span style="color:red">*</span>
                 <input type="datetime-local" name="comdown_start" class="form-control" value="{{@$edit_data->comdown_start}}">
            </div>
            <div class="col-lg-6 col-6">
                 <label>Com down End</label> <span style="color:red">*</span>
                 <input type="datetime-local" name="comdown_end" class="form-control" value="{{@$edit_data->comdown_end}}">
            </div>
          </div><br>
       </div> 
       
       <div class="col-lg-12 col-12">
        <div class="row" >
         <div class="col-lg-6 col-6">
             <label>MFG Date</label> <span style="color:red">*</span>
             <input type="date" name="mfg_date" class="form-control" value="{{@$edit_data->mfg_date}}">
          </div>
        
        <div class="col-lg-6 col-6">
             <label>Expiry Date</label> <span style="color:red">*</span>
             <!--<input type="text" name="expiry_date" class="form-control" id="expiry_date">-->
              <input type="date" name="expiry_date" class="form-control" value="{{@$edit_data->expiry_date}}">
        </div>
        </div><br>
    </div>
          
            <!--<div class="col-lg-4 col-6">
           <label>Stock *</label> -->
            <input type="hidden" name="stock" value="<?=@$edit_data->stock?>" class="form-control"><!--</div>-->
            <div class="col-md-12">
            <div class="row">
                 <div class="col-lg-2 col-6">
           </div>
                <div class="col-lg-2 col-6">
           </div>
             <div class="col-lg-2 col-6">
          
           </div>
            <div class="col-lg-2 col-6">
           </div> <div class="col-lg-2 col-6">
           </div>
            <div class="col-lg-2 col-6">
                <br>
                <a href="#" class="btn btn-primary btn-sm add_more_items" data-id='<?=$edit_data->id?>'>Add More</a>
                </div>
            </div>
            </div>
            <div class="col-md-12 append_html">
               
                <?php
                if(@$items){
                foreach($items as $key=> $items_value){ ?>
                <div class="row">
                    <div class="col-lg-2 col-6">
           <label>Product type</label> <span style="color:red">*</span>
            <select class="form-control" name="type[]">
             <option <?php if($items_value->type=='normal'){echo "selected";}?>  value="normal">Normal</option>
           <!--  <option <?php if($items_value->type=='bulk'){echo "selected";}?>  value="bulk">Bulk</option>-->
             
           </select>
            
            </div>
                     <div class="col-lg-2 col-6">
           <label>Mrp Price</label> <span style="color:red">*</span>
            <input type="hidden" name="item_id[]" value="<?=@$items_value->id?>">
            <input type="text" name="service_price[]" value="<?=@$items_value->item_mrp_price?>" placeholder="Mrp Price" class="form-control"></div>
                <div class="col-lg-2 col-6">
           <label>Sale Price</label> <span style="color:red">*</span>
            <input type="text" name="sale_price[]" value="<?=$items_value->item_price?>"  class="form-control"></div>
            <div class="col-lg-2 col-6">
           <label>Stock</label> <span style="color:red">*</span>
            <input type="number" name="stock[]" placeholder="Stock" value="<?=$items_value->stock?>" class="form-control"></div>
             <div class="col-lg-2 col-6">
           <label>Color</label> <span style="color:red">*</span>
          <select class="form-control" name="color[]">
              <option value="">Select Color</option>
              <?php foreach($subattributes as $colors){ 
                if($colors->attributes_id=='1'){
              ?>
              <option <?php if($items_value->color==$colors->slug){echo "selected";}?> value="<?=$colors->slug?>"><?=$colors->sub_attributes_name?></option>
              <?php } } ?>
          </select>
          </div>
          <div class="col-lg-2 col-6">
           <label>Size</label> <span style="color:red">*</span>
          <select class="form-control" name="size[]">
              <option value="">Select Size</option>
              <?php foreach($subattributes as $size){ 
                if($size->attributes_id=='2'){
              ?>
              <option <?php if($items_value->size==$size->slug){echo "selected";}?> value="<?=$size->slug?>"><?=$size->sub_attributes_name?></option>
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
              <option <?php if($items_value->item_unit==$unit->slug){echo "selected";}?> value="<?=$unit->slug?>"><?=$unit->sub_attributes_name?></option>
              <?php } } ?>
           </select>
           </div>
            <div class="col-lg-2 col-6">
           <label>Unit Value</label> <span style="color:red">*</span>
            <input type="number" name="unit_value[]" value="<?=$items_value->item_unit_value?>"  class="form-control"></div>
             <div class="col-lg-2 col-6">
           <label>Minimum Order qty</label> <span style="color:red">*</span>
            <input type="number" name="minimum_order_qty[]" value="<?=$items_value->minimum_order_qty?>" min='1'  class="form-control"></div>
            <div class="col-lg-2 col-6">
           <label>Image</label> <span style="color:red">*</span>
            <input type="file" name="images"   id="variant_image" data-id="<?=@$items_value->id?>"   class="form-control variantimageupload">
            <span class="viewimage<?=@$items_value->id?>">
            <?php if($items_value->image){ ?>
            <img src="<?php echo asset('/');?>uploads/items/<?=$items_value->image?>" style="width:100px">
            <?php } ?>
            </span>
            </div>
            <div class="col-lg-2 col-6">
                <br>
                <?php if($key!='0'){ ?>
                <a href="#" data-id='<?=@$items_value->id?>' class="btn btn-primary btn-sm remove_items">Remove</a>
                <?php } ?>
                </div>
            </div>
            <hr>
                <?php }  }?>
            </div>
             
            
            
            <div class="col-md-12 append_html"> </div>
            
            
                <div class="col-lg-4 col-6">
                   <label> Country origin </label> 
                   <select name="country_origin" class="form-control">
                       <option value=""> --select country--</option>
                       @if(!empty($country))
                       @foreach($country as $c)
                            <option value="{{$c->id}}" {{@$edit_data->country_origin==$c->id ? 'selected':'' }}> {{ $c->name }} </option>
                       @endforeach
                       @endif
                   </select>
                   <br>
                </div>
          
            <!--// product belongs to city or zipcode-->
               
                <div class="col-lg-4 col-6">
                   <label> Select City  </label> 
                   
                   <select name="custom_city" class="form-control" id="select_custome_city">
                       <option value=""> --select city--</option>
                       @if(!empty($custom_city))
                       @foreach($custom_city as $city)
                            <option value="{{$city->id}}" {{ $edit_data->custom_city_id==$city->id ? 'selected':'' }} > {{ $city->city }} </option>
                       @endforeach
                       @endif
                   </select>
                   <br>
                </div>
                  
                <div class="col-lg-4 col-6">
                   <label> Delivery Area </label> 
                   <select name="postalcode[]" class="form-control postal_code" id="postal_code" multiple="multiple">
                       <option value=""> </option>
                       @if(json_decode($edit_data->pincode_id))
                       @foreach(json_decode($edit_data->pincode_id) as $pin)
                            <option value="{{$pin}}" selected> {{ $pin }}</option>
                       @endforeach
                        @endif
                   </select>
                   <br>
                </div>
                
             <hr> 
            
            
            
            <div class="col-lg-4 col-6">
           <label>Meta Title</label> 
            <input type="text" name="meta_title" value="<?=@$edit_data->meta_title?>" class="form-control"></div>
             <div class="col-lg-4 col-6">
           <label>Meta Keyword</label> 
            <input type="text" name="meta_keyword" value="<?=@$edit_data->meta_keyword?>" class="form-control"></div>
             <div class="col-lg-4 col-6">
           <label>Meta Description</label> 
            <input type="text" name="meta_description" value="<?=@$edit_data->meta_description?>" class="form-control"></div>
            <div class="col-lg-12 col-6">
           <label>Slug</label> 
            <input type="text" name="slug" value="<?=@$edit_data->slug?>" class="form-control"></div>
            
          <div class="col-lg-12">
             <label>Short Description</label>
            <textarea class="form-control " name="short_description"><?=@$edit_data->short_description?></textarea>
          </div>
          <div class="col-lg-12">
             <label>Description</label>
            <textarea class="form-control textarea" name="description"><?=@$edit_data->description?></textarea>
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
            <?php if($edit_data->image){ ?>
                      <img src="<?php echo asset('/');?>uploads/service/<?=$edit_data->image?>" width="60px">
                    <?php } ?>
            <div class="viewimg" style="display: none"><img id="v_img" src="#" alt="your image" style="width: 22%; padding: 1%;"></div>
          </div>
          <div class="col-lg-12">
             <label>Gallery</label>
            <input type="file" name="images[]" multiple id="view_img" class="form-control">
            <div class="row">

            <?php if($gallery){
              foreach ($gallery as $key => $value) {   
             ?>
             <div class="col-md-1 gallery">
                      <img src="<?php echo asset('/');?>uploads/service/<?=$value->image?>" width="100%"><span class="deletegallery_image" data-id="<?=$value->id?>">X</span>
                       </div>
                    <?php } }?>

                  </div>
            <div class="viewimg" style="display: none"><img id="v_img" src="#" alt="your image" style="width: 22%; padding: 1%;"></div>
          </div>
          <div class="col-lg-12 col-6">
           <label>Related Product</label> 
            <select class="form-control" name="related_product[]" multiple>
            <option>Select Related Product</option>  
            <?php foreach($all_product as $val){ ?>
            <option <?php if(json_decode($edit_data->related_product)){
            if(in_array($val->id,json_decode($edit_data->related_product))){echo "selected";}
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
            
             
           </select>
            
            </div>
                 <div class="col-lg-2 col-6">
           <label>Mrp Price</label> <span style="color:red">*</span>
           <input type="hidden" name="item_id[]">
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
              <option value="">Select Size</option>
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
            <input type="number" name="unit_value[]"   class="form-control" min='1'></div>
            <div class="col-lg-2 col-6">
           <label>Minimum Order qty</label> <span style="color:red">*</span>
            <input type="number" name="minimum_order_qty[]" value="1"  class="form-control" min='1'></div>
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