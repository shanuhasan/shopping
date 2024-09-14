<?php

namespace App\Http\Controllers\Admin;

use DB;
use Session;
use Sentinel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\VendorModel as VendorModel;
use App\Models\ServiceModel as ServiceModel;
use App\Models\Category_model as Category_model;
use App\Models\Order;
use App\Models\ServiceprovideModel as ServiceprovideModel;
use App\Models\User;

class Dashboardcontroller extends Controller
{
	public function report_index()
	{
		return view('admin.report.index');
	}

	public function index()
	{
		$role = Auth::user()->role_id;

		if ($role == User::ADMIN) {
			$data['users'] = User::getUserByRole(User::CUSTOMER);
			$data['vendor'] = User::getUserByRole(User::VENDOR);
			$data['orders'] = Order::getOrders();
			$data['currentMonthOrders'] = Order::getCurrentMonthOrders();
			return view('admin.dashboard', $data);
		} else {
			return Redirect('vendor/dasboard');
		}
	}

	function get_data_graph()
	{
		$total_array = array();
		$sum = 0;
		$sum1 = 0;
		$sum2 = 0;
		$sum3 = 0;
		$sum4 = 0;
		$sum5 = 0;
		$sum6 = 0;
		$sum7 = 0;
		$sum8 = 0;
		$sum9 = 0;
		$sum10 = 0;
		$sum11 = 0;
		$data = Order::getOrders();

		foreach ($data as $key => $value) {
			if (date('Y-m', strtotime($value->date)) == date('Y-01')) {
				$sum += $value->grand_total;
			}
			if (date('Y-m', strtotime($value->date)) == date('Y-02')) {
				$sum1 += $value->grand_total;
			}
			if (date('Y-m', strtotime($value->date)) == date('Y-03')) {
				$sum2 += $value->grand_total;
			}
			if (date('Y-m', strtotime($value->date)) == date('Y-04')) {
				$sum3 += $value->grand_total;
			}
			if (date('Y-m', strtotime($value->date)) == date('Y-05')) {
				$sum4 += $value->grand_total;
			}
			if (date('Y-m', strtotime($value->date)) == date('Y-06')) {
				$sum5 += $value->grand_total;
			}
			if (date('Y-m', strtotime($value->date)) == date('Y-07')) {
				$sum6 += $value->grand_total;
			}
			if (date('Y-m', strtotime($value->date)) == date('Y-08')) {
				$sum7 += $value->grand_total;
			}
			if (date('Y-m', strtotime($value->date)) == date('Y-09')) {
				$sum8 += $value->grand_total;
			}
			if (date('Y-m', strtotime($value->date)) == date('Y-10')) {
				$sum9 += $value->grand_total;
			}
			if (date('Y-m', strtotime($value->date)) == date('Y-11')) {
				$sum10 += $value->grand_total;
			}
			if (date('Y-m', strtotime($value->date)) == date('Y-12')) {
				$sum11 += $value->grand_total;
			}
		}

		$month = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		$data_complete_booking = array($sum, $sum1, $sum2, $sum3, $sum4, $sum5, $sum6, $sum7, $sum8, $sum9, $sum10, $sum11);
		echo json_encode(array('month' => $month, 'complete_booking_data' => $data_complete_booking));
	}
}
