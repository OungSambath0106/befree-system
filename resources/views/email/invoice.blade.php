<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Phoum Chaufea')</title>

    <style>
        @font-face {
            font-family: 'Hanuman';
            src: url("{{ asset('fonts/Hanuman-Regular.ttf') }}") format("truetype");
        }
        body{
            font-family: 'Hanuman',sans-serif;
        }
        th {
            text-align: center !important;
            padding: 8px 8px 3px 8px;
        }
        td {
            text-align: center !important;
            padding: 8px 8px 3px 8px;
        }
        tr:nth-child(even) {}
        .image-logo img {
            width: 30%;
        }
        .bg-processing{
            color: orange;
            padding: 4px 8px;
            text-align: center;
        }
        .bg-comfirmed{
            color: green;
            padding: 4px 8px;
            text-align: center;
        }
        .bg-cancel{
            color: red;
            padding: 4px 8px;
            text-align: center;
        }
        .text-right{
            text-align: right;
        }
        .text-left{
            text-align: left;
        }
        .container-invoice{
            width: 100%;
            padding: 5px;
        }
    </style>
</head>

<body>
    <div class="container-invoice">
        <div class="image-logo "style="text-align: center;
        width: 100%">
            @php

                $setting = \App\Models\BusinessSetting::first();
                $data['company_name'] = @$setting->where('type', 'company_name')->first()->value??'Phoum Chaufea Resort';
                $data['email'] = @$setting->where('type', 'email')->first()->value??'';
                $data['company_address'] = @$setting->where('type', 'company_address')->first()->value??'';
                $data['web_header_logo'] = @$setting->where('type', 'web_header_logo')->first()->value??'';
                $url = public_path() . '/uploads/business_settings/' . $data['web_header_logo'];
            @endphp
            <img style="width: auto; height: 150px;" src="{{ $url }}" alt="">
            <h4 style="text-transform: uppercase;">{{ $data['company_name'] }}</h4>
            <h5 style="margin-top: -15px;">{{ $data['email'] }}</h5>
            {{-- <h5 style="margin-top: -15px;">{{ $data['company_address'] }}</h5> --}}
        </div>
        <table style="font-family: 'Hanuman', sans-serif;border-collapse: collapse;width: 100%;">
            <tr style="padding: 10px;">
                <th></th>
                <th></th>
            </tr>
            <tr style="
            font-size:10px;">
                <td class="text-left"><h5>Transaction No.{{ $transaction->invoice_no }}</h5></td>
                <td class="text-right"><h5>Customer Name : {{ $customer->first_name }} {{ $customer->last_name }}</h5></td>

            </tr>
            <tr style="font-size:10px;">
                <td class="text-left"><h5>Booking Date : {{ \Carbon\Carbon::parse($transaction->created_at)->format('d-M-Y') }}</h5>
                </td>
                <td class="text-right"><h5>Email : {{ $customer->email }}</h5></td>
            </tr>
            <tr style="font-size:10px;">
                <td class="text-left">
                <h5>
                    Payment Status :
                    @if ($transaction->status == 'processing' )
                        <span class="bg-processing">processing</span>
                    @elseif ($transaction->status == 'confirmed')
                        <span class="bg-comfirmed">Confirmed</span>
                    @else
                        <span class="bg-cancel">Cancel</span>
                    @endif
                </h5>
                </td>
                <td class="text-right"><h5>Phone : {{ $customer->phone }}</h5></td>
            </tr>
            <tr style="font-size:10px;">
                <td class="text-left"><h5>Payment Method : <span style="text-transform: uppercase;">{{ $transaction->payment_method }}</span></h5></td>
                <td class="text-right"><h5>Check in : {{ \Carbon\Carbon::parse($transaction->checkin_date)->format('d-M-Y') }}</h5></td>
            </tr>
            <tr style="padding-bottom: 10px;font-size:10px;">
                <td></td>
                <td class="text-right"><h5>Check out : {{ \Carbon\Carbon::parse($transaction->checkout_date)->format('d-M-Y') }}</h5></td>
            </tr>
        </table>
        <div class="receipt">
            <p
                style="font-size: 20px;font-weight: 700;color: #CF6737; border-bottom: #CF6737 3px solid;margin-bottom:10px;margin-top:10px;">
                Receipt</p>
        </div>
        <table style="font-family: 'Hanuman', sans-serif;
        border-collapse: collapse;
        width: 100%;">
            <tr style="background-color: #f3c7a4;">
                <th>HOME STAY</th>
                <th>PACKAGE</th>
                <th>TOTAL NIGHT</th>
                <th>PRICE/NIGHT</th>
                <th>TOTAL</th>
            </tr>
            <tr  style="font-size:10px;">
                <td>{{ @$transaction->room->title }}</td>
                <td>
                    @if (@$transaction->ratePlan->type == 'package')
                        {{ @$transaction->ratePlan->title }}
                    @else

                    @endif
                </td>
                <td>{{ $transaction->night_stay }}</td>
                <td>{{ max($transaction->price_each_date) }} USD</td>
                <td>{{ $transaction->final_total }} USD</td>
            </tr>

        </table>
        <div style="border-top: 1px dotted gray;"></div>

    </div>
</body>

</html>
