<?php

namespace App\Http\Controllers\Backends;

use Exception;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\helpers\GlobalFunction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function bookingReport(Request $request)
    {
        $status = [
            'processing' => __('Processing'),
            'confirmed' => __('Confirmed'),
            'cancel' => __('Cancel'),
        ];

        $transactions = Transaction::latest('id')->paginate(10);
        GlobalFunction::seenNotification('App\Models\Transaction');
        return view('backends.report.booking_report', compact('transactions', 'status'));
    }

    public function viewBookingReport($id, Request $request)
    {
        $transaction = Transaction::find($id);

        return view('backends.report.booking_report_detail', compact('transaction'));
    }

    public function bookingReportUpdateStatus(Request $request)
    {
        $transaction = Transaction::find($request->id);
        $transaction->status = $request->status;
        $transaction->save();

        $transactions = Transaction::latest('id')->paginate(10)->withQueryString();

        $status = [
            'processing' => __('Processing'),
            'confirmed' => __('Confirmed'),
            'cancel' => __('Cancel'),
        ];

        if ($request->ajax()) {
            $view = view('backends.report.partials._booking_report_table', compact('transactions','status'))->render();
            return response()->json([
                'view' => $view,
                'success' => true,
                'msg' => 'Updated order status successfully.'
            ]);
        }
    }
}
