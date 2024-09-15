<?php

use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;


function settings()
{
    $settings = Cache::remember('settings', 5, function () {
        $temp = Setting::where(['type' => 'site_settings', 'name' => 'site_settings'])->first();
        $temp1 = Setting::where(['type' => 'site_settings', 'name' => 'site_settings'])->first();
        //echo "<pre>"; 
        // print_r($temp); die;
        if (!empty($temp) && !empty($temp->value)) {
            $temp = json_decode($temp->value);

            $settings = collect();
            foreach ($temp as $key => $setting) {
                if ($key == 'country') {
                    $country_name = Country::where(['id' => $setting])->first()->name;
                    $settings->put('country_name', $country_name);
                }
                if ($key == 'state') {
                    $state_name = State::where(['id' => $setting])->first()->name;
                    $settings->put('state_name', $state_name);
                }
                if ($temp->city) {
                    if ($key == 'city') {
                        $city_name = City::where(['id' => $setting])->first()->name;
                        $settings->put('city_name', $city_name);
                    }
                }
                $settings->put($key, $setting);
            }

            if (!empty($settings['line_1'])) {
                $address[] = $settings['line_1'];
            }

            if (!empty($temp1->slogon)) {
                $settings->put('slogan', $temp1->slogon);
            }
            if (!empty($temp1->commission)) {
                $settings->put('commission', $temp1->commission);
            }
            if (!empty($temp1->gift_wrap)) {
                $settings->put('gift_wrap', $temp1->gift_wrap);
            }

            if (!empty(json_decode($temp1->type_id))) {
                $settings->put('type_id', $temp1->type_id);
            }

            if (!empty(json_decode($temp1->category_id))) {
                $settings->put('category_id', $temp1->category_id);
            }


            if (!empty($settings['line_2'])) {
                $address[] = $settings['line_2'] . "<br>";
            }
            if (!empty($settings['city_name'])) {
                $address[] = $settings['city_name'];
            }
            if (!empty($settings['state_name'])) {
                $address[] = $settings['state_name'];
            }
            if (!empty($settings['country_name'])) {
                $address[] = $settings['country_name'];
            }

            if (!empty($settings['zip_code'])) {
                $address[] = $settings['zip_code'] . "<br>";
            }
            if (count($address)) {
                $settings->put('address', implode(',', $address));
            }
            return $settings;
        }
    });

    return $settings;
}
