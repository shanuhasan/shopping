@extends('admin.layout')
@section('content')
<style>
    .card {
        padding: 20px;
    }

   
</style>
<div class="content-wrapper">
    
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><?=@$page_title?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        <li class="breadcrumb-item active"><?=@$page_title?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    
    <section class="content">
        <div class="container-fluid">            
            <div class="card">
                {{Form::open(array('url'=>'admin/settings','method'=>'post','enctype'=>'multipart/form-data','id'=>'settings_form'))}}
                <input type="hidden" name="settings_for" value="site_settings">
                <div class="row">
                    <div class="col-lg-4 col-6">
                        <div class="form-group">
                          <label>Site name</label> <span style="color:red">*</span>
                        <input type="text" name="site_settings[site_name]" required value="{{isset($settings['site_name'])?$settings['site_name']:NULL}}" placeholder="Site Name" class="form-control">
                        </div>
                      </div>
                      <div class="col-lg-4 col-6">
                        <div class="form-group">
                          <label>Site title</label> <span style="color:red">*</span>
                          <input type="text" name="site_settings[site_title]" required value="{{isset($settings['site_title'])?$settings['site_title']:NULL}}" placeholder="Site title" class="form-control">
                        </div>
                      </div>
                      <div class="col-lg-4 col-6">
                        <div class="form-group">
                            <label>Email</label> <span style="color:red">*</span>
                            <input type="email" name="site_settings[email]" required value="{{isset($settings['email'])?$settings['email']:NULL}}" id="email" class="form-control"
                                placeholder="Email">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-lg-4 col-6">
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="tel" name="site_settings[phone]" value="{{isset($settings['phone'])?$settings['phone']:NULL}}" id="phone" class="form-control" placeholder="Phone">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Address1</label> <span style="color:red">*</span>
                            <input type="text" name="site_settings[line_1]" required value="{{isset($settings['line_1'])?$settings['line_1']:NULL}}" placeholder="Address"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Address2</label>
                            <input type="text" name="site_settings[line_2]" value="{{isset($settings['line_2'])?$settings['line_2']:NULL}}" placeholder="Address 2 (optional)"
                                class="form-control">
                        </div>
                    </div>
                    <div class="clearfix"></div><div class="col-md-4">
                        <div class="form-group">
                            <label>Country</label> <span style="color:red">*</span>
                            {{Form::select('site_settings[country]', $countries, isset($settings['country'])?$settings['country']:'',['class'=>'form-control countries','id'=>'countries','required'=>'required'])}}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>State</label>
                            {{Form::select('site_settings[state]', $states, isset($settings['state'])?$settings['state']:'',['class'=>'form-control state','id'=>'states','data-val'=>isset($settings['state'])?$settings['state']:''])}}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>City</label>
                            {{Form::select('site_settings[city]', $cities,isset($settings['city'])?$settings['city']:'',['class'=>'form-control city','id'=>'cities','data-val'=>isset($settings['city'])?$settings['city']:''])}}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Zip Code</label>
                            <input type="text" name="site_settings[zip_code]" value="{{isset($settings['zip_code'])?$settings['zip_code']:NULL}}" placeholder="Zip code (optional)"
                                class="form-control">
                        </div>
                    </div> 
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Latitude</label>
                            <input type="text" name="site_settings[lat]" value="{{isset($settings['lat'])?$settings['lat']:NULL}}" placeholder="Latitude"
                                class="form-control">
                        </div>
                    </div> 
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Longitude</label>
                            <input type="text" name="site_settings[long]" value="{{isset($settings['long'])?$settings['long']:NULL}}" placeholder="Longitude"
                                class="form-control">
                        </div>
                    </div>
                   
                    <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Header Logo</label>
                            <input type="file" name="header_logo" id="header_logo" class="form-control">
                            <div class="headerlogo" style="<?php if(empty($settings) || !empty($settings['header_logo'])){echo '"display: none';}?>"><img id="header_logo_img" src="{{isset($settings['header_logo'])?asset($settings['header_logo']):NULL}}" alt="your image" style="width: 22%; padding: 1%;"></div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="footer_logo" id="footer_logo" class="form-control">
                            <div class="footer_logoimg" style="<?php if(!isset($settings['footer_logo']) || (empty($settings) || !empty($settings['footer_logo']))){echo '"display: none';}?>">
                                <img id="footer_logo_img" src="{{isset($settings['footer_logo'])?asset($settings['footer_logo']):NULL}}" alt="your image" style="width: 22%; padding: 1%;">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Slogan</label>
                            <input type="text" name="slogan" required value="{{isset($settings['slogan'])?$settings['slogan']:NULL}}" placeholder="Site slogon"class="form-control">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Commission (%)</label>
                            <input type="text" name="commission" required value="{{isset($settings['commission'])?$settings['commission']:NULL}}" placeholder="commission"class="form-control">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Gift Wrap Price</label>
                            <input type="number" name="gift_wrap" required value="{{isset($settings['gift_wrap'])?$settings['gift_wrap']:NULL}}" placeholder="gift wrap"class="form-control">
                        </div>
                    </div>
                    
                    
                    
                    </div>
                    <div class="clearfix"></div>
                </div>
                    <div class="row">
                        <h4 style="margin-left:1%">Other Details</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                            <label>Receipt Email</label>
                            <input type="text" name="site_settings[other_email]" value="{{isset($settings['other_email'])?$settings['other_email']:NULL}}" 
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                            <div class="form-group">
                            <label>Receipt Address</label>
                            <input type="text" name="site_settings[other_address]" value="{{isset($settings['other_address'])?$settings['other_address']:NULL}}" 
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                            <div class="form-group">
                            <label>Receipt Phone</label>
                            <input type="text" name="site_settings[other_phone]" value="{{isset($settings['other_phone'])?$settings['other_phone']:NULL}}" 
                                class="form-control">
                        </div>
                    </div>
                    
                     <div class="col-md-4">
                            <div class="form-group">
                            <label>Home Page Display</label>
                           <select multiple name="type_data[]" class="form-control">
                               <?php foreach($types as $type){?>
                               <option <?php if(json_decode($settings['type_id'])){ if(in_array($type->id,json_decode($settings['type_id']))){echo "selected";} } ?> value="<?=$type->id?>"><?=$type->name?></option>
                               <?php } ?>
                           </select>
                              
                        </div>
                    </div><div class="col-md-4">
                            <div class="form-group">
                            <label>Home Page Display Category</label>
                           <select multiple name="cat_data[]" class="form-control">
                               <?php foreach($category as $cat){?>
                               <option <?php if(json_decode($settings['type_id'])){ if(in_array($cat->id,json_decode($settings['category_id']))){echo "selected";} } ?> value="<?=$cat->id?>"><?=$cat->category_name?></option>
                               <?php } ?>
                           </select>
                                
                        </div>
                    </div>
                    </div>
                    <div class="row">
                        <h4 style="margin-left:1%">More Details</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                            <label>CGST (%)</label>
                            <input type="number" name="site_settings[cgst]" value="{{isset($settings['cgst'])?$settings['cgst']:NULL}}" 
                                class="form-control" min='0'>
                        </div>
                    </div>
                    <div class="col-md-4">
                            <div class="form-group">
                            <label>SGST (%)</label>
                            <input type="number" name="site_settings[sgst]" value="{{isset($settings['sgst'])?$settings['sgst']:NULL}}" 
                                class="form-control" min='0'>
                        </div>
                    </div>
                    <div class="col-md-4">
                            <div class="form-group">
                            <label>Coin value on Registration</label>
                            <input type="number" name="site_settings[coinvalue_on_registration]" value="{{isset($settings['coinvalue_on_registration'])?$settings['coinvalue_on_registration']:NULL}}" 
                                class="form-control" min='0'>
                        </div>
                    </div> <div class="col-md-4">
                            <div class="form-group">
                            <label>Coin value on Referral</label>
                            <input type="number" name="site_settings[coinvalue_on_referral]" value="{{isset($settings['coinvalue_on_referral'])?$settings['coinvalue_on_referral']:NULL}}" 
                                class="form-control" min='0'>
                        </div>
                    </div> <div class="col-md-4">
                            <div class="form-group">
                            <label>Coin Rate</label>
                            <input type="number" name="site_settings[coin_rate]" value="{{isset($settings['coin_rate'])?$settings['coin_rate']:NULL}}" 
                                class="form-control" placeholder="ex. 1 Coin = 1Rs." min='0'>
                        </div>
                    </div>
                    <div class="col-md-4">
                            <div class="form-group">
                            <label>Shipping Charge</label>
                            <input type="number" name="site_settings[shipping_charge]" value="{{isset($settings['shipping_charge'])?$settings['shipping_charge']:NULL}}" 
                                class="form-control"  min='0'>
                        </div>
                    </div>
                    
                    
                    </div>
                <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>About</label>
                        <textarea class="form-control" name="site_settings[about]">{{isset($settings['about'])?$settings['about']:NULL}}</textarea>
                      </div>
                    </div>
                  </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                        <label>Meta Title</label>
                        <input type="text" name="site_settings[meta_title]" value="{{isset($settings['meta_title'])?$settings['meta_title']:NULL}}" class="form-control"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                        <label>Meta Keyword</label>
                        <input type="text" name="site_settings[meta_keyword]" value="{{isset($settings['meta_keyword'])?$settings['meta_keyword']:NULL}}" class="form-control"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                        <label>Meta Description</label>
                        <input type="text" name="site_settings[meta_description]" value="{{isset($settings['meta_description'])?$settings['meta_description']:NULL}}" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-sm btn-info">Save</button>
                    </div>
                </div>
                {{Form::close()}}
            </div>
        </div>
    </section>
    
</div>


@stop

@push('js')
<script src="{{asset('assets/bacend/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="<?php echo asset('/assets/settings.js');?>"></script>
@endpush