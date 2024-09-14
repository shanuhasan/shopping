<?php

namespace App\Http\Controllers\admin;

use App\Models\Media;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;

class CategoryController extends Controller
{
   public function index()
   {
      $data['category'] = Category::select('categories.*', 'tc.category_name as subcategory_name')
         ->leftJoin('categories as tc', 'categories.parent_id', '=', 'tc.id')
         ->get();
      $data['page_title'] = 'Category List';
      return view('admin/category/index', $data);
   }

   function create()
   {
      $data['category'] = Category::all();
      $data['page_title'] = 'Add Category';
      return view('admin.category.create', $data);
   }

   function store(Request $request)
   {
      $request['slug'] = Str::slug($request->category);

      $validator = Validator::make($request->all(), [
         'category_name' => 'required|unique:categories',
      ]);

      if ($validator->passes()) {

         $model = new Category();
         $model->parent_id = 0;
         $model->category_name = $request->category_name;
         $model->slug = $request->slug;
         $model->which_type = 'main_cat';
         $model->offer = $request->offer;
         $model->meta_title = $request->meta_title;
         $model->meta_keyword = $request->meta_keyword;
         $model->meta_discription = $request->meta_discription;
         $model->description = $request->description;
         $model->created_date = date('Y-m-d H:i:s');
         $model->updated_date = date('Y-m-d H:i:s');
         $model->status = 1;
         $model->add_by = Auth::user()->id;
         $model->save();

         //save image
         if (!empty($request->image_id)) {
            $tempImage = Media::find($request->image_id);
            $extArray = explode('.', $tempImage->name);
            $ext = last($extArray);

            $newImageName = $model->id . time() . '.' . $ext;
            $sPath = public_path() . '/temp/' . $tempImage->name;
            $dPath = public_path() . '/uploads/category/' . $newImageName;
            File::copy($sPath, $dPath);

            //generate thumb
            $dPath = public_path() . '/uploads/category/thumb/' . $newImageName;
            $manager = new ImageManager(new Driver());
            $img = $manager->read($sPath);
            $img->cover(450, 600);
            $img->save($dPath);

            $model->media_id = $newImageName;
            $model->save();
         }

         Session::flash('success', 'Category Added Successfully.');
         return redirect()->route('admin.category');
      } else {
         Session::flash('error', 'Please fill the required fields.');
         return redirect()->back()
            ->withErrors($validator)
            ->withInput();
      }
   }

   public function edit($id)
   {

      $data['edit_data'] = Category::find($id);
      $data['category'] = Category::all();
      $data['page_title'] = 'Edit Category';
      return view('admin.category.edit', $data);
   }

   function update(Request $request)
   {
      $id = $request->update_id;
      $request['slug'] = Str::slug($request->category_name);

      $model = Category::findById($id);

      $validator = Validator::make($request->all(), [
         'category_name' => 'required|unique:categories,category_name,' . $model->id . ',id',
      ]);

      if ($validator->passes()) {

         $model->category_name = $request->category_name;
         $model->slug = $request->slug;
         $model->offer = $request->offer;
         $model->meta_title = $request->meta_title;
         $model->meta_keyword = $request->meta_keyword;
         $model->meta_discription = $request->meta_discription;
         $model->description = $request->description;
         $model->updated_date = date('Y-m-d H:i:s');
         $model->add_by = Auth::user()->id;
         $model->save();

         $oldImage = $model->media_id;

         //save image
         if (!empty($request->image_id)) {
            $tempImage = Media::find($request->image_id);
            $extArray = explode('.', $tempImage->name);
            $ext = last($extArray);

            $newImageName = $model->id . time() . '.' . $ext;
            $sPath = public_path() . '/temp/' . $tempImage->name;
            $dPath = public_path() . '/uploads/category/' . $newImageName;
            File::copy($sPath, $dPath);

            //generate thumb
            $dPath = public_path() . '/uploads/category/thumb/' . $newImageName;
            $manager = new ImageManager(new Driver());
            $img = $manager->read($sPath);
            $img->cover(450, 600);
            $img->save($dPath);

            $model->media_id = $newImageName;
            $model->save();

            //delete old image
            File::delete(public_path() . '/uploads/category/thumb/' . $oldImage);
            File::delete(public_path() . '/uploads/category/' . $oldImage);
         }


         Session::flash('success', 'Category update Successfully....');
         return redirect()->route('admin.category.edit', $id);
      } else {
         Session::flash('error', 'Category Name is required...');
         return redirect()->route('admin.category.edit', $id);
      }
   }

   function delete($id)
   {
      $edit_data = Category::findById($id);
      File::delete(public_path() . '/uploads/category/thumb/' . $edit_data->media_id);
      File::delete(public_path() . '/uploads/category/' . $edit_data->media_id);
      DB::table('categories')->where('id', $id)->delete();
      Session::flash('success', 'Delete successfully...');
      return redirect()->route('admin.category');
   }

   function deactive($id)
   {
      $data = array('status' => '1');
      DB::table('categories')->where('id', $id)->update($data);
      Session::flash('success', 'Status Update successfully...');
      return redirect()->back();
   }

   function active($id)
   {
      $data = array('status' => '0');
      DB::table('categories')->where('id', $id)->update($data);
      Session::flash('success', 'Status Update successfully...');
      return redirect()->back();
   }

   function subCategory()
   {
      $data['category'] = Category::getSubCategory();

      $data['page_title'] = 'Sub Category List';
      return view('admin.category.subcategory', $data);
   }

   function createSubCategory()
   {
      $data['category'] = Category::getCategory();
      $data['page_title'] = 'Add SubCategory';
      return view('admin.category.add_subcategory', $data);
   }

   function storeSubCategory(Request $request)
   {

      $request['slug'] = Str::slug($request->category);

      $validator = Validator::make($request->all(), [
         'category_name' => 'required|unique:categories',
      ]);

      if ($validator->passes()) {

         $model = new Category();
         $model->parent_id = $request->parent_category;
         $model->category_name = $request->category_name;
         $model->slug = $request->slug;
         $model->which_type = 'sub_cat';
         $model->offer = $request->offer;
         $model->meta_title = $request->meta_title;
         $model->meta_keyword = $request->meta_keyword;
         $model->meta_discription = $request->meta_discription;
         $model->description = $request->description;
         $model->created_date = date('Y-m-d H:i:s');
         $model->updated_date = date('Y-m-d H:i:s');
         $model->status = 1;
         $model->add_by = Auth::user()->id;
         $model->save();

         //save image
         if (!empty($request->image_id)) {
            $tempImage = Media::find($request->image_id);
            $extArray = explode('.', $tempImage->name);
            $ext = last($extArray);

            $newImageName = $model->id . time() . '.' . $ext;
            $sPath = public_path() . '/temp/' . $tempImage->name;
            $dPath = public_path() . '/uploads/category/' . $newImageName;
            File::copy($sPath, $dPath);

            //generate thumb
            $dPath = public_path() . '/uploads/category/thumb/' . $newImageName;
            $manager = new ImageManager(new Driver());
            $img = $manager->read($sPath);
            $img->cover(450, 600);
            $img->save($dPath);

            $model->media_id = $newImageName;
            $model->save();
         }
         Session::flash('success', 'Category Added Successfully.');
         return redirect()->route('admin.subcategory');
      } else {
         Session::flash('error', 'Please fill the required fields.');
         return redirect()->back()
            ->withErrors($validator)
            ->withInput();
      }
   }

   function editSubCategory($id)
   {
      $data['edit_data'] = Category::find($id);
      $data['category'] = Category::getCategory();
      $data['offer_banner'] = DB::table('offer_banners')->where('sub_category_id', $id)->first();
      $data['page_title'] = 'Edit Sub Category';
      return view('admin.category.edit_subcategory', $data);
   }

   function updateSubCategory(Request $request)
   {
      $id = $request->update_id;
      $request['slug'] = Str::slug($request->category);

      $model = Category::findById($id);

      $validator = Validator::make($request->all(), [
         'category_name' => 'required|unique:categories,category_name,' . $model->id . ',id',
      ]);

      if ($validator->passes()) {
         $model->parent_id = $request->parent_category;
         $model->category_name = $request->category_name;
         $model->slug = $request->slug;
         $model->meta_title = $request->meta_title;
         $model->meta_keyword = $request->meta_keyword;
         $model->meta_discription = $request->meta_discription;
         $model->description = $request->description;
         $model->updated_date = date('Y-m-d H:i:s');
         $model->add_by = Auth::user()->id;
         $model->save();

         $oldImage = $model->media_id;
         //save image
         if (!empty($request->image_id)) {
            $tempImage = Media::find($request->image_id);
            $extArray = explode('.', $tempImage->name);
            $ext = last($extArray);

            $newImageName = $model->id . time() . '.' . $ext;
            $sPath = public_path() . '/temp/' . $tempImage->name;
            $dPath = public_path() . '/uploads/category/' . $newImageName;
            File::copy($sPath, $dPath);

            //generate thumb
            $dPath = public_path() . '/uploads/category/thumb/' . $newImageName;
            $manager = new ImageManager(new Driver());
            $img = $manager->read($sPath);
            $img->cover(450, 600);
            $img->save($dPath);

            $model->media_id = $newImageName;
            $model->save();

            //delete old image
            File::delete(public_path() . '/uploads/category/thumb/' . $oldImage);
            File::delete(public_path() . '/uploads/category/' . $oldImage);
         }

         Session::flash('success', 'Category update Successfully....');
         return redirect()->route('admin.subcategory.edit', $id);
      } else {
         Session::flash('error', 'Please fill the required fields.');
         return redirect()->back()
            ->withErrors($validator)
            ->withInput();
      }
   }

   function deleteSubCategory($id)
   {
      $edit_data = Category::findById($id);
      File::delete(public_path() . '/uploads/category/thumb/' . $edit_data->media_id);
      File::delete(public_path() . '/uploads/category/' . $edit_data->media_id);
      DB::table('categories')->where('id', $id)->delete();
      Session::flash('success', 'Delete successfully.');
      return redirect()->route('admin.subcategory');
   }

   function childCategory()
   {
      $data['category'] = Category::getChildCategory();
      $data['page_title'] = 'Add Child Category List';
      return view('admin.category.child_category_list', $data);
   }

   function createChildCategory()
   {
      $data['category'] = Category::getCategory();
      $data['page_title'] = 'Add Child Category';
      return view('admin.category.add_child_category', $data);
   }

   public function storeChildCategory(Request $request)
   {
      $request['slug'] = Str::slug($request->child_category);

      $validator = Validator::make($request->all(), [
         'parent_category' => 'required',
         'sub_category' => 'required',
         'category_name' => 'required',
         'meta_title' => 'required',
         'meta_keyword' => 'required'
      ]);

      if ($validator->passes()) {

         $model = new Category();
         $model->parent_id = $request->sub_category;
         $model->category_name = $request->category_name;
         $model->slug = $request->slug;
         $model->which_type = 'child_cat';
         $model->meta_title = $request->meta_title;
         $model->meta_keyword = $request->meta_keyword;
         $model->meta_discription = $request->meta_discription;
         $model->description = $request->description;
         $model->created_date = date('Y-m-d H:i:s');
         $model->updated_date = date('Y-m-d H:i:s');
         $model->status = 1;
         $model->add_by = Auth::user()->id;
         $model->save();

         //save image
         if (!empty($request->image_id)) {
            $tempImage = Media::find($request->image_id);
            $extArray = explode('.', $tempImage->name);
            $ext = last($extArray);

            $newImageName = $model->id . time() . '.' . $ext;
            $sPath = public_path() . '/temp/' . $tempImage->name;
            $dPath = public_path() . '/uploads/category/' . $newImageName;
            File::copy($sPath, $dPath);

            //generate thumb
            $dPath = public_path() . '/uploads/category/thumb/' . $newImageName;
            $manager = new ImageManager(new Driver());
            $img = $manager->read($sPath);
            $img->cover(450, 600);
            $img->save($dPath);

            $model->media_id = $newImageName;
            $model->save();
         }
         Session::flash('success', 'Category Added Successfully.');
         return redirect()->route('admin.childcategory');
      } else {
         Session::flash('error', 'Please fill the required fields.');
         return redirect()->back()
            ->withErrors($validator)
            ->withInput();
      }
   }

   public function editChildCategory($id)
   {
      $data['edit_category'] = Category::where('id', $id)->first();
      $data['sub_category'] = Category::where('id', $data['edit_category']->parent_id)->first();
      $data['parent_category'] = Category::where('id', $data['sub_category']->parent_id)->first();

      $data['category'] = Category::getCategory();
      $data['subcategory'] = Category::where('parent_id', $data['parent_category']->id)->get();

      $data['page_title'] = 'Edit Child Category';
      return view('admin.category.edit_child_category', $data);
   }

   public function updateChildCategory(Request $request)
   {
      $id = $request->update_id;
      $request['slug'] = Str::slug($request->child_category);

      $model = Category::findById($id);

      $validator = Validator::make($request->all(), [
         // 'category_name' => 'required|unique:categories,category_name,' . $model->id . ',id',
         'category_name' => 'required',
      ]);

      if ($validator->passes()) {
         $model->parent_id = $request->sub_category;
         $model->category_name = $request->category_name;
         $model->slug = $request->slug;
         $model->meta_title = $request->meta_title;
         $model->meta_keyword = $request->meta_keyword;
         $model->meta_discription = $request->meta_discription;
         $model->description = $request->description;
         $model->updated_date = date('Y-m-d H:i:s');
         $model->add_by = Auth::user()->id;
         $model->save();

         $oldImage = $model->media_id;
         //save image
         if (!empty($request->image_id)) {
            $tempImage = Media::find($request->image_id);
            $extArray = explode('.', $tempImage->name);
            $ext = last($extArray);

            $newImageName = $model->id . time() . '.' . $ext;
            $sPath = public_path() . '/temp/' . $tempImage->name;
            $dPath = public_path() . '/uploads/category/' . $newImageName;
            File::copy($sPath, $dPath);

            //generate thumb
            $dPath = public_path() . '/uploads/category/thumb/' . $newImageName;
            $manager = new ImageManager(new Driver());
            $img = $manager->read($sPath);
            $img->cover(450, 600);
            $img->save($dPath);

            $model->media_id = $newImageName;
            $model->save();

            //delete old image
            File::delete(public_path() . '/uploads/category/thumb/' . $oldImage);
            File::delete(public_path() . '/uploads/category/' . $oldImage);
         }

         Session::flash('success', 'Category update Successfully....');
         return redirect()->route('admin.childcategory.edit', $id);
      } else {
         Session::flash('error', 'Please fill the required fields.');
         return redirect()->back()
            ->withErrors($validator)
            ->withInput();
      }
   }

   public function deleteChildCategory($id)
   {
      DB::table('categories')->where('id', $id)->delete();
      Session::flash('success', 'Child Category Deleted Successfully....');
      return redirect()->back();
   }

   function get_subcategory_by_ajax(Request $request)
   {
      if (!empty($request->value)) {
         $data = Category::where('parent_id', $request->value)->get();
         if (!empty($data)) {
            echo "<option value='0'> Select SubCategory </option>";
            foreach ($data as $key => $d) {
               echo "<option value='" . $d->id . "'> " . $d->category_name . " </option>";
            }
         }
      } else {
         echo "<option value='0'> Select SubCategory </option>";
      }
   }
}
