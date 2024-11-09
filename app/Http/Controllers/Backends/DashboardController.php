<?php

namespace App\Http\Controllers\Backends;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Room;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $users = User::get();
        $brands = Brand::get();
        $products = Product::get();

        return view('backends.index', compact('users', 'brands', 'products'));
    }
}
