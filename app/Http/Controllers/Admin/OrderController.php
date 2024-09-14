<?php

namespace App\Http\Controllers\Admin;

use App;
use PDF;
use Session;
use Sentinel;
use Validator;
use App\Models\City;
use App\Models\User;
use App\Models\Order;
use App\Models\State;
use App\Models\Country;
use App\Models\OrderItem;
use App\Models\OrderItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\shiproket\ShiproketController;

class OrderController extends Controller
{

    // protected $shiproket;
    // public function __construct(ShiproketController $shiproketController)
    // {
    //     $this->shiproket = $shiproketController;
    // }

    public function report()
    {
        $slug = Sentinel::getUser()->roles()->first()->slug;
        $role = Sentinel::findRoleBySlug('vendor');
        $users = $role->users()->get();
        $data['vendor'] = $users;

        $role_delboy = Sentinel::findRoleBySlug('deliveryboy');
        $del_boy = $role_delboy->users()->get();
        $data['deliveryboy'] = $del_boy;

        $role_cust = Sentinel::findRoleBySlug('customer');
        $cust = $role_cust->users()->get();
        $data['customer'] = $cust;

        $data['area'] = DB::table('tbl_area')->get();
        $data['cities'] = DB::table('tbl_custom_city')->get();

        $data['product_list'] = DB::table('orders')->select('orders.order_id', 'orders.email', 'orders.date', 'tbl_services.service_name', 'users.first_name', 'users.last_name', 'orders.status', 'order_items.price', 'user.first_name as user_id', 'tbl_custom_city.city')
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            ->leftJoin('tbl_services', 'order_items.product_id', '=', 'tbl_services.id')
            ->leftJoin('users', 'tbl_services.add_by', '=', 'users.id')
            ->leftJoin('deliveries', 'deliveries.order_id', '=', 'orders.id')
            ->leftJoin('users as user', 'deliveries.user_id', '=', 'user.id')
            ->leftJoin('users as u', 'u.id', '=', 'orders.user_id')
            ->leftJoin('tbl_custom_city', 'tbl_custom_city.id', '=', 'u.city')
            ->orderBy('orders.date', 'desc')
            ->get();

        $data['page_title'] = 'Report';
        return view('admin/report/report', $data);
    }

    public function export_order_report($id)
    {
        $array = explode("-", $id);
        $fileName = 'order.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        $slug = Sentinel::getUser()->roles()->first()->slug;

        $products = DB::table('orders')->select('orders.order_id', 'orders.email', 'orders.date', 'tbl_services.service_name', 'users.first_name', 'users.last_name', 'order_items.status', 'order_items.price', 'orders.payment_method', 'user.first_name as user_id')
            ->whereIn('orders.order_id', $array)
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            ->leftJoin('tbl_services', 'order_items.product_id', '=', 'tbl_services.id')
            ->leftJoin('users', 'tbl_services.add_by', '=', 'users.id')
            ->leftJoin('deliveries', 'deliveries.order_id', '=', 'orders.id')
            ->leftJoin('users as user', 'deliveries.user_id', '=', 'user.id')
            ->orderBy('orders.date', 'desc')
            ->get();

        $columns = array('S.No.', 'Product Name', 'Order Id', 'Email', 'Price', 'Vendor Name', 'Order Date', 'Status', 'Delivery Boy', 'Payment Method');

        $callback = function () use ($products, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $i = 1;
            foreach ($products as $item) {
                $row['sno']  = $i++;
                $row['product_name']  = $item->service_name;
                $row['order_id']    = $item->order_id;
                $row['email']    = $item->email;
                $row['price']    = $item->price;
                $row['vendor_name']  = $item->first_name;
                $row['order_date']  = $item->date;
                $row['status']  = $item->status;
                $row['delboy']  = $item->user_id;
                $row['payment_method']  = $item->payment_method;

                fputcsv($file, array($row['sno'], $row['product_name'], $row['order_id'], $row['email'], $row['price'], $row['vendor_name'], $row['order_date'], $row['status'], $row['delboy'], $row['payment_method']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function filter_report(Request $request)
    {
        $venid = $request->vendor;
        $status = $request->status;
        $delboy = $request->deliveryboy;
        $area = $request->area;
        $payment_method = $request->payment_method;
        $date = $request->filterDate;
        $city = $request->city;
        $cust = $request->customer;

        $slug = Sentinel::getUser()->roles()->first()->slug;
        $role = Sentinel::findRoleBySlug('vendor');
        $users = $role->users()->get();
        $data['vendor'] = $users;

        $role_delboy = Sentinel::findRoleBySlug('deliveryboy');
        $del_boy = $role_delboy->users()->get();
        $data['deliveryboy'] = $del_boy;

        $role_cust = Sentinel::findRoleBySlug('customer');
        $cust = $role_cust->users()->get();
        $data['customer'] = $cust;


        $data['area'] = DB::table('tbl_area')->get();
        $data['cities'] = DB::table('tbl_custom_city')->get();

        $getData = DB::table('orders')->select('orders.order_id', 'orders.date', 'orders.email', 'tbl_services.service_name', 'users.first_name', 'users.last_name', 'order_items.status', 'order_items.price', 'orders.area', 'orders.payment_method', 'user.first_name as user_id', 'tbl_custom_city.city')
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            ->leftJoin('tbl_services', 'order_items.product_id', '=', 'tbl_services.id')
            ->leftJoin('users', 'tbl_services.add_by', '=', 'users.id')
            ->leftJoin('deliveries', 'deliveries.order_id', '=', 'orders.id')
            ->leftJoin('users as user', 'deliveries.user_id', '=', 'user.id')
            ->leftJoin('users as u', 'u.id', '=', 'orders.user_id')
            ->leftJoin('tbl_custom_city', 'tbl_custom_city.id', '=', 'u.city');


        if ($venid) {
            $getData = $getData->where('tbl_services.add_by', $venid);
        }
        if ($status) {
            $getData = $getData->where('order_items.status', $status);
        }
        if ($delboy) {
            $getData = $getData->where('deliveries.user_id', $delboy);
        }
        if ($area) {
            $getData = $getData->where('orders.area', $area);
        }
        if ($payment_method) {
            $getData = $getData->where('orders.payment_method', $payment_method);
        }
        if ($date) {
            $getData = $getData->where(DB::raw("(STR_TO_DATE(orders.date,'%Y-%m-%d'))"), $date);
        }
        if ($city) {
            $getData = $getData->where('tbl_custom_city.id', $city);
        }
        if ($cust) {
            $getData = $getData->where('orders.user_id', $cust);
        } else {
        }

        $finalData = $getData->orderBy('orders.date', 'desc')->get();

        $data['product_list'] = $finalData;


        $data['page_title'] = 'Report';

        return view('admin/report/report', $data);
    }

    public function export_order($id)
    {

        $array = explode("-", $id);
        $fileName = 'order.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        $slug = Sentinel::getUser()->roles()->first()->slug;

        if ($slug == 'super_admin') {
            $data_lsit = array();
            $data_lsit = Order::orderBy('id', 'DESC')->whereIn('id', $array)->get();
            foreach ($data_lsit as $key => $value) {
                $data_lsit[$key]->get_items = $this->get_order_item($value->id);
            }
            $data = $data_lsit;
        } else {
            $users = Sentinel::getUser();
            $orders = array();
            $orders = Order::whereIn('orders.id', $array)->select(DB::raw("orders.id as id,orders.order_id as order_id,sum(order_items.total) as grand_total,orders.name as name,orders.email as email,orders.phone as phone,orders.status as status,orders.payment_status as payment_status,date,order_items.vendor_id as vendor_id"))
                ->whereRaw("tbl_services.add_by={$users->id}")
                ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
                ->leftJoin('tbl_services', 'order_items.product_id', '=', 'tbl_services.id')
                ->groupBy("orders.id")
                ->get();
            foreach ($orders as $key => $value) {
                $orders[$key]->get_items = $this->get_order_item($value->id);
            }

            $data = $orders;
        }

        $columns = array('Id', 'Order Date', 'Order Id', 'Customer Name', 'Email', 'Phone', 'Total Amount', 'Status', 'Payment Status', 'Vendor');

        $callback = function () use ($data, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($data as $task) {
                $row['id']  = $task->id;
                $row['date']  = $task->date;
                $row['order_id']    = $task->order_id;
                $row['name']  = $task->name;
                $row['email']  = $task->email;
                $row['phone']  = $task->phone;
                $row['grand_total']  = $task->grand_total;
                $row['status']  = $task->status;
                $row['payment']  = $task->payment_status;
                $row['vendor']  = $task->vendor_id;


                fputcsv($file, array($row['id'], $row['date'], $row['order_id'], $row['name'], $row['email'], $row['phone'], $row['grand_total'], $row['status'], $row['payment'], $row['vendor']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


    public function order_item_status(Request $request)
    {

        $data = explode('_', $request->value);

        $orderitemId = $data[1];

        if ($data[0] == 'returnpending') {
            $res = DB::table('order_items')->where('id', $data[1])->update(["status" => 'return_pending']);
            DB::table('commission_set')->where('order_item_id', $orderitemId)->update(["status" => 'return_pending']);
            return $res;
        } elseif ($data[0] == 'returncomplete') {

            $res = DB::table('order_items')->where('id', $data[1])->update(["status" => 'return_complete']);

            $chk = DB::table('order_items')->where('id', $data[1])->first();
            $chkSecond = DB::table('order_items')->where('order_id', $chk->order_id)->where('status', '!=', 'return_complete')->get();
            if (count($chkSecond) <= 0) {
                DB::table('orders')->where('id', $chk->order_id)->update(["status" => "return_complete"]);
            }

            DB::table('commission_set')->where('order_item_id', $orderitemId)->update(["status" => 'return_complete']);

            return $res;
        } elseif ($data[0] == 'cancelled') {

            $res = DB::table('order_items')->where('id', $data[1])->update(["status" => 'cancelled']);

            $chk = DB::table('order_items')->where('id', $data[1])->first();

            $chkSecond = DB::table('order_items')->where('order_id', $chk->order_id)->where('status', '!=', 'cancelled')->get();
            if (count($chkSecond) <= 0) {

                DB::table('orders')->where('id', $chk->order_id)->update(["status" => "cancelled"]);
                DB::table('commission_set')->where('order_item_id', $orderitemId)->update(["status" => 'cancelled']);
            }
            return $res;
        } else {

            DB::table('order_items')->where('id', $data[1])->update(["status" => $data[0]]);

            if ($data[0] == 'shipped') {
                $responseData = $this->shiproket->validTokenGenrateShiproket($data[1]);
            } else if ($data[0] == 'delivered') {
                DB::table('commission_set')->where('order_item_id', $orderitemId)->update(["status" => "delivered"]);
            }

            $chk = DB::table('order_items')->where('id', $data[1])->first();
            $chkSecond = DB::table('order_items')->where('order_id', $chk->order_id)->where('status', '!=', 'delivered')->get();
            if (count($chkSecond) <= 0) {
                DB::table('orders')->where('id', $chk->order_id)->update(["status" => "delivered"]);
            }

            DB::table('commission_set')->where('order_item_id', $orderitemId)->update(["status" => $data[0]]);
            return true;
        }
    }

    public function index()
    {
        $role = Auth::user()->role_id;
        $userId = Auth::user()->id;
        $data = [];
        $orders = [];

        if ($role == User::ADMIN) {
            $orders = Order::orderBy('id', 'DESC')->limit(100)->get();
            foreach ($orders as $key => $value) {
                $orders[$key]->get_items = $this->get_order_item($value->id);
            }
        } else {

            $users = User::getUsers();

            $orders = Order::select(DB::raw("orders.id as id,orders.order_id as order_id,sum(order_items.total) as grand_total,orders.user_name,orders.email,orders.phone,orders.status,orders.payment_status,date"))
                ->whereRaw("tbl_services.add_by={$users->id}")
                ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
                ->leftJoin('tbl_services', 'order_items.product_id', '=', 'tbl_services.id')
                ->groupBy("orders.id")
                ->get();

            foreach ($orders as $key => $value) {
                $orders[$key]->get_items = $this->get_order_item($value->id);
            }
        }

        $data['list'] = $orders;
        $data['role'] = $role;
        $data['deliveryboy'] = User::getUserByRole(User::DELIVERY_BOY);
        $data['customers'] = User::getUserByRole(User::CUSTOMER);
        $data['area'] = DB::table('areas')->get();

        // echo "<pre>";
        // print_r($data);
        // die;

        return view('admin.orders.index1', $data);
    }


    public function orders()
    {

        $slug = Sentinel::getUser()->roles()->first()->slug;

        if ($slug == 'super_admin') {
            $data_lsit = array();
            $data_lsit = Order::orderBy('id', 'DESC')->where('status', 'pending')->limit(50)
                ->get();
            foreach ($data_lsit as $key => $value) {
                $data_lsit[$key]->get_items = $this->get_order_item($value->id);
            }
            $data['list'] = $data_lsit;
        } else {
            $users = Sentinel::getUser();
            $orders = array();
            $orders = Order::select(DB::raw("orders.id as id,orders.order_id as order_id,sum(order_items.total) as grand_total,orders.user_name,orders.email,orders.phone,orders.status,orders.payment_status,date"))
                ->whereRaw("tbl_services.add_by={$users->id}")
                ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
                ->leftJoin('tbl_services', 'order_items.product_id', '=', 'tbl_services.id')
                ->groupBy("orders.id")
                ->get();
            foreach ($orders as $key => $value) {
                $orders[$key]->get_items = $this->get_order_item($value->id);
            }

            $data['list'] = $orders;
        }
        $data['slug'] = $slug;
        $role = Sentinel::findRoleBySlug('deliveryboy');
        $users = $role->users()->get();
        $data['deliveryboy'] = $users;

        $role_customers = Sentinel::findRoleBySlug('customer');
        $customers = $role_customers->users()->get();
        $data['customers'] = $customers;

        $data['area'] = DB::table('areas')->get();
        $data['page_title'] = 'All Orders';
        return view('admin/orders/index1', $data);
    }

    public function filter_orders(Request $request)
    {

        $slug = Sentinel::getUser()->roles()->first()->slug;

        if ($slug == 'super_admin') {
            $data_lsit = array();

            if ($request->filters == 'Filter By Status') {
                $data_lsit = Order::orderBy('id', 'DESC')->where('status', $request->status)->get();
            } else if ($request->filters == 'Filter By Area') {
                $data_lsit = Order::orderBy('id', 'DESC')->where('area', $request->area)->get();
            } else if ($request->filters == 'Filter By Datetime') {
                echo $request->firstdate;
                echo "<br>";
                echo $request->firsttime;
                echo "<br>";
                echo $request->seconddate;
                echo "<br>";
                echo $request->secondtime;
                echo "<br>";

                /* $datetime=explode("-",$request->datetime);
               $first=$datetime[0];
               $firstdatetime=explode(" ",$first);
               print_r($request->datetime);*/
                die;
                $data_lsit = Order::where('area', $request->area)->orderBy('id', 'DESC')->get();
            } else if ($request->filters == 'Filter By Date') {

                $startDate  = $request->date1;
                $endDate  = $request->date2;
                $data_lsit = Order::whereBetween('orders.created_at', [$startDate, $endDate])->orderBy('id', 'DESC')->get();
            } else if ($request->filters == 'Filter By Order') {

                $from_order = $request->from_order;
                $to_order = $request->to_order;
                $data_lsit = Order::orderBy('id', 'DESC')->whereBetween(DB::raw('order_id'), [$from_order, $to_order])->get();
            } else if ($request->filters == 'Filter By Customer') {
                $customers = $request->customers;
                $data_lsit = Order::orderBy('id', 'DESC')->where('user_id', $customers)->get();
            } else if ($request->filters == '') {
                $data_lsit = Order::orderBy('id', 'DESC')->get();
            }

            foreach ($data_lsit as $key => $value) {
                $data_lsit[$key]->get_items = $this->get_order_item($value->id);
            }
            $data['list'] = $data_lsit;
        } else {

            $users = Sentinel::getUser();
            $orders = array();

            $search_vendor = DB::table('orders');

            if ($request->filters == 'Filter By Status') {

                $search_vendor = $search_vendor->where('orders.status', $request->status)->orderBy('orders.id', 'DESC');
            } else if ($request->filters == 'Filter By Area') {
                $search_vendor = $search_vendor->where('area', $request->area)->orderBy('id', 'DESC');
            } else if ($request->filters == 'Filter By Datetime') {

                echo $request->firstdate;
                echo "<br>";
                echo $request->firsttime;
                echo "<br>";
                echo $request->seconddate;
                echo "<br>";
                echo $request->secondtime;
                echo "<br>";

                $search_vendor = $search_vendor->where('area', $request->area)->orderBy('id', 'DESC');
            } else if ($request->filters == 'Filter By Date') {

                $startDate  = $request->date1;
                $endDate  = $request->date2;
                $search_vendor = $search_vendor->whereBetween('orders.created_at', [$startDate, $endDate])->orderBy('id', 'DESC');
            } else if ($request->filters == 'Filter By Order') {
                $from_order =   $request->from_order;
                $to_order   =   $request->to_order;
                $search_vendor = $search_vendor->whereBetween(DB::raw('order_id'), [$from_order, $to_order])->orderBy('id', 'DESC');
            } else if ($request->filters == 'Filter By Customer') {

                $customers = $request->customers;
                $search_vendor = $search_vendor->where('user_id', $customers)->orderBy('id', 'DESC');
            } else if ($request->filters == '') {
                $search_vendor = $search_vendor->orderBy('id', 'DESC');
            }

            $orders = $search_vendor->select(DB::raw("orders.*"))
                ->where("tbl_services.add_by", $users->id)
                ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
                ->leftJoin('tbl_services', 'order_items.product_id', '=', 'tbl_services.id')
                ->groupBy("orders.id")
                ->get();

            //dd($orders);

            foreach ($orders as $key => $value) {
                $orders[$key]->get_items = $this->get_order_item($value->id);
            }
            $data['list'] = $orders;
        }

        $data['slug']               =   $slug;
        $role                       =   Sentinel::findRoleBySlug('deliveryboy');
        $users                      =   $role->users()->get();
        $role_customers             =   Sentinel::findRoleBySlug('customer');
        $customers                  =   $role_customers->users()->get();
        $data['deliveryboy']        =   $users;
        $data['customers']          =   $customers;
        $data['area']               =   DB::table('areas')->get();
        $data['page_title']         =   'All Orders';

        return view('admin/orders/index1', $data);
    }




    function search_customer(Request $request)
    {
        $value = $request->value;
        $data = DB::table('users')
            ->where('first_name', $value)
            ->orWhere('phone', $value)
            ->get();
        if ($data) {
            print_r($data);
        }
    }
    function subquery()
    {
        $data = array();
        $data = Order::orderBy('id', 'DESC')->whereIn('payment_method', ['online', 'cod'])->get();
        foreach ($data as $key => $value) {
            $data[$key]->get_items = $this->get_order_item($value->id);
        }
        echo "<pre>";
        print_r($data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Session::put('cart', array());
        $state = array('' => "Select country");
        $city = array('' => "Select state");
        $countries = array('' => "Select country");
        $country = Country::all();
        foreach ($country as $cun) {
            $countries[$cun->id] = $cun->name;
        }
        $data['status'] = array(
            "pending" => "Pending",
            "ordered" => "Ordered",
            "processing" => "Processing",
            "delivered" => "Delivered",
        );

        $data['payment_status'] = array(
            "pending" => "Pending",
            "success" => "Success",
        );
        $data['cities'] = $city;
        $data['states'] = $state;
        $data['countries'] = $countries;
        $data['page_title'] = 'Add Order';
        return view('admin/orders/add', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!empty(Session::get('cart'))) {
            $address = array();
            $carts = Session::get('cart');
            $user = User::where("id", $request->customer)->select(DB::raw("id,concat(first_name,' ',last_name) as user_name,email,phone,line_1,line_2,country,state,city,zip_code"))->first();
            if (!empty($user->line_1)) {
                $address[] = $user->line_1 . "<br>";
            }
            if (!empty($user->line_2)) {
                $address[] = $user->line_2 . "<br>";
            }
            if (!empty($user->city)) {
                $address[] = $user->citydata->name;
            }
            if (!empty($user->state)) {
                $address[] = $user->statedata->name;
            }
            if (!empty($user->country)) {
                $address[] = $user->countrydata->name;
            }
            if (!empty($user->zip_code)) {
                $address[] = $user->zip_code . "<br>";
            }
            //dd($address);
            $grand_total = 0;
            $items = array();
            $data = array(
                'date' => date("Y-m-d H:i:s"),
                'user_id' => $request->customer,
                'user_name' => $user->user_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'tax' => 0,
                'address' => implode(",", $address),
                'payment_status' => $request->payment_status,
                'status' => $request->status,
                'note' => $request->note,
            );
            //print_r(); die;
            foreach ($carts as $cart) {

                $price = $cart['service_price'] ? $cart['service_price'] : 0;
                $price = $cart['sale_price'] ? $cart['sale_price'] : $price;
                $total = $price * $cart['quantity'];
                $grand_total += $total;
                $items[] = array(
                    'product_id' => $cart['id'],
                    'item_id' => $cart['item_id'],
                    'price' => $price,
                    'quantity' => $cart['quantity'],
                    'tax' => 0,
                    'total' => $total,
                );
            }
            if ($request->order_discount && $request->order_discount > 0) {
                $grand_total -= $request->order_discount;
                $data['order_discount'] = $request->order_discount;
            }

            if ($request->shipping && $request->shipping > 0) {
                $grand_total += $request->shipping;
                $data['shipping'] = $request->shipping;
            }
            $data['grand_total'] = $grand_total;
            if ($request->payment_status == "success") {
                $data['paid'] = $grand_total;
            }

            $order = Order::create($data);
            if (!empty($order)) {
                foreach ($items as $item) {
                    $item['order_id'] = $order->id;
                    OrderItems::create($item);
                }
                Order::where("id", $order->id)->update(['reference_no' => "ORD000{$order->id}", 'order_id' => "ORD000{$order->id}"]);
            }
            Session::put('cart', array());
            Session::flash('success', 'Order insert Successfully.......');
            return Redirect('admin/order')->with('message', 'Order insert Successfully...');
        } else {
            Session::flash('user_id', $request->customer);
            return  redirect()->back()->with('error', 'No items selected...');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($order)
    {
        $slug = Sentinel::getUser()->roles()->first()->slug;

        if ($slug == 'super_admin') {
            //echo "yes"; die;
            $data['order'] = Order::where('id', $order)->first();

            $data['items'] = OrderItems::where('order_items.order_id', $order)
                ->select(DB::raw("order_items.*,product_items.item_unit as unit, product_items.item_unit_value as unit_value,tbl_services.service_name"))
                ->leftJoin('product_items', 'order_items.item_id', '=', 'product_items.id')
                ->leftJoin('tbl_services', 'order_items.product_id', '=', 'tbl_services.id')
                ->get();
            $offer_list = array();

            $offer_list = DB::table('validate_offers')->where('validate_offers.order_id', $order)
                ->select(DB::raw("offer_products.*,if(tbl_services.image,concat('/uploads/service/',tbl_services.image),'') as image, tbl_services.service_name,tbl_services.unit,tbl_services.unit_value"))
                ->leftJoin('offer_products', 'validate_offers.offer_id', '=', 'offer_products.id')
                ->leftJoin('tbl_services', 'offer_products.product_id', '=', 'tbl_services.id')
                //->leftJoin('product_items', 'order_items.item_id', '=', 'product_items.id')
                ->get();
            $data['offer_list'] = $offer_list;
        } else {
            $data['offer_list'] = array();
            $users = Sentinel::getUser();
            $data['order'] = Order::where('orders.id', $order)->select(DB::raw("orders.id as id,orders.order_id as order_id,sum(order_items.total) as grand_total,orders.user_name,orders.email,orders.name,orders.address,orders.address2,orders.phone,orders.status,orders.payment_status,date"))
                ->whereRaw("tbl_services.add_by={$users->id}")
                ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
                ->leftJoin('tbl_services', 'order_items.product_id', '=', 'tbl_services.id')
                ->groupBy("orders.id")->first();
            // dd( $data['order']);
            $data['items'] = OrderItems::where('order_items.order_id', $order)
                ->select(DB::raw("order_items.*,product_items.item_unit as unit, product_items.item_unit_value as unit_value,tbl_services.service_name"))
                ->whereRaw("tbl_services.add_by={$users->id}")
                ->leftJoin('tbl_services', 'order_items.product_id', '=', 'tbl_services.id')
                ->leftJoin('product_items', 'order_items.item_id', '=', 'product_items.id')
                ->get();
        }
        $data['page_title'] = 'View Order';
        return view('admin/orders/view', $data);
    }
    public function print_invoice_list($order)
    {
        $slug = Sentinel::getUser()->roles()->first()->slug;

        if ($slug == 'super_admin') {
            $data['order'] = Order::where('id', $order)->first();
            $data['items'] = OrderItems::where('order_items.order_id', $order)
                ->select(DB::raw("order_items.*,product_items.item_unit as unit, product_items.item_unit_value as unit_value,tbl_services.service_name"))
                ->leftJoin('product_items', 'order_items.item_id', '=', 'product_items.id')
                ->leftJoin('tbl_services', 'order_items.product_id', '=', 'tbl_services.id')
                ->get();
            $offer_list = array();

            $offer_list = DB::table('validate_offers')->where('validate_offers.order_id', $order)
                ->select(DB::raw("offer_products.*,if(tbl_services.image,concat('/uploads/service/',tbl_services.image),'') as image, tbl_services.service_name,tbl_services.unit,tbl_services.unit_value"))
                ->leftJoin('offer_products', 'validate_offers.offer_id', '=', 'offer_products.id')
                ->leftJoin('tbl_services', 'offer_products.product_id', '=', 'tbl_services.id')
                ->get();
            $data['offer_list'] = $offer_list;
        } else {
            $users = Sentinel::getUser();
            $data['order'] = Order::where('orders.id', $order)->select(DB::raw("orders.id as id,orders.order_id as order_id,sum(order_items.total) as grand_total,orders.user_name,orders.email,orders.name,orders.address,orders.address2,orders.phone,orders.status,orders.payment_status,date"))
                ->whereRaw("tbl_services.add_by={$users->id}")
                ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
                ->leftJoin('tbl_services', 'order_items.product_id', '=', 'tbl_services.id')
                ->groupBy("orders.id")->first();
            // dd( $data['order']);
            $data['items'] = OrderItems::where('order_id', $order)
                ->whereRaw("tbl_services.add_by={$users->id}")
                ->leftJoin('tbl_services', 'order_items.product_id', '=', 'tbl_services.id')
                ->get();
        }
        $data['page_title'] = 'Print Order';
        return view('admin/orders/print_list', $data);
    }
    public function print_invoice($order)
    {
        $slug = Sentinel::getUser()->roles()->first()->slug;

        if ($slug == 'super_admin') {
            $data['order'] = Order::where('id', $order)->first();
            $data['items'] = OrderItems::where('order_items.order_id', $order)
                ->select(DB::raw("order_items.*,product_items.item_unit as unit, product_items.item_unit_value as unit_value,tbl_services.service_name"))
                ->leftJoin('product_items', 'order_items.item_id', '=', 'product_items.id')
                ->leftJoin('tbl_services', 'order_items.product_id', '=', 'tbl_services.id')
                ->get();
            $offer_list = array();

            $offer_list = DB::table('validate_offers')->where('validate_offers.order_id', $order)
                ->select(DB::raw("offer_products.*,if(tbl_services.image,concat('/uploads/service/',tbl_services.image),'') as image, tbl_services.service_name"))
                ->leftJoin('offer_products', 'validate_offers.offer_id', '=', 'offer_products.id')
                ->leftJoin('tbl_services', 'offer_products.product_id', '=', 'tbl_services.id')
                ->get();
            $data['offer_list'] = $offer_list;
        } else {
            $users = Sentinel::getUser();
            $data['order'] = Order::where('orders.id', $order)->select(DB::raw("orders.id as id,orders.order_id as order_id,sum(order_items.total) as grand_total,orders.user_name,orders.email,orders.name,orders.address,orders.address2,orders.phone,orders.status,orders.payment_status,date"))
                ->whereRaw("tbl_services.add_by={$users->id}")
                ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
                ->leftJoin('tbl_services', 'order_items.product_id', '=', 'tbl_services.id')
                ->groupBy("orders.id")->first();
            // dd( $data['order']);
            $data['items'] = OrderItems::where('order_id', $order)
                ->whereRaw("tbl_services.add_by={$users->id}")
                ->leftJoin('tbl_services', 'order_items.product_id', '=', 'tbl_services.id')
                ->get();
        }
        $data['page_title'] = 'View Order';
        return view('admin/orders/bill', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit($order)
    {

        // dd($order);

        $data['status'] = array(
            "pending" => "Pending",
            "ordered" => "Ordered",
            "processing" => "Processing",
            "delivered" => "Delivered",
            "cancelled" => "Cancelled",
            "shipped" => "Shipped",
            "return" => "Return",
            "return_complete" => "Return Complete",
        );

        $data['payment_status'] = array(
            "pending" => "Pending",
            "success" => "Success",
        );

        $data['order'] = Order::where('id', $order)->first();
        $data['page_title'] = 'Edit Order';

        //dd($data);

        return view('admin/orders/edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        if (!empty(Session::get('cart'))) {
            $address = array();
            $carts = Session::get('cart');
            $user = User::where("id", $request->customer)->select(DB::raw("id,concat(first_name,' ',last_name) as user_name,email,phone,line_1,line_2,country,state,city,zip_code"))->first();
            if (!empty($user->line_1)) {
                $address[] = $user->line_1 . "<br>";
            }
            if (!empty($user->line_2)) {
                $address[] = $user->line_2 . "<br>";
            }
            if (!empty($user->city)) {
                $address[] = $user->citydata->name;
            }
            if (!empty($user->state)) {
                $address[] = $user->statedata->name;
            }
            if (!empty($user->country)) {
                @$address[] = @$user->countrydata->name;
            }
            if (!empty($user->zip_code)) {
                $address[] = $user->zip_code . "<br>";
            }
            //dd($address);
            $grand_total = 0;
            $items = array();
            $data = array(
                'user_id' => $request->customer,
                'user_name' => $user->user_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'tax' => 0,
                'address' => implode(",", $address),
                'payment_status' => $request->payment_status,
                'status' => $request->status,
                'note' => $request->note,
            );
            foreach ($carts as $cart) {

                $price = $cart['service_price'] ? $cart['service_price'] : 0;
                $price = $cart['sale_price'] ? $cart['sale_price'] : $price;
                $total = $price * $cart['quantity'];
                $grand_total += $total;
                $items[] = array(
                    'product_id' => $cart['id'],
                    'item_id' => $cart['item_id'],
                    'price' => $price,
                    'quantity' => $cart['quantity'],
                    'tax' => 0,
                    'total' => $total,
                );
            }

            if ($request->order_discount && $request->order_discount > 0) {
                $grand_total -= $request->order_discount;
                $data['order_discount'] = $request->order_discount;
            }
            //dd($grand_total);
            if ($request->shipping && $request->shipping > 0) {
                $grand_total += $request->shipping;
                $data['shipping'] = $request->shipping;
            }
            $data['grand_total'] = $grand_total;
            if ($request->payment_status == "success") {
                $data['paid'] = $grand_total;
            }

            $order = Order::where("id", $request->id)->update($data);
            // if(!empty($order)){
            //     OrderItems::where('order_id',$request->id)->delete();
            //     foreach ($items as $item) {
            //         $item['order_id']=$request->id;
            //         OrderItems::create($item);
            //     }                
            // } 

            if ($request->status == 'pending') {
                $dataItem = array('status' => 'pending');
            } elseif ($request->status == 'ordered') {
                $dataItem = array('status' => 'ordered');
            } elseif ($request->status == 'processing') {
                $dataItem = array('status' => 'processing');
            } elseif ($request->status == 'delivered') {
                $dataItem = array('status' => 'delivered');
            } elseif ($request->status == 'cancelled') {
                $dataItem = array('status' => 'cancelled');
            } elseif ($request->status == 'shipped') {
                $dataItem = array('status' => 'shipped');
            } elseif ($request->status == 'return') {
                $dataItem = array('status' => 'return_pending');
            } elseif ($request->status == 'return_complete') {
                $dataItem = array('status' => 'return_complete');
            } else {
                $dataItem = array('status' => 'pending');
            }

            //dd($dataItem);

            $checkEvereyItemCancel = DB::table('order_items')
                ->where('order_id', $request->id)
                // ->where('status', '!=', 'cancelled')
                ->get();

            // dd($checkEvereyItemCancel);                        
            if (count($checkEvereyItemCancel) > 0) {

                DB::table('order_items')
                    ->where('order_id', $request->id)
                    ->where('status', '!=', 'cancelled')
                    ->update($dataItem);
            }

            Session::put('cart', array());
            Session::flash('success', 'Order updated Successfully....');
            return Redirect('admin/order/edit/' . $request->id)->with('message', 'Order updated Successfully...');
        } else {
            Session::flash('user_id', $request->customer);
            Session::flash('success', 'No items selected...');
            return  redirect()->back()->with('error', 'No items selected...');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy($order)
    {
        Order::where('id', $order)->delete();
        OrderItems::where('order_id', $order)->delete();
        Session::flash('success', 'Order deleted Successfully....');
        return Redirect('admin/order');
    }


    public function addTocart(Request $request)
    {
        //echo $request->item_id; die;

        $product = DB::table('tbl_services')
            ->where("tbl_services.id", $request->product_id)
            ->where("product_items.id", $request->item_id)
            ->select("tbl_services.id", "product_items.id as item_id", "tbl_services.service_name", "product_items.item_mrp_price as service_price", "product_items.item_unit as unit", "product_items.item_unit_value as unit_value", "product_items.item_price as sale_price", "tbl_services.image")
            ->leftJoin('product_items', 'tbl_services.id', '=', 'product_items.product_id')
            ->first();

        $cart = array();
        //dd($product);
        if (!empty($product)) {
            $products = array();
            //$product=$product->toArray();
            foreach ($product as $p_key => $pvalue) {
                $products[$p_key] = $pvalue;
            }
            $product = $products;
            $product_id = $product['id'] . $product['item_id'];

            if (count(array_values(Session::get('cart'))) > 0) {
                $cart = Session::get('cart');

                if (isset($cart[$product_id])) {
                    $product = $cart[$product_id];

                    $product['quantity'] = isset($request->quantity) ? $request->quantity : $product['quantity'] + 1;
                } else {

                    $product['quantity'] = 1;
                }
                $cart[$product_id] = $product;
                Session::put('cart', $cart);
            } else {
                $product['quantity'] = 1;
                //dd($product); 
                $cart[$product['id'] . $product['item_id']] = $product;
                Session::put('cart', $cart);
            }
        }
        $cart = Session::get('cart');
        $status = false;
        if (count(array_values($cart))) {
            $status = true;
        }
        return response()->json(['cart' => $cart, 'status' => $status]);
    }

    public function removeCart(Request $request)
    {
        if (count(array_values(Session::get('cart'))) > 0) {
            $product_id = $request->product_id;
            $cart = Session::get('cart');
            if (isset($cart[$product_id])) {
                unset($cart[$product_id]);
            }
            Session::put('cart', $cart);
        }
        $cart = Session::get('cart');
        $status = false;
        if (count(array_values($cart))) {
            $status = true;
        }
        return response()->json(['cart' => $cart, 'status' => $status]);
    }

    public function getOrderItems(Request $request)
    {

        $slug = Sentinel::getUser()->roles()->first()->slug;
        $users = Sentinel::getUser();

        if ($slug == 'vendor') {
            $items = OrderItems::where('order_id', $request->order_id)->where('vendor_id', $users->id)->get();
        } else {

            $items = OrderItems::where('order_id', $request->order_id)->get();
        }

        $cart = array();
        foreach ($items as $item) {

            $first_itmes = DB::table('product_items')->where('id', $item->item_id)->first();
            $vendor_de = DB::table('users')->where('id', $item->product->add_by)->first();

            $v_name = $vendor_de->first_name . ' ' . $vendor_de->last_name;

            $cart[$item->product->id . @$first_itmes->id] = array(
                'order_item_id' => $item->id,
                'order_item_status' => $item->status,
                'id' => $item->product->id,
                'vendor_id' => $item->product->add_by,
                'vendor_name' => @$v_name,
                'item_id' => @$first_itmes->id,
                'service_name' => $item->product->service_name,
                'service_price' => number_format((float)@$first_itmes->item_mrp_price, 2, '.', ''),
                'image' => $item->product->image,
                'sale_price' => number_format((float)@$first_itmes->item_price, 2, '.', ''),
                'quantity' => $item->quantity,
                // 'quantity'=>number_format((float)$item->quantity, 2, '.', ''),
                'unit' => @$first_itmes->item_unit,
                'unit_value' => @$first_itmes->item_unit_value,
            );
        }

        Session::put('cart', $cart);

        $cart = Session::get('cart');
        $status = false;
        if (count(array_values($cart))) {
            $status = true;
        }
        return response()->json(['cart' => $cart, 'status' => $status]);
    }

    function view_pdf()
    {
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<h1>Test</h1>');
        return $pdf->stream();
    }
    function print_invoice_pdf($order)
    {
        $slug = Sentinel::getUser()->roles()->first()->slug;

        if ($slug == 'super_admin') {
            $data['order'] = Order::where('id', $order)->first();
            $data['items'] = OrderItems::where('order_items.order_id', $order)
                ->select(DB::raw("order_items.*,product_items.item_unit as unit, product_items.item_unit_value as unit_value,tbl_services.service_name"))
                ->leftJoin('product_items', 'order_items.item_id', '=', 'product_items.id')
                ->leftJoin('tbl_services', 'order_items.product_id', '=', 'tbl_services.id')
                ->get();
            $offer_list = array();

            $offer_list = DB::table('validate_offers')->where('validate_offers.order_id', $order)
                ->select(DB::raw("offer_products.*,if(tbl_services.image,concat('/uploads/service/',tbl_services.image),'') as image, tbl_services.service_name"))
                ->leftJoin('offer_products', 'validate_offers.offer_id', '=', 'offer_products.id')
                ->leftJoin('tbl_services', 'offer_products.product_id', '=', 'tbl_services.id')
                ->get();
            $data['offer_list'] = $offer_list;
        } else {
            $users = Sentinel::getUser();
            $data['order'] = Order::where('orders.id', $order)->select(DB::raw("orders.id as id,orders.order_id as order_id,sum(order_items.total) as grand_total,orders.user_name,orders.email,orders.name,orders.address,orders.address2,orders.phone,orders.status,orders.payment_status,date"))
                ->whereRaw("tbl_services.add_by={$users->id}")
                ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
                ->leftJoin('tbl_services', 'order_items.product_id', '=', 'tbl_services.id')
                ->groupBy("orders.id")->first();
            // dd( $data['order']);
            $data['items'] = OrderItems::where('order_id', $order)
                ->whereRaw("tbl_services.add_by={$users->id}")
                ->leftJoin('tbl_services', 'order_items.product_id', '=', 'tbl_services.id')
                ->get();
        }
        $data['page_title'] = 'View Order';
        //$html= view('admin/orders/view',$data);
        $html =  view('admin/orders/pdf', $data)->render();
        $pdf = App::make('dompdf.wrapper');

        $pdf->loadHTML($html);
        return $pdf->stream();
    }
    public function pdfhtml($order)
    {
        $slug = Sentinel::getUser()->roles()->first()->slug;

        if ($slug == 'super_admin') {
            $data['order'] = Order::where('id', $order)->first();
            $data['items'] =  OrderItems::where('order_items.order_id', $order)
                ->select(DB::raw("order_items.*,product_items.item_unit as unit, product_items.item_unit_value as unit_value,tbl_services.service_name"))
                ->leftJoin('product_items', 'order_items.item_id', '=', 'product_items.id')
                ->leftJoin('tbl_services', 'order_items.product_id', '=', 'tbl_services.id')
                ->get();
            $offer_list = array();

            $offer_list = DB::table('validate_offers')->where('validate_offers.order_id', $order)
                ->select(DB::raw("offer_products.*,if(tbl_services.image,concat('/uploads/service/',tbl_services.image),'') as image, tbl_services.service_name"))
                ->leftJoin('offer_products', 'validate_offers.offer_id', '=', 'offer_products.id')
                ->leftJoin('tbl_services', 'offer_products.product_id', '=', 'tbl_services.id')
                ->get();
            $data['offer_list'] = $offer_list;
        } else {
            $users = Sentinel::getUser();
            $data['order'] = Order::where('orders.id', $order)->select(DB::raw("orders.id as id,orders.order_id as order_id,sum(order_items.total) as grand_total,orders.user_name,orders.email,orders.name,orders.address,orders.address2,orders.phone,orders.status,orders.payment_status,date"))
                ->whereRaw("tbl_services.add_by={$users->id}")
                ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
                ->leftJoin('tbl_services', 'order_items.product_id', '=', 'tbl_services.id')
                ->groupBy("orders.id")->first();
            // dd( $data['order']);
            $data['items'] = OrderItems::where('order_id', $order)
                ->whereRaw("tbl_services.add_by={$users->id}")
                ->leftJoin('tbl_services', 'order_items.product_id', '=', 'tbl_services.id')
                ->get();
        }
        $data['page_title'] = 'View Order';
        //$html= view('admin/orders/view',$data);
        echo  view('admin/orders/view', $data)->render();
    }
    function get_order_item($id)
    {
        $data =  OrderItem::where('order_items.order_id', $id)
            ->select(DB::raw("order_items.*,product_items.item_unit as unit, product_items.item_unit_value as unit_value,tbl_services.service_name"))
            ->leftJoin('product_items', 'order_items.item_id', '=', 'product_items.id')
            ->leftJoin('tbl_services', 'order_items.product_id', '=', 'tbl_services.id')
            ->get();
        return $data;
    }
    function get_offer_list($id)
    {
        $offer_list = array();

        $offer_list = DB::table('validate_offers')->where('validate_offers.order_id', $id)
            ->select(DB::raw("offer_products.*,if(tbl_services.image,concat('/uploads/service/',tbl_services.image),'') as image, tbl_services.service_name,tbl_services.unit,tbl_services.unit_value"))
            ->leftJoin('offer_products', 'validate_offers.offer_id', '=', 'offer_products.id')
            ->leftJoin('tbl_services', 'offer_products.product_id', '=', 'tbl_services.id')
            ->get();
        return $offer_list;
    }
    function print_invoice_multiple($id)
    {
        $array = explode("-", $id);
        $slug = Sentinel::getUser()->roles()->first()->slug;

        if ($slug == 'super_admin') {
            $order_data = array();
            $order_data = Order::whereIn('id', $array)->get();

            foreach ($order_data as $key => $value) {
                $order_data[$key]->order_item = $this->get_order_item($value->id);
                $order_data[$key]->offer_item = $this->get_offer_list($value->id);
            }

            $data['order_data'] = $order_data;
        } else {
            $users = Sentinel::getUser();
            $order_data = Order::whereIn('orders.id', $array)->select(DB::raw("orders.id as id,orders.order_id as order_id,sum(order_items.total) as grand_total,orders.user_name,orders.email,orders.name,orders.address,orders.address2,orders.phone,orders.status,orders.payment_status,date"))
                ->whereRaw("tbl_services.add_by={$users->id}")
                ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
                ->leftJoin('tbl_services', 'order_items.product_id', '=', 'tbl_services.id')
                ->groupBy("orders.id")->get();
            foreach ($order_data as $key => $value) {
                $order_data[$key]->order_item = $this->get_order_item($value->id);
                $order_data[$key]->offer_item = $this->get_offer_list($value->id);
            }

            $data['order_data'] = $order_data;
        }
        $data['page_title'] = 'Print Orders';
        return view('admin/orders/multiple_print', $data);
    }
    function print_invoice_items($id)
    {
        $array = explode("-", $id);
        $data['order_item'] = $this->get_order_item_array($array);
        $data['offer_item'] = $this->get_offer_list_array($array);
        $data['page_title'] = 'Print Orders';
        return view('admin/orders/totalitem', $data);
    }
    function get_order_item_array($id)
    {
        $data = OrderItems::where('order_items.order_id', $id)
            ->select(DB::raw("order_items.*,product_items.item_unit as unit, product_items.item_unit_value as unit_value,tbl_services.service_name"))
            ->leftJoin('product_items', 'order_items.item_id', '=', 'product_items.id')
            ->leftJoin('tbl_services', 'order_items.product_id', '=', 'tbl_services.id')
            ->get();
        return $data;
    }
    function get_offer_list_array($id)
    {
        $offer_list = array();
        $offer_list = DB::table('validate_offers')->whereIn('validate_offers.order_id', $id)
            ->select(DB::raw("offer_products.*,if(tbl_services.image,concat('/uploads/service/',tbl_services.image),'') as image, tbl_services.service_name,tbl_services.unit,tbl_services.unit_value"))
            ->leftJoin('offer_products', 'validate_offers.offer_id', '=', 'offer_products.id')
            ->leftJoin('tbl_services', 'offer_products.product_id', '=', 'tbl_services.id')
            ->get();
        return $offer_list;
    }
}
