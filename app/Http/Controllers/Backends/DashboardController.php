<?php

namespace App\Http\Controllers\Backends;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Room;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $rooms = Room::get();
        $transactions = Transaction::get();
        $customers = Customer::get();
        $top_book_homestays = Room::withCount('transactions')->orderByDesc('transactions_count')->get()->take(5);
        $lates_bookings = Transaction::latest('id')->get()->take(5);

        return view('backends.index', compact('rooms', 'transactions', 'customers', 'top_book_homestays', 'lates_bookings'));
    }
}
