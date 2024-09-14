@extends('layouts.app')
@section('content')

    <?php $shopingCart = Session::get('shoping_charge_cart'); ?>

    <section class="breadcrumb-area">
        <div class="container">
            <div class="breadcrumb-content">
                <ul>
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li>Buy now</li>
                </ul>
            </div>
        </div>
    </section>
    <section class="py-4 py-md-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="track">
                        <div class="step active">
                            <span class="icon"></span>
                            <span class="text">Buy now</span>
                        </div>
                        <div class="step active">
                            <span class="icon"></span>
                            <span class="text">Shipping Address</span>
                        </div>
                        <div class="step active">
                            <span class="icon"></span>
                            <span class="text">processes</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-4 py-md-1">
        <div class="container">
            <div class="checkout-wrapper">
                <span>Delivery Address</span><br><br>

                <form id="paycheckout1" action="{{ url('/order_place_buynow') }}" method="post">
                    @csrf

                    <div class="row">

                        @if (Auth::check())

                            <div class="col-lg-8 left">

                                @if ($errors->has('delivery_address'))
                                    <div class="alert alert-danger">
                                        <ul>
                                            <li>{{ $errors->first('delivery_address') }}</li>
                                        </ul>
                                    </div>
                                @endif

                                @if ($user_address)
                                    @foreach ($user_address as $userAddress)
                                        <div>
                                            <input type="radio" name="delivery_address" value="{{ @$userAddress->id }}"
                                                checked>&nbsp;&nbsp;
                                            {{ @$userAddress->name }}<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            {{ @$userAddress->email }}&nbsp; |
                                            {{ @$userAddress->phone }}<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            {{ @$userAddress->address }}
                                            {{ @$userAddress->pincode }}
                                        </div><br>
                                    @endforeach
                                @endif

                                <div class="alert mb-4">

                                    @if (count($user_address) > 0)
                                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#add_new_address"
                                            class="add_new_address">Add New Address</a><br><br>
                                        <input type="hidden" name="new_address" id="set_address" value="old_address">
                                    @else
                                        <input type="hidden" name="new_address" value="new_address">
                                    @endif

                                    <div id="add_new_address" class="collapse {{ count($user_address) > 0 ? '' : 'show' }}">

                                        <div class="row">
                                            <div class="col-md-6 userdetails">
                                                <div class="form-group">
                                                    <label>Name *</label>
                                                    <input type="text" class="form-control" name="name"
                                                        value="{{ old('name') }}">

                                                    @if ($errors->has('name'))
                                                        <span class="text-danger"> {{ $errors->first('name') }} </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6 userdetails">
                                                <div class="form-group">
                                                    <label>Email *</label>
                                                    <input type="email" class="form-control" name="email"
                                                        value="{{ old('email') }}">

                                                    @if ($errors->has('email'))
                                                        <span class="text-danger"> {{ $errors->first('email') }} </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6 userdetails">
                                                <div class="form-group">
                                                    <label>Phone * </label>
                                                    <input type="number" class="form-control" name="phone"
                                                        value="{{ old('phone') }}">

                                                    @if ($errors->has('phone'))
                                                        <span class="text-danger"> {{ $errors->first('phone') }} </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6 userdetails">
                                                <div class="form-group">
                                                    <label>Alternate Phone</label>
                                                    <input type="number" class="form-control" name="phone2"
                                                        value="{{ old('phone2') }}">
                                                </div>
                                            </div>


                                            <div class="col-md-4 userdetails">
                                                <div class="form-group">
                                                    <label>Country *</label>
                                                    <select class="form-control" name="country">
                                                        <option value="india">India</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4 userdetails">
                                                <div class="form-group">
                                                    <label>State *</label>
                                                    <select class="form-control" name="state" id="state">
                                                        <option value=""> --select state--</option>
                                                        @foreach ($states as $stateData)
                                                            <option value="{{ $stateData->id }}"> {{ $stateData->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    @if ($errors->has('state'))
                                                        <span class="text-danger"> {{ $errors->first('state') }} </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-4 userdetails">
                                                <div class="form-group">
                                                    <label>City *</label>
                                                    <select class="form-control show_city" name="city">
                                                        <option value=""> --select city--</option>
                                                    </select>

                                                    @if ($errors->has('city'))
                                                        <span class="text-danger"> {{ $errors->first('city') }} </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6 userdetails">
                                                <div class="form-group">
                                                    <label>Pincode * </label>
                                                    <input type="number" class="form-control" name="pincode"
                                                        value="{{ old('pincode') }}">

                                                    @if ($errors->has('pincode'))
                                                        <span class="text-danger"> {{ $errors->first('pincode') }} </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6 userdetails">
                                                <div class="form-group">
                                                    <label>Address * </label>
                                                    <input type="text" class="form-control" name="address"
                                                        value="{{ old('address') }}">

                                                    @if ($errors->has('address'))
                                                        <span class="text-danger"> {{ $errors->first('address') }} </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-12 userdetails">
                                                <div class="form-group">
                                                    <label>Address 2</label>
                                                    <input type="text" class="form-control" name="address2"
                                                        placeholder="optional" value="{{ old('address2') }}">
                                                </div>
                                            </div>

                                        </div><!--row-->

                                    </div><br>
                                </div>
                            </div>
                        @else
                            <script>
                                window.location.assign("/login")
                            </script>
                        @endif


                        <div class="col-lg-4 mt-4 mt-lg-0">

                            <?php
                            $total = [];
                            
                            ?>
                            <div class="media mb-3">
                                <div class="media-thumb">
                                    <img src="<?php echo asset('/'); ?>uploads/items/{{ @$item->image }}" alt=""
                                        style="width: 70px;">
                                </div>
                                <div class="media-body ml-3">
                                    <span> {{ @$product->service_name }} </span>
                                    <p class="fw-500">Qty : 1 - ₹ {{ @$item->item_price }}</p>
                                </div>
                            </div>


                            <h2>Price Details ( 1 Item)</h2>

                            <input type="hidden" name="product_id" value="{{ @$product->id }}">
                            <input type="hidden" name="item_id" value="{{ @$item->id }}">

                            <table class="table table-borderless cart-table">
                                <tbody>
                                    <tr>
                                        <!--<td>Qty</td>-->
                                        <td class="text-right"><span><input type="hidden" name="qty" value="1"
                                                    maxlength="4" style="width: 55px;text-align: center;"></span></td>
                                    </tr>

                                    <tr>
                                        <td>Sub total</td>
                                        <td class="text-right"><span>₹ {{ @$item->item_price }} </span>
                                            <input type="hidden" name="base_amount_value"
                                                value="{{ @$item->item_price }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>GST(18%) </td>
                                        <td class="text-right">
                                            <span>₹ <?php echo $gstAmount = $item->item_price - round(($item->item_price * 100) / 118); ?></span>
                                            <input type="hidden" name="gst_price" value="<?php echo $gstAmount; ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Delivery Charges</td>
                                        <td class="text-right"><span>₹ 50 </span>
                                            <input type="hidden" name="shipping" value="50">
                                        </td>
                                    </tr>

                                </tbody>
                                <tbody class="total">
                                    <tr>
                                        <th class="text-uppercase">Total</th>
                                        <th class="text-right">
                                            <input type="hidden" name="grand_total"
                                                value="{{ @$item->item_price + 50 }}">
                                            <span>₹ {{ @$item->item_price + 50 }}</span>

                                        </th>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="continue-shopping">
                                <table class="table subtable" width="100%">
                                    <th><strong>Cash on Delivery:</strong></th>
                                    <td><input type="radio" value="cod" name="payment_method" required></td>
                                    </tr>
                                    <tr>
                                        <th><strong>Online Payment:</strong></th>
                                        <td><input type="radio" value="online" name="payment_method" required></td>
                                    </tr>
                                </table>
                            </div>
                            @if (Auth::check())
                                <button type="submit" class="btn btn-block btn-primary">Continue</button>
                            @endif
                            <!--<a href="#" class="btn btn-block btn-primary">Continue</a>-->
                        </div><!-- col som-4-->
                    </div><!-- col row-->

                </form>
            </div>
        </div>
    </section>
@stop


@section('extra_script')
    <script>
        function setAccoudion() {
            var hasNewAddress = localStorage.getItem("newAddress");
            if (hasNewAddress == 'show') {
                document.getElementById("set_address").value = 'new_address';
                document.getElementById("add_new_address").classList.add('show');
            }
        }

        $(document).ready(function() {
            $(".add_new_address").click(function() {
                var hasNewAddress = localStorage.getItem("newAddress");
                if (hasNewAddress == 'show') {
                    document.getElementById("set_address").value = 'old_address';
                    localStorage.removeItem("newAddress");
                } else {
                    document.getElementById("set_address").value = 'new_address';
                    localStorage.setItem("newAddress", "show");
                }
            });
        });

        $(document).ready(function() {
            setAccoudion();
        });
    </script>

@stop
