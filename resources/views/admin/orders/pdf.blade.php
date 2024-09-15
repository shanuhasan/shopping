<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Bill</title>

    <style>
        .invoice-box {
            width: 300px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        /* table
    {
        text-align: center !important;
    }*/
        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media only screen and (max-width: 768px) {
            .invoice-box {
                width: 100% !important;
            }

            p {
                margin: 0px;
            }
        }
    </style>
    <style>
        /* @font-face {
    src: url("/front/assets/Krutidev_010.TTF") format('truetype');
    font-family: "Kruti Dev";
  }*/
        body {
            font-family: Kruti Dev !important;
        }
    </style>
</head>

<body>

    <div class="invoice-box">
        <div class="box">
            <h1 style="font-size:20px;"><?= @$order->order_id ?></h1>
            <h3><a
                    href="{{ url('/') }}"><strong>{{ isset(settings()['site_name']) ? settings()['site_name'] : null }}</strong></a>
            </h3>
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td style="width:100px">
                        <div style="font-size:14px">

                            <b>Address:-</b>
                            From
                            <p>

                                {!! isset(settings()['address']) ? settings()['address'] : null !!}
                                Phone: 9784133794, 9784243794<br>
                                Email: {{ isset(settings()['email']) ? settings()['email'] : null }}
                            </p>

                        </div>
                    </td>
                    <td>
                        <div style="font-size:14px;text-align: left !important;">
                            <b>Customer Address:-</b>
                            To
                            <p>
                                <strong>{{ $order->user_name }}</strong>
                                {!! $order->address !!}
                                Phone: {{ $order->phone }}<br>
                                Email: {{ $order->email }}<br>
                                Name: {{ $order->name }}<br>
                                Address: {{ $order->address }}

                            </p>
                        </div>
                    </td>
                    <td style="width:150px">
                        <div style="font-size:14px; text-align: left !important; ">
                            <b>Invoice Details:-</b>

                            <p>
                                <b>Order ID:</b> {{ $order->order_id }}<br>
                                <b>Order Status:</b> {{ ucfirst($order->status) }}<br>
                                <b>Payment Status:</b> {{ ucfirst($order->payment_status) }}<br>
                                <b>Payment Method:</b> {{ ucfirst($order->payment_method) }}<br>
                                <b>Date time:</b> {{ ucfirst($order->created_at) }}
                            </p>
                        </div>
                    </td>
                </tr>
            </table>

            <table class="table table-striped" style="font-size:13px; text-align: center !important;">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quatity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody style="font-size:12px;">
                    @php
                        $i = 1;
                        $subtotal = 0;
                    @endphp
                    @forelse ($items as $item)
                        @php
                            $subtotal += $item->total;
                        @endphp
                        <tr>
                            <td>{{ $i }}</td>
                            <td><?php echo explode('-', $item->service_name)[0]; ?></td>
                            <td>INR {{ number_format((float) $item->price, 2, '.', '') }}</td>
                            <td>{{ number_format((float) $item->quantity, 2, '.', '') }}
                                {{ $item->unit_value }}{{ $item->unit }}</td>
                            <td>INR {{ number_format((float) $item->total, 2, '.', '') }}</td>
                        </tr>
                        @php
                            $i++;
                        @endphp
                    @empty
                    @endforelse
                </tbody>
            </table>
            <hr>
            <table class="table" style="font-size:14px">
                <tr>
                    <th style="width:50%">Subtotal:</th>
                    <td>INR {{ number_format((float) $subtotal, 2, '.', '') }}</td>
                </tr>
                <!--tr>
                        <th>Tax (9.3%)</th>
                        <td>0</td>
                      </tr-->
                <tr>
                    <th>Shipping:</th>
                    <td>INR {{ number_format((float) $order->shipping, 2, '.', '') }}</td>
                </tr>
                <tr>
                    <th>Discount:</th>
                    <td>{{ number_format((float) $order->order_discount ? $order->order_discount : 0.0, 2, '.', '') }}</td>
                </tr>
                <tr>
                    <th>Total:</th>
                    <td>INR {{ number_format((float) $subtotal, 2, '.', '') }}</td>
                </tr>
            </table>
            <!--<b style="float: right;">Order Total:-Rs320</b>-->
            <hr>
        </div>
    </div>
</body>

</html>
