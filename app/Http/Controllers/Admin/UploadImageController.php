<?php

namespace App\Http\Controllers\Admin;

use App\Models\Media;
use Illuminate\Http\Request;
use App\Models\ProductDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class UploadImageController extends Controller
{
    public function create(Request $request)
    {
        $image = $request->image;

        // dd($image);

        if (!empty($image)) {
            $ext = $image->getClientOriginalExtension();
            $tempImage = new Media();
            $tempImage->name = 'test';
            $tempImage->save();

            $newName = $tempImage->id . '-' . time() . '.' . $ext;
            $tempImage->name = $newName;
            $tempImage->save();

            $image->move(public_path() . '/temp', $newName);

            //generate thumb

            $manager = new ImageManager(new Driver());

            $sourcePath = public_path() . '/temp/' . $newName;
            $dPath = public_path() . '/temp/thumb/' . $newName;

            $image = $manager->read($sourcePath);
            $image->cover(300, 275);
            $image->save($dPath);

            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                'imagePath' => asset('temp/thumb/' . $newName),
                'message' => 'Image uploaded successfully'
            ]);
        }
    }

    // public function update(Request $request)
    // {
    //     $image = $request->image;
    //     $ext = $image->getClientOriginalExtension();
    //     $sPath = $image->getPathName();

    //     $detailModel = new ProductDetail();
    //     $detailModel->product_id = $request->product_id;
    //     $detailModel->image = 'NULL';
    //     $detailModel->save();

    //     $newImageName = $request->product_id . '-' . $detailModel->id . '-' . time() . '.' . $ext;
    //     $detailModel->image = $newImageName;
    //     $detailModel->save();

    //     // Large Image
    //     $dPath = public_path() . '/uploads/product/large/' . $newImageName;
    //     $manager = new ImageManager(new Driver());
    //     $image = $manager->read($sPath);
    //     $image->scaleDown(1400);
    //     $image->save($dPath);

    //     //generate thumb
    //     $dPath = public_path() . '/uploads/product/thumb/' . $newImageName;
    //     $manager = new ImageManager(new Driver());
    //     $img = $manager->read($sPath);
    //     $img->cover(300, 300);
    //     $img->save($dPath);

    //     return response()->json([
    //         'status' => true,
    //         'image_id' => $detailModel->id,
    //         'imagePath' => asset('uploads/product/thumb/' . $detailModel->image),
    //         'message' => 'Image saved successfully',
    //     ]);
    // }

    // public function delete(Request $request)
    // {
    //     $productImage = ProductDetail::find($request->id);

    //     if (empty($productImage)) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Image not found.',
    //         ]);
    //     }

    //     File::delete(public_path('uploads/product/large/' . $productImage->image));
    //     File::delete(public_path('uploads/product/thumb/' . $productImage->image));

    //     $productImage->delete();

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Image deleted successfully',
    //     ]);
    // }
}
