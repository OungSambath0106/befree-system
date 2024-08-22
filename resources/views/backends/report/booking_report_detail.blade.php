@extends('backends.master')

@push('css')
    <style>
        tr.tr_summary td {
            border-top: none;
            padding-block: 3px
        }
        /* tr.tr_summary td:first-child {
            border-top: none;
        } */


        @media print {
            .content-header {
                /* display: none; */
            }
            .main-footer {
                display: none;
            }

            /* Set paper size and orientation for portrait A4 */
            @page {
                size: A4 portrait;
                /* margin: 20px; */
            }
        }
    </style>
@endpush
@section('contents')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>{{ __('Booking Detail') }}</h3>
            </div>
            <div class="col-sm-6" style="text-align: right">
                <button onclick="printInvoice()" class="btn btn-primary">
                    <i class="fa fa-print"></i>
                    {{ __('Print') }}
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body invoice-print">
                        <div class="row d-none d-print-flex">
                            <div class="col-12 mt-3 text-center">
                                {{-- @dd(session('business')) --}}
                                @if(session()->has('business_logo'))
                                    <img src="{{ asset('uploads/business_settings/' . session('business_logo')) }}" alt="" style="object-fit: contain;margin-left: 0; height: 100px;max-height: 100px; max-width: 150px;">
                                @endif

                            </div>
                            <div class="col-12 mt-3 mb-5">
                                <h5 class="text-center">{{ session('company_name') }}</h5>
                            </div>
                        </div>
                        <div class="row justify-content-between">
                            <div class="col-6">
                                <h6 class="mb-3">
                                    <b>{{ __('Invoice ID') }} : </b>
                                    <span class="">#{{ $transaction->invoice_no }}</span>
                                </h6>
                                @php
                                    $payment_method = null;
                                    if ($transaction->payment_method) {
                                        $payment_method = str_replace('_', ' ', $transaction->payment_method);
                                    }
                                @endphp
                                <h6>
                                    <b>{{ __('Payment Method') }} : </b>
                                    <span class="text-capitalize">{{ $payment_method ?? 'N/A' }}</span>
                                </h6>
                            </div>
                            <div class="col-6 text-right">
                                <h6 class="mb-3">
                                    <b>{{ __('Booking Date') }} : </b>
                                    <span class="">{{ date('d/m/Y', strtotime($transaction->created_at)) }}</span>
                                </h6>
                                <h6>
                                    <b>{{ __('Status') }} : </b>
                                    <span class="badge badge-pill
                                    @if ($transaction->status == 'confirmed')
                                        bg-success
                                    @elseif ($transaction->status == 'processing')
                                        bg-info
                                    @else
                                        bg-danger
                                    @endif
                                    text-capitalize">{{ $transaction->status }}</span>
                                </h6>
                            </div>
                        </div>
                        <div class="row justify-content-between mt-3 d-none d-print-flex">
                            <div class="col-6">
                                <p class="mb-3">
                                    <b>{{ __('Customer Name') }} : </b>
                                    <span class="">{{ $transaction->guest_info['first_name'] . ' ' . $transaction->guest_info['last_name'] }}</span>
                                </p>
                                <p class="mb-3">
                                    <b>{{ __('Email') }} : </b>
                                    <span class="">{{ $transaction->guest_info['email'] }}</span>
                                </p>
                            </div>
                            <div class="col-6 text-right">
                                <p class="mb-3">
                                    <b>{{ __('Phone') }} : </b>
                                    <span class="">{{ $transaction->guest_info['full_mobile'] }}</span>
                                </p>
                            </div>
                        </div>

                        <div class=" table-wrapper my-3">
                            <table class="table">
                                <thead>
                                    <tr style="background-color: #CACACA">
                                        <th>{{ __('Homestay') }}</th>
                                        <th>{{ __('Package') }}</th>
                                        <th>{{ __('Total Night') }}</th>
                                        {{-- <th>{{ __('Pice/Night') }}</th> --}}
                                        <th class="text-center">{{ __('File Total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @foreach ($transaction as $line) --}}
                                        <tr>
                                            <td>{{ @$transaction->room->title }}</td>
                                            <td>{{ @$transaction->ratePlan->type == 'package' ? @$transaction->ratePlan->title : 'No Extra' }}</td>
                                            <td>{{ @$transaction->night_stay }}</td>
                                            {{-- <td>$ {{ max($transaction->price_each_date) }}</td> --}}
                                            <td class="text-center">$ {{ $transaction->final_total }}</td>
                                        </tr>
                                    {{-- @endforeach --}}
                                    {{-- <tr class="">
                                        <td colspan="4">
                                            <h6 class="text-right">{{ __('Subtotal') }} :</h6>
                                        </td>
                                        <td colspan="1" class="text-right">
                                            <h6 class="">$ {{ $transaction->sub_total }}</h6>
                                        </td>
                                    </tr>
                                    <tr class="tr_summary">
                                        <td colspan="4">
                                            <h6 class="text-right">{{ __('Discount') }} :</h6>
                                        </td>
                                        <td colspan="1" class="text-right">
                                            <h6 class="">$ {{ $transaction->discount }}</h6>
                                        </td>
                                    </tr>
                                    <tr class="tr_summary">
                                        <td colspan="4">
                                            <h6 class="text-right">{{ __('Tax') }} :</h6>
                                        </td>
                                        <td colspan="1" class="text-right">
                                            <h6 class="">$ {{ $transaction->tax }}</h6>
                                        </td>
                                    </tr>
                                    <tr class="tr_summary">
                                        <td colspan="4" >
                                            <h5 class="text-right">{{ __('Final Total') }} :</h5>
                                        </td>
                                        <td colspan="1" class="text-right">
                                            <h5 class="">$ {{ $transaction->final_total }}</h5>
                                        </td>
                                    </tr> --}}
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
            </div>

            <div class="col-md-3 d-print-none">
                <div class="card">
                    <div class="card-header">
                        <h6 class=" header-text">{{ __('Customer Info') }}</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">
                            <b>{{ __('Name') }} : </b>
                            <span class="">{{ $transaction->guest_info['first_name'] . ' ' . $transaction->guest_info['last_name'] }}</span>
                        </p>
                        <p class="mb-3">
                            <b>{{ __('Email') }} : </b>
                            <span class="">{{ $transaction->guest_info['email'] }}</span>
                        </p>
                        <p class="mb-3">
                            <b>{{ __('Phone') }} : </b>
                            <span class="">{{ $transaction->guest_info['full_mobile'] }}</span>
                        </p>
                        {{-- <p class="mb-3">
                            <b>{{ __('Company') }} : </b>
                            <span class="">{{ $transaction->guest_info['company'] }}</span>
                        </p> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade modal_form" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

@endsection
@push('js')
<script>
    function printInvoice() {
        window.print();
    }
</script>
@endpush
