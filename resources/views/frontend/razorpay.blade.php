@extends('layouts.app')
@section('content')
    <section class="breadcrumb-bg">
        <div class="theme-container container ">
            <div class="site-breadcumb white-clr">
                <h2 class="section-title">
                    <strong class="clr-txt">{{ isset($settings['site_name']) ? $settings['site_name'] : null }} </strong>
                    <span class="light-font"> </span>
                </h2>
                <ol class="breadcrumb breadcrumb-menubar">
                    <li> <a href="{{ url('/') }}"> Home </a> Checkout </li>
                </ol>
            </div>
        </div>
    </section>
    <form action="http://marketingchord.com/anb/success_payment" method="POST" id="razorpay"
        style="text-align: center;padding: 3%;">

        @csrf
        <script src="https://checkout.razorpay.com/v1/checkout.js" data-key="<?= $payment_info['api_key'] ?>"
            data-amount="<?= $payment_info['price'] ?>00" data-currency="INR" data-buttontext="Pay with Razorpay"
            data-name="ANB" data-description="ANB transaction" data-image="{{ url('uploads/logo/1599827425.png') }}"
            data-prefill.name="<?= $payment_info['name'] ?>" data-prefill.email="<?= $payment_info['email'] ?>"
            data-prefill.contact="<?= $payment_info['phone'] ?>" data-theme.color="#000080"></script>
        <input type="hidden" custom="Hidden Element" value="<?= $payment_info['order_id'] ?>" name="hidden">
    </form>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>
    <script type="text/javascript">
        setTimeout(function() {
            $(".razorpay-payment-button").trigger('click');
            //alert('asdfd');
        }, 10);
    </script>
@stop
