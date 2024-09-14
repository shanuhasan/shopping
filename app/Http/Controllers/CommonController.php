<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CommonController extends Controller
{

    public function get_states(Request $request)
    {

        $country_id = $request->country_id;
        $states = State::where("country_id", $country_id)->select("id", "name")->get();
        return response()->json($states);
    }

    public function get_cities(Request $request)
    {
        $state_id = $request->state_id;
        $cities = City::where("state_id", $state_id)->select("id", "name")->get();
        return response()->json($cities);
    }

    public function getStates(Request $request)
    {

        $country_id = $request->country_id;
        $states = State::where("country_id", $country_id)->select("id", "name")->get();
        return response()->json($states);
    }

    public function getCities(Request $request)
    {
        $state_id = $request->state_id;
        $cities = City::where("state_id", $state_id)->select("id", "name")->get();
        return response()
            ->json($cities);
    }

    public function getSubCategory(Request $request)
    {
        $parent_id = $request->cat;
        $data = Category::where('parent_id', $parent_id)->select("id", "category_name")->get();
        return response()
            ->json($data);
    }


    public function isExistingEmail(Request $request)
    {
        if (isset($request->id)) {
            $user = User::where("email", $request->email)->where("id", "!=", $request->id)->first();
            if ($user) {
                return response()->json('<i class="fa fa-exclamation-triangle"></i> This email is already used');
            }
        } else {
            $validator = Validator::make($request->all(), [
                'email' => 'unique:users|email'
            ]);
            if ($validator->fails()) {
                return response()->json('<i class="fa fa-exclamation-triangle"></i> This email is already used');
            }
        }


        return response()->json('true');
    }
}
