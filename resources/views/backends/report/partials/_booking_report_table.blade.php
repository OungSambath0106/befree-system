<div class="card-body p-0 table-wrapper">
    <table class="table">
        <thead class="text-uppercase">
            <tr>
                <th >#</th>
                <th class="">{{ __('Invoice No.') }}</th>
                <th>{{ __('Customer') }}</th>
                <th>{{ __('Room') }}</th>
                <th>{{ __('Rate Plan') }}</th>
                <th>{{ __('Total night') }}</th>
                <th>{{ __('Final Total') }}</th>
                <th>{{ __('Payment Method') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $transaction->invoice_no }}</td>
                    <td>{{ $transaction->guest_info['first_name'] }} {{ $transaction->guest_info['last_name'] }}</td>
                    {{-- <td>{{ @$transaction->customer->full_name }}</td> --}}
                    <td>{{ @$transaction->room->title }}</td>
                    <td>{{ @$transaction->ratePlan->title }}</td>
                    <td>{{ @$transaction->night_stay }}</td>
                    <td>${{ @$transaction->final_total }}</td>
                    <td class="text-uppercase">{{ $transaction->payment_method }}</td>
                    {{-- <td>{{ $transaction->status }}</td> --}}
                    {{-- @dd($status) --}}
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn btn-xs @if($transaction->status == 'processing') btn-info @elseif($transaction->status == 'confirmed') btn-success @elseif($transaction->status == 'cancel') btn-danger @endif dropdown-toggle text-uppercase" data-toggle="dropdown">
                            {{ $transaction->status }}
                            </button>

                            <div class="dropdown-menu">
                                @foreach ($status as $key => $item)
                                    <a class="dropdown-item transaction_status" href="#" data-id="{{ $transaction->id }}" data-value="{{ $key }}">{{ $item }}</a>
                                @endforeach
                            </div>
                        </div>
                    </td>
                    <td>
                        <a href="{{ route('admin.booking_report.detail', $transaction->id) }}" class="btn btn-sm btn-info">
                            <i class="fa fa-eye"></i>
                            {{ __('View') }}
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row">
        <div class="col-12 d-flex flex-row flex-wrap">
            <div class="row" style="width: -webkit-fill-available;">
                <div class="col-12 col-sm-6 text-center text-sm-left pl-3" style="margin-block: 20px">
                    {{ __('Showing') }} {{ $transactions->firstItem() }} {{ __('to') }} {{ $transactions->lastItem() }} {{ __('of') }} {{ $transactions->total() }} {{ __('entries') }}
                </div>
                <div class="col-12 col-sm-6 pagination-nav pr-3"> {{ $transactions->links() }}</div>
            </div>
        </div>
    </div>


</div>

