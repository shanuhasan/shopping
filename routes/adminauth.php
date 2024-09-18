<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\OfferController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\BannerController;
use App\Http\Controllers\admin\CouponController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UploadImageController;

Route::group(['middleware' => ['admin'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/report', [DashboardController::class, 'report'])->name('report');

    //  Banners
    Route::get('/banners', [BannerController::class, 'index'])->name('banner');
    Route::get('/banners/status/{id}', [BannerController::class, 'updateStatus'])->name('banner.status');
    Route::get('/banners/create', [BannerController::class, 'create'])->name('banner.create');
    Route::post('/banners/store', [BannerController::class, 'store'])->name('banner.store');
    Route::get('/banners/edit/{id}', [BannerController::class, 'edit'])->name('banner.edit');
    Route::post('/banners/update', [BannerController::class, 'update'])->name('banner.update');
    Route::get('/banners/view/{id}', [BannerController::class, 'show'])->name('banner.show');
    Route::get('/banners/delete/{id}', [BannerController::class, 'destroy'])->name('banner.destroy');

    // Category
    Route::get('/category', [CategoryController::class, 'index'])->name('category');
    Route::get('/category/create', [CategoryController::class, 'create'])->name('category.create');
    Route::post('/category/store', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/category/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
    Route::post('/category/update', [CategoryController::class, 'update'])->name('category.update');
    Route::get('/category/delete/{id}', [CategoryController::class, 'delete'])->name('category.delete');
    Route::get('/category/deactive/{id}', [CategoryController::class, 'deactive'])->name('category.deactive');
    Route::get('/category/active/{id}', [CategoryController::class, 'active'])->name('category.active');

    // Sub Category
    Route::get('/sub-category', [CategoryController::class, 'subCategory'])->name('subcategory');
    Route::get('/sub-category/create', [CategoryController::class, 'createSubCategory'])->name('subcategory.create');
    Route::post('/sub-category/store', [CategoryController::class, 'storeSubCategory'])->name('subcategory.store');
    Route::get('/sub-category/edit/{id}', [CategoryController::class, 'editSubCategory'])->name('subcategory.edit');
    Route::post('/sub-category/update', [CategoryController::class, 'updateSubCategory'])->name('subcategory.update');
    Route::get('/sub-category/delete/{id}', [CategoryController::class, 'deleteSubCategory'])->name('subcategory.delete');
    Route::get('/sub-category/deactive/{id}', [CategoryController::class, 'deactive'])->name('subcategory.deactive');
    Route::get('/sub-category/active/{id}', [CategoryController::class, 'active'])->name('subcategory.active');

    // Child Category
    Route::get('/child-category', [CategoryController::class, 'childCategory'])->name('childcategory');
    Route::get('/child-category/create', [CategoryController::class, 'createChildCategory'])->name('childcategory.create');
    Route::post('/child-category/store', [CategoryController::class, 'storeChildCategory'])->name('childcategory.store');
    Route::get('/child-category/edit/{id}', [CategoryController::class, 'editChildCategory'])->name('childcategory.edit');
    Route::post('/child-category/update', [CategoryController::class, 'updateChildCategory'])->name('childcategory.update');
    Route::get('/child-category/delete/{id}', [CategoryController::class, 'deleteChildCategory'])->name('childcategory.delete');
    Route::get('/child-category/deactive/{id}', [CategoryController::class, 'deactive'])->name('childcategory.deactive');
    Route::get('/child-category/active/{id}', [CategoryController::class, 'active'])->name('childcategory.active');
    Route::get('/get_subcategory_by_ajax', [CategoryController::class, 'get_subcategory_by_ajax'])->name('subcategory.get_subcategory_by_ajax');

    //Products
    Route::get('/product', [ProductController::class, 'index'])->name('product');
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
    Route::get('/product/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
    Route::post('/product/update', [ProductController::class, 'update'])->name('product.update');
    Route::get('/product/view/{id}', [ProductController::class, 'view'])->name('product.view');
    Route::get('/product/delete/{id}', [ProductController::class, 'delete'])->name('product.delete');
    Route::get('/product/active/{id}', [ProductController::class, 'active'])->name('product.active');
    Route::get('/product/deactive/{id}', [ProductController::class, 'deactive'])->name('product.deactive');
    Route::get('/product/add_itemsinproduct', [ProductController::class, 'add_itemsinproduct'])->name('product.add_itemsinproduct');
    Route::get('/product/deleteProductitems', [ProductController::class, 'deleteProductitems'])->name('product.deleteProductitems');
    Route::post('/product/uploadvariantimages', [ProductController::class, 'uploadvariantimages'])->name('product.uploadvariantimages');
    Route::get('/product/remove_product_images', [ProductController::class, 'remove_product_images'])->name('product.remove_product_images');
    Route::get('/product/get_subcategory', [ProductController::class, 'get_subcategory'])->name('product.get_subcategory');
    Route::get('/product/get_service_bysubcategory', [ProductController::class, 'get_service_bysubcategory'])->name('product.get_service_bysubcategory');
    Route::get('/product/get_child_category_by_ajax', [ProductController::class, 'get_child_category_by_ajax'])->name('product.get_child_category_by_ajax');
    Route::get('/products/findProducts', [ProductController::class, 'findProducts'])->name('product.findProducts');
    Route::get('/products/getProductDeatails', [ProductController::class, 'getProductDeatails'])->name('product.getProductDeatails');
    Route::get('/attribute', [ProductController::class, 'attribute'])->name('attribute');
    Route::get('/attribute_varition_configer/{id}/{varition?}', [ProductController::class, 'attribute_varition_configer'])->name('attribute_varition_configer');
    Route::post('/add_varition', [ProductController::class, 'add_varition'])->name('add_varition');

    Route::get('/complaint', [ProductController::class, 'complaint'])->name('complaint');
    Route::get('/review', [ProductController::class, 'review'])->name('review');
    Route::get('/review/{id}', [ProductController::class, 'reviewDetail'])->name('reviewDetail');

    Route::get('/admin/review', "admin\Vendor_Controller@review_list");
    Route::get('/admin/review/{id}', "admin\Vendor_Controller@review_list_detail");

    ///offer product
    Route::get('/offers', [OfferController::class, 'index'])->name('offers');
    Route::get('/offers/status/{id}', [OfferController::class, 'updateStatus'])->name('offers.status');
    Route::get('/offers/create', [OfferController::class, 'create'])->name('offers.create');
    Route::post('/offers/store', [OfferController::class, 'store'])->name('offers.store');
    Route::get('/offers/edit/{id}', [OfferController::class, 'edit'])->name('offers.edit');
    Route::post('/offers/update', [OfferController::class, 'update'])->name('offers.update');
    Route::get('/offers/view/{id}', [OfferController::class, 'show'])->name('offers.show');
    Route::get('/offers/delete/{id}', [OfferController::class, 'destroy'])->name('offers.destroy');

    ///coupons
    Route::get('/coupons', [CouponController::class, 'index'])->name('coupons');
    Route::get('/coupons/status/{id}', [CouponController::class, 'updateStatus'])->name('coupons.status');
    Route::get('/coupons/create', [CouponController::class, 'create'])->name('coupons.create');
    Route::post('/coupons/store', [CouponController::class, 'store'])->name('coupons.store');
    Route::get('/coupons/edit/{id}', [CouponController::class, 'edit'])->name('coupons.edit');
    Route::post('/coupons/update', [CouponController::class, 'update'])->name('coupons.update');
    Route::get('/coupons/view/{id}', [CouponController::class, 'show'])->name('coupons.show');
    Route::get('/coupons/delete/{id}', [CouponController::class, 'destroy'])->name('coupons.destroy');


    //order
    Route::get('/order', [OrderController::class, 'index'])->name('order.index');
    Route::get('/orders', [OrderController::class, 'orders'])->name('order.orders');
    Route::post('/filter_orders', [OrderController::class, 'filter_orders'])->name('order.filter_orders');
    Route::get('/print_invoice_pdf/{id}', [OrderController::class, 'print_invoice_pdf'])->name('order.print_invoice_pdf');
    Route::get('/print_invoice_multiple/{id}', [OrderController::class, 'print_invoice_multiple'])->name('order.print_invoice_multiple');
    Route::get('/print_invoice_items/{id}', [OrderController::class, 'print_invoice_items'])->name('order.print_invoice_items');
    Route::get('/print_invoice_list/{id}', [OrderController::class, 'print_invoice_list'])->name('order.print_invoice_list');
    Route::get('/print_invoice/{id}', [OrderController::class, 'print_invoice'])->name('order.print_invoice');
    Route::get('/export_order/{id}', [OrderController::class, 'export_order'])->name('order.export_order');

    Route::get('/order/create', [OrderController::class, 'create'])->name('order.create');
    Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
    Route::get('/order/edit/{id}', [OrderController::class, 'edit'])->name('order.edit');
    Route::post('/order/update', [OrderController::class, 'update'])->name('order.update');
    Route::post('/order/updateOrderStatus', [OrderController::class, 'updateOrderStatus'])->name('order.updateOrderStatus');
    Route::get('/order/view/{id}', [OrderController::class, 'show'])->name('order.view');
    Route::get('/order/delete/{id}', [OrderController::class, 'destroy'])->name('order.destroy');
    Route::get('/order/addTocart', [OrderController::class, 'addTocart'])->name('order.addTocart');
    Route::get('/order/removeCart', [OrderController::class, 'removeCart'])->name('order.removeCart');


    //Customers
    Route::get('/customers', [UserController::class, 'index'])->name('customer.index');
    Route::get('/get_order_deliveryboy', [UserController::class, 'get_order_deliveryboy'])->name('customer.get_order_deliveryboy');
    Route::get('/updatedeliveryStatus', [UserController::class, 'updatedeliveryStatus'])->name('customer.updatedeliveryStatus');
    Route::get('/customers/deliveryboy', [UserController::class, 'deliveryboy'])->name('customer.deliveryboy');
    Route::get('/customers/sellers', [UserController::class, 'sellers'])->name('customer.sellers');
    Route::get('/assign_delivery_boy', [UserController::class, 'assign_delivery_boy'])->name('customer.assign_delivery_boy');
    Route::get('/customers/status/{id}', [UserController::class, 'updateStatus'])->name('customer.updateStatus');
    Route::get('/customers/create', [UserController::class, 'create'])->name('customer.create');
    Route::post('/customers/store', [UserController::class, 'store'])->name('customer.store');
    Route::get('/customers/edit/{id}', [UserController::class, 'edit'])->name('customer.edit');

    Route::get('/get_state_by_country', [UserController::class, 'get_state_by_country'])->name('get_state_by_country');
    Route::get('/get_city_by_state', [UserController::class, 'get_city_by_state'])->name('get_city_by_state');
    Route::get('/customers/getCustomers', [UserController::class, 'getCustomers'])->name('customer.getCustomers');

    //common
    Route::post('/upload-image', [UploadImageController::class, 'create'])->name('media.create');
    Route::get('/get-subcategory', [CommonController::class, 'getSubCategory'])->name('get-subcategory');
    Route::get('/get-states', [CommonController::class, 'getStates'])->name('get-states');
    Route::get('/get-cities', [CommonController::class, 'getCities'])->name('get-cities');
    Route::post('/registerExistingEmail', [CommonController::class, 'isExistingEmail'])->name('registerExistingEmail');
});
