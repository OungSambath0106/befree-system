<?php

namespace App\Http\Controllers\Frontends;

use PDF;
use Exception;
use Carbon\Carbon;
use App\Models\Blog;
use App\Models\Room;
use App\Models\Slider;
use App\Models\BlogTag;
use App\Models\Gallery;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Facility;
use App\Models\RatePlan;
use App\Models\RoomDate;
use App\Mail\InvoiceEmail;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Models\BlogCategory;
use App\Models\ExtraService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ServiceGallery;
use App\helpers\GlobalFunction;
use App\Models\BusinessSetting;
use App\Models\HomeStayGallery;
use App\Jobs\SendEmailCheckoutJob;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Highlight;
use App\Models\Staycation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class FrontendController extends Controller
{
    public function __construct()
    {
        define('MINUTE_IN_SECONDS', 60);
        define('HOUR_IN_SECONDS', 60 * MINUTE_IN_SECONDS);
        define('DAY_IN_SECONDS', 24 * HOUR_IN_SECONDS);
        define('WEEK_IN_SECONDS', 7 * DAY_IN_SECONDS);
        define('MONTH_IN_SECONDS', 30 * DAY_IN_SECONDS);
        define('YEAR_IN_SECONDS', 365 * DAY_IN_SECONDS);
    }

    public function home()
    {
        $setting = BusinessSetting::all();
        $data['services']        = Service::where('status', 'active')->get();
        $data['blogs']           = Blog::where('status', 'active')->get();
        $data['rooms'] = Room::with(['homestaygaller', 'RatePlan'])->where('status', 'active')->latest()->paginate(6);
        $data['extra_services']  = ExtraService::where('status', 'active')->get();
        $data['facilities']      = Facility::where('status', 'active')->get();
        $data['staycations']      = Staycation::where('status', 'active')->get();
        $data['comments']      = Comment::where('status', 'active')->get();
        $data['highlights']      = Highlight::where('status', 'active')->get();
        $data['link_google_map'] = @$setting->where('type', 'link_google_map')->first()->value;
        $data['company_description'] = @$setting->where('type', 'company_description')->first()->value;
        $data['company_name']    = @$setting->where('type', 'company_name')->first()->value;
        $data['phone']           = @$setting->where('type', 'phone')->first()->value;
        $data['email']           = @$setting->where('type', 'email')->first()->value;
        $data['company_address'] = @$setting->where('type', 'company_address')->first()->value;
        $data['sliders']         = Slider::where('status', 'active')->whereNull('deleted_at')->where('type', 'company_info')->get();

        // contact info
        $data['contact_us_phone_number']   = @$setting->where('type', 'contact_us_phone_number')->first()->value;
        $data['contact_description']       = @$setting->where('type', 'contact_description')->first()->value;
        $data['extra_service_description'] = @$setting->where('type', 'extra_service_description')->first()->value;
        $data['history_of_chaufea']        = @$setting->where('type', 'history_of_chaufea')->first()->value ?? '';
        $data['foundation']                = @$setting->where('type', 'foundation')->first()->value ?? '';
        $data['company_sub_title']         = @$setting->where('type', 'company_sub_title')->first()->value ?? '';


        // imagee
        $data['image1'] = @$setting->where('type', 'image1')->first()->value;
        $data['image2'] = @$setting->where('type', 'image2')->first()->value;
        $data['image3'] = @$setting->where('type', 'image3')->first()->value;
        $data['image4'] = @$setting->where('type', 'image4')->first()->value;

        //gallery
        $data['gallery'] = Gallery::where('status', 'active')->orderBy('id', 'desc')->get();
        // $data['showgallery'] = Gallery::where('status', 'active')->orderBy('id', 'desc')->where('id', 2)->get();
        $data['galleryCount'] = $data['gallery']->count();
        // $data['gallerys'] = Gallery::where('status', 'active')->orderBy('id', 'asc')->take(4)->get()->reverse();

        // Find the first available gallery starting from ID 2 that is active
        $showgallery = Gallery::where('status', 'active')
        ->where('id', '>=', 2)
        ->orderBy('id', 'asc')
        ->first();

        // Check if a gallery was found; if not, set $showgallery to null
        $data['showgallery'] = $showgallery ? $showgallery : null;

        if ($showgallery) {
        // Get the next 4 galleries after the one found in showgallery
        $data['gallerys'] = Gallery::where('status', 'active')
            ->where('id', '>', $showgallery->id)
            ->orderBy('id', 'asc')
            ->take(4)
            ->get();
        } else {
        // If no showgallery found, set gallerys to empty collection
        $data['gallerys'] = collect();
        }


        //count gallery for rooms
        foreach ($data['rooms'] as $room) {
            $room->image_count = $room->homestaygaller->reduce(function ($carry, $gallery) {
                return $carry + count($gallery->image);
            }, 0);
        }


        $data['categories'] = Category::where('status', 1)
            ->withCount('galleries')
            ->get();

        $data['galleryCount'] = Gallery::count();
        $data['galleries'] = Gallery::all();
        
        $data['categoryGalleries'] = [];
        foreach ($data['categories'] as $category) {
            $data['categoryGalleries'][$category->id] = $category->galleries;
        }
        return view('frontends.home.newhome', $data);
    }

    // public function checkRoomAvailability(Request $request)
    // {
    //     // Validate the request
    //     $validated = $request->validate([
    //         'checkin_date' => 'required|date_format:d/m/Y',
    //         'checkout_date' => 'required|date_format:d/m/Y|after:checkin_date',
    //     ]);

    //     // Convert date formats
    //     $checkinDate = Carbon::createFromFormat('d/m/Y', $validated['checkin_date'])->format('Y-m-d');
    //     $checkoutDate = Carbon::createFromFormat('d/m/Y', $validated['checkout_date'])->format('Y-m-d');

    //     // Query to get available rooms
    //     $rooms = Room::whereDoesntHave('roomDates', function ($query) use ($checkinDate, $checkoutDate) {
    //         $query->where(function ($query) use ($checkinDate, $checkoutDate) {
    //             $query->whereBetween('start_date', [$checkinDate, $checkoutDate])
    //                 ->orWhereBetween('end_date', [$checkinDate, $checkoutDate])
    //                 ->orWhere(function ($query) use ($checkinDate, $checkoutDate) {
    //                     $query->where('start_date', '<=', $checkinDate)
    //                         ->where('end_date', '>=', $checkoutDate);
    //                 });
    //         });
    //     })->get();

    //     return response()->json($rooms);
    // }

    public function checkRoomAvailability(Request $request)
    {
        // dd($request->all());
        try {
            $rules = [
                // 'hotel_id'   => 'required',
                'start_date' => 'required',
                'end_date'   => 'required',
                // 'number_of_room'     => 'required',
            ];

            $bookingInfo = $request->all();
            if (is_array($bookingInfo)) {
                if (array_key_exists('homestay_id', $bookingInfo)) {
                    $bookingInfo['room_id'] = $bookingInfo['homestay_id'];
                    unset($bookingInfo['homestay_id']);
                }

                $request->session()->put('booking_info', $bookingInfo);
            }
            $validator = Validator::make(request()->all(), $rules);

            if ($validator->fails()) {
                $output = ['success' => false, 'msg' => $validator->messages()->first()];
                return redirect()->back()->with($output);
            }

            $start_date = Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d');
            $end_date = Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d');
            // dd($end_date);

            // dd(strtotime($end_date), strtotime($start_date), DAY_IN_SECONDS);
            if (strtotime($end_date) - strtotime($start_date) < DAY_IN_SECONDS) {
                return redirect()->back()->with(['success' => false, 'msg' => __("Dates are not valid")]);
            }
            if (strtotime($end_date) - strtotime($start_date) > 30 * DAY_IN_SECONDS) {
                return redirect()->back()->with(['success' => false, 'msg' => __("Maximum day for booking is 30")]);
            }

            $number_of_room = $request->number_of_room;

            $numberDays = abs(strtotime($end_date) - strtotime($start_date)) / 86400;
            if ($numberDays < 1) {
                return redirect()->back()->with(['success' => false, 'msg' => __("You must to book a minimum of :number days", ['number' => 1])]);
            }

            $room_availables = [];
            $room = Room::find(request('homestay_id'));
            // dd($request->all());
            $rooms = Room::with(['homestaygaller', 'RatePlan'])->where('status', 'active')->get();
            foreach ($rooms as $room) {
                if ($this->checkAvailable($request, $room->id)) {
                    $room_availables[] = $room;
                }
            }
            // return $room_availables;

            // if ($room_available == false) {
            //     return redirect()->back()->with([
            //         'success' => false,
            //         'msg' => __('Homestay not available')
            //     ]);
            // }

            //count gallery for rooms
            $rooms = $rooms->map(function ($room) {
                $room->image_count = $room->homestaygaller->reduce(function ($carry, $gallery) {
                    return $carry + count($gallery->image);
                }, 0);
                return $room;
            });
            $rooms = $room_availables;

            $view = view('frontends.home.partials._rooms_container', compact('rooms'))->render();

            return response()->json([
                'success' => true,
                'view' => $view,
                'msg' => __('Room available')
            ]);

            // return view('frontends.home.room_available', ['rooms' => $room_availables])->render();
        } catch (Exception $e) {
            dd($e);
            return redirect()->back()->with([
                'success' => false,
                'msg' => __('messages.something_went_wrong')
            ]);
        }
    }

    public function homeStay()
    {
        $data['rooms'] = Room::where('status', 'active')->latest('id')->get();
        return view('frontends.homestay.homestay', $data);
    }

    public function homeStaySearch(Request $request)
    {
        try {
            // dd($request->all());
            $rules = [
                // 'hotel_id'   => 'required',
                'start_date' => 'required',
                'end_date'   => 'required',
                // 'number_of_room'     => 'required',
            ];
            $bookingInfo = $request->all();
            if (is_array($bookingInfo)) {
                if (array_key_exists('homestay_id', $bookingInfo)) {
                    $bookingInfo['room_id'] = $bookingInfo['homestay_id'];
                    unset($bookingInfo['homestay_id']);
                }

                $request->session()->put('booking_info', $bookingInfo);
            }
            $validator = Validator::make(request()->all(), $rules);

            if ($validator->fails()) {
                $output = ['success' => false, 'msg' => $validator->messages()->first()];
                return redirect()->back()->with($output);
            }

            $end_date = date('Y-m-d', strtotime($request->end_date));
            $start_date = date('Y-m-d', strtotime($request->start_date));

            if (strtotime($end_date) - strtotime($start_date) < DAY_IN_SECONDS) {
                return redirect()->back()->with(['success' => false, 'msg' => __("Dates are not valid")]);
            }
            if (strtotime($end_date) - strtotime($start_date) > 30 * DAY_IN_SECONDS) {
                return redirect()->back()->with(['success' => false, 'msg' => __("Maximum day for booking is 30")]);
            }

            $number_of_room = $request->number_of_room;

            $numberDays = abs(strtotime($end_date) - strtotime($start_date)) / 86400;
            if ($numberDays < 1) {
                return redirect()->back()->with(['success' => false, 'msg' => __("You must to book a minimum of :number days", ['number' => 1])]);
            }

            $room = Room::find(request('homestay_id'));
            // dd($request->all());
            $room_available = $this->checkAvailable($request);

            if ($room_available == false) {
                return redirect()->back()->with([
                    'success' => false,
                    'msg' => __('Homestay not available')
                ]);
            }

            return view('frontends.search.search', compact('room'));
        } catch (Exception $e) {
            // dd($e);
            return redirect()->back()->with([
                'success' => false,
                'msg' => __('messages.something_went_wrong')
            ]);
        }
    }

    public function checkHomestayAvailable(Request $request)
    {
        try {
            $rules = [
                'start_date' => 'required:date_format:Y-m-d',
                'end_date'   => 'required:date_format:Y-m-d',
            ];
            $messagges = [
                'start_date.required' => __('Checkin date is required'),
                'end_date.required' => __('Checkout date is required'),
            ];

            $validator = Validator::make(request()->all(), $rules, $messagges);

            if ($validator->fails()) {
                $output = ['success' => false, 'msg' => $validator->messages()->first()];
                return response()->json($output);
            }

            $current_date = Carbon::now()->format('Y-m-d');
            if ($request->start_date < $current_date) {
                return response()->json(['success' => false, 'msg' => __("Dates are not valid")]);
            }

            if (strtotime($request->end_date) - strtotime($request->start_date) < DAY_IN_SECONDS) {
                return response()->json(['success' => false, 'msg' => __("Dates are not valid")]);
            }
            if (strtotime($request->end_date) - strtotime($request->start_date) > 30 * DAY_IN_SECONDS) {
                return response()->json(['success' => false, 'msg' => __("Maximum day for booking is 30")]);
            }

            $number_of_room = $request->number_of_room;

            $numberDays = abs(strtotime($request->end_date) - strtotime($request->start_date)) / 86400;
            if ($numberDays < 1) {
                return response()->json(['success' => false, 'msg' => __("You must to book a minimum of :number days", ['number' => 1])]);
            }

            $room = Room::find(request('homestay_id'));

            $room_available = $this->checkAvailable($request);

            if ($room_available == false) {
                return response()->json([
                    'success' => false,
                    'msg' => __('This homestay is not available')
                ]);
            }

            // dd($request->all());
            $rate_plans = RatePlan::where('room_id', $room->id)
                ->when($request->is_package == 1, function ($query) {
                    return $query->where('type', 'package');
                })
                ->get();
            $rate_plan_id = RatePlan::where('room_id', $room->id)->pluck('id')->toArray();
            // dd($rate_plan_id);
            $query = RoomDate::query();
            $query->where('type', 'price');
            $query->whereIn('rate_plan_id', $rate_plan_id);
            $query->where('start_date', '>=', date('Y-m-d H:i:s', strtotime($request->query('start_date'))));
            $query->where('end_date', '<=', date('Y-m-d H:i:s', strtotime($request->query('end_date'))));
            $query->orderBy('price', 'desc');
            $dates =  $query->get();

            $rate_plans->map(function ($row) use ($dates) {
                if ($dates->count() > 0) {
                    $date = $dates->where('rate_plan_id', $row->id)->first();

                    $row->price = $date->price;
                    // dd($date);
                }
                return $row;
            });

            $view = view('frontends.homestay._check_available_form', compact('room', 'rate_plans'))->render();
            return response()->json([
                'success' => true,
                'msg' => __('This homestay is avaliable'),
                'view' => $view,
            ]);
        } catch (Exception $e) {
            dd($e->getMessage());
            return response()->json([
                'success' => false,
                'msg' => __('messages.something_went_wrong')
            ]);
        }
    }

    private function checkAvailable(Request $request, $room_id)
    {
        $room_available = Room::find($room_id)->isAvailableAt($request->input());
        return $room_available;
    }

    public function homeStayDetail($id)
    {
        $room = Room::findOrFail($id);
        $home_stay_gallery = HomeStayGallery::where('home_stay_id', $room->id)->first();
        $rate_plan = RatePlan::where('room_id', $room->id)->get();
        return view('frontends.homestay.homestay_detail', compact('room', 'home_stay_gallery', 'rate_plan'));
    }
    public function blog(Request $request)
    {
        $setting = BusinessSetting::all();
        $query = Blog::where('status', 'active');
        if ($request->has('category_id')) {
            $category_id = $request->input('category_id');
            $query->where('category_id', $category_id);
        }

        if ($request->has('tag_id')) {
            $tag_ids = json_decode($request->input('tag_id'), true);

            if ($tag_ids !== null) {
                $query->whereIn('tag_id', $tag_ids);
            }
        }
        $data['blogs']          = $query->get();
        $data['foundation']     = @$setting->where('type', 'foundation')->first()->value ?? '';
        return view('frontends.blog.blog', $data);
    }

    public function blogDetail($id)
    {
        $data['blog'] = Blog::findOrFail($id);
        $data['blogs'] = Blog::where('status', 'active')->orderBy('created_at', 'desc')->get();
        $data['categories'] = BlogCategory::where('status', 'active')->get();
        $data['tages'] = BlogTag::where('status', 'active')->get();
        return view('frontends.blog.blog_detail', $data);
    }
    public function aboutUs()
    {
        $setting = BusinessSetting::all();
        $data['extra_service_description'] = @$setting->where('type', 'extra_service_description')->first()->value;
        $data['company_description'] = @$setting->where('type', 'company_description')->first()->value;
        $data['extra_services']      = ExtraService::where('status', 'active')->get();
        $data['company_name']        = @$setting->where('type', 'company_name')->first()->value;
        $data['phone']               = @$setting->where('type', 'phone')->first()->value;
        $data['image1']              = @$setting->where('type', 'image1')->first()->value;
        $data['image2']              = @$setting->where('type', 'image2')->first()->value;
        $data['image3']              = @$setting->where('type', 'image3')->first()->value;
        $data['image4']              = @$setting->where('type', 'image4')->first()->value;
        $data['facilities']          = Facility::where('status', 'active')->get();
        $data['history_of_chaufea']  = @$setting->where('type', 'history_of_chaufea')->first()->value ?? '';
        $data['sliders']             = Slider::where('status', 'active')->whereNull('deleted_at')->where('type', 'company_info')->get();
        return view('frontends.about-us.about-us', $data);
    }


    public function service()
    {
        $data['services'] = Service::where('status', 'active')->get();
        return view('frontends.service.service', $data);
    }

    public function service_detail($id)
    {
        $data['services'] = Service::where('status', 'active')->get();
        $data['service'] = Service::where('id', $id)->first();
        $data['gallery'] = ServiceGallery::where('service_id', $id)->first();
        return view('frontends.service.service_detail', $data);
    }

    public function facility()
    {
        $setting = BusinessSetting::all();
        $data['history_of_chaufea']        = @$setting->where('type', 'history_of_chaufea')->first()->value ?? '';
        return view('frontends.facility.facility', $data);
    }
    public function gallery()
    {
        $data['gallery'] = Gallery::where('status', 'active')->get();
        return view('frontends.gallery.gallery', $data);
    }

    public function bookNow(Request $request)
    {
        if ($request->ajax()) {
            // dd($request->all());

            // $room = Room::find(request('homestay_id'));
            $data = new Request([
                'homestay_id'   => $request->room_id,
                'start_date'    => $request->start_date,
                'end_date'      => $request->end_date,
            ]);
            $room_available = $this->checkAvailable($data);

            if ($room_available == false) {
                // dd(1);
                return response()->json([
                    'success' => false,
                    'msg' => __('Homestay not available')
                ]);
            }



            $request->session()->put('booking_info', $request->all());
            $rate_plan = RatePlan::find($request->rate_plan_id);

            // dd($rate_plan_id);
            $query = RoomDate::query();
            $query->where('type', 'price');
            $query->where('rate_plan_id', $request->rate_plan_id);
            $query->where('start_date', '>=', date('Y-m-d H:i:s', strtotime($request->query('start_date'))));
            $query->where('end_date', '<=', date('Y-m-d H:i:s', strtotime($request->query('end_date'))));
            // $query->orderBy('price', 'desc');
            $rate_plan_dates = $query->get();
            // dd($rate_plan_dates->pluck('id', 'price'));
            $period = GlobalFunction::periodDate($request->start_date, $request->end_date, false);

            $date_price = [];
            $total_price = 0;
            foreach ($period as $dt) {
                $price_each_date = $rate_plan->price;
                if ($rate_plan_dates->count() > 0) {
                    $rateplan_each_date = $rate_plan_dates->where('start_date', $dt->format('Y-m-d'))->first();
                    if ($rateplan_each_date) {
                        $price_each_date = $rateplan_each_date->price;
                    }
                }
                $date_price[$dt->format('Y-m-d')] = $price_each_date;
                $total_price += $price_each_date;
            }

            $request->session()->put('booking_info.rate_plan', $rate_plan);
            $request->session()->put('booking_info.date_price', $date_price);
            $request->session()->put('booking_info.total_price', $total_price);

            $start_date = Carbon::createFromFormat('Y-m-d', $request->query('start_date'));
            $end_date = Carbon::createFromFormat('Y-m-d', $request->query('end_date'));
            $total_night = $end_date->diffInDays($start_date);

            $request->session()->put('booking_info.total_night', $total_night);
            // dd(session()->all());

            if (!auth()->guard('customer')->check()) {
                return response()->json([
                    'success' => 0,
                    'msg' => 'Please login first'
                ]);
            }

            return response()->json([
                'success' => 1,
                'url' => route('checkout_form'),
            ]);
        }
    }

    public function checkoutForm(Request $request, $id)
    {
        // return auth()->user();
        $customer = auth()->guard('customer')->user();
        $booking_info = session()->get('booking_info');
        $room = Room::find($id);

        $business = new BusinessSetting;
        $booking_policy = @$business->where('type', 'booking_policy')->first()->value;
        // $start_date = Carbon::createFromFormat('Y-m-d', $booking_info['start_date']);
        // $end_date = Carbon::createFromFormat('Y-m-d', $booking_info['end_date']);
        // $total_night = $booking_info['total_night'];

        return view('frontends.booking.newcheckout', compact('customer', 'booking_info', 'room', 'booking_policy'));
    }

    public function postCheckout(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'full_mobile' => 'required',
            'country' => 'required',
        ]);

        $booking_info = session('booking_info');

        if ($validator->fails()) {
            $output = ['success' => false, 'msg' => $validator->messages()->first()];
            return redirect()->back()->with($output)->withInput();
        }

        $data = new Request([
            'homestay_id'   => $booking_info['room_id'],
            'start_date'    => $booking_info['start_date'],
            'end_date'      => $booking_info['end_date'],
        ]);

        $room_available = $this->checkAvailable($data);

        if ($room_available == false) {
            $output = [
                'success' => false,
                'msg' => __('Homestay not available')
            ];
            return redirect()->route('homestay_detail', $booking_info['room_id'])->with($output)->withInput();
        }

        set_time_limit(300);
        try {
            DB::beginTransaction();
            // dd($request->all(), session()->all());

            $customer               = Customer::find(auth()->guard('customer')->user()->id);
            $customer->first_name   = $request->first_name;
            $customer->last_name    = $request->last_name;
            $customer->email        = $request->email;
            $customer->phone        = $request->full_mobile;
            $customer->title        = $request->title;
            $customer->country      = $request->country;
            $customer->save();

            $transaction                     = new Transaction;
            $transaction->customer_id        = $customer->id;
            $transaction->room_id            = $booking_info['room_id'];
            $transaction->booking_package_id = $booking_info['rate_plan_id'];
            $transaction->checkin_date       = $booking_info['start_date'];
            $transaction->checkout_date      = $booking_info['end_date'];
            $transaction->final_total        = $booking_info['total_price'];
            $transaction->night_stay         = $booking_info['total_night'];
            $transaction->status             = 'processing';
            $transaction->payment_method     = $request->payment_method ?? 'cash';
            $transaction->guest_info         = $request->except('_token', 'comment', 'confirm_email');
            $transaction->comment            = $request->comment;
            $transaction->price_each_date    = $booking_info['date_price'];

            $invoice_no = Str::random(4) . date('Ymd');
            $transaction->invoice_no         = $invoice_no;

            $transaction->save();
            session()->put('customer', $customer);
            session()->put('transaction', $transaction);

            GlobalFunction::storeNotification('App\Models\Transaction', $transaction->id);

            DB::commit();
            if ($transaction) {
                try {
                    $customer_email = $customer->email;
                    $customer_name = $customer->first_name . ' ' . $customer->last_name;
                    $customer_phone_number = $customer->phone;
                    $font_family = "'Hanuman','sans-serif'";
                    $config = [];
                    $data['customer'] = $customer;
                    $data['transaction'] = $transaction;
                    $data["customer_email"] = $customer_email;
                    $data["title"] = "Hi " . $customer_name;
                    $data["body"] = "This is your order detail. Please kindly check with the attached file. Thank you.";

                    // send to customer
                    $pdf = PDF::loadView('email.invoice', [
                        'data' => $data,
                        'customer' => $customer,
                        'transaction' => $transaction,
                        'font_family' => $font_family,
                    ], [], $config);
                    Mail::send([], [], function ($message) use ($data, $pdf) {
                        $message->to($data["customer_email"], $data["customer_email"])
                            ->subject($data["title"])
                            ->attachData($pdf->output(), "payment.pdf")
                            ->setBody($data["body"]);
                    });

                    // send to admin
                    $admin_email = BusinessSetting::where('type', 'email')->first()->value ?? '';

                    $data["email"] = $admin_email;
                    $data["title"] = "Hi Admin, You have a new booking from " . $customer_name;
                    $data["body"] = "This is a new booking from your customer. Please kindly check with the attached file.\n\nCustomer info:\nName: " . $customer_name . "\nEmail: " . $customer_email . "\nPhone: " . $customer_phone_number . "\n\nThank you.";

                    $pdf = PDF::loadView('email.invoice', [
                        'data' => $data,
                        'customer' => $customer,
                        'transaction' => $transaction,
                        'font_family' => $font_family,
                    ], [], $config);

                    Mail::send([], [], function ($message) use ($data, $pdf) {
                        $message->to($data["email"], $data["email"])
                            ->subject($data["title"])
                            ->attachData($pdf->output(), "payment.pdf")
                            ->setBody($data["body"]);
                    });

                    // send to admin
                    $sales_email = BusinessSetting::where('type', 'sales_email')->first()->value ?? '';

                    $data["sales_email"] = $sales_email;
                    $data["title"] = "Hi Admin, You have a new booking from " . $customer_name;
                    $data["body"] = "This is a new booking from your customer. Please kindly check with the attached file.\n\nCustomer info:\nName: " . $customer_name . "\nEmail: " . $customer_email . "\nPhone: " . $customer_phone_number . "\n\nThank you.";

                    $pdf = PDF::loadView('email.invoice', [
                        'data' => $data,
                        'customer' => $customer,
                        'transaction' => $transaction,
                        'font_family' => $font_family,
                    ], [], $config);

                    Mail::send([], [], function ($message) use ($data, $pdf) {
                        $message->to($data["sales_email"], $data["sales_email"])
                            ->subject($data["title"])
                            ->attachData($pdf->output(), "payment.pdf")
                            ->setBody($data["body"]);
                    });
                } catch (Exception $e) {
                    dd($e);
                }
            }

            return redirect()->route('show_booking_success')->with([
                'success' => true,
                'msg' => 'Booking successfully'
            ]);
            // return view('frontends.booking.booking-confirm', compact('transaction', 'customer'));

        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->back()->with([
                'success' => false,
                'msg' => 'Something went wrong',
            ]);
        }
    }
    public function newpostCheckout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'full_mobile' => 'required',
            'country' => 'required',
            'checkin_date' => 'required|date_format:d/m/Y',
            'checkout_date' => 'required|date_format:d/m/Y',
        ]);
        // $booking_info = session('booking_info');

        if ($validator->fails()) {
            $output = ['success' => false, 'msg' => $validator->messages()->first()];
            return redirect()->back()->with($output)->withInput();
        }
        // $data = new Request([
        //     'start_date'    => $booking_info['start_date'],
        //     'end_date'      => $booking_info['end_date'],
        // ]);

        try {
            DB::beginTransaction();
            // dd($request->rate_plan_id);
            $booking_info = session('booking_info', []);
            // $checkinDate = Carbon::createFromFormat('d/m/Y', $booking_info['start_date'])->format('Y-m-d');
            // $checkoutDate = Carbon::createFromFormat('d/m/Y', $booking_info['end_date'])->format('Y-m-d');
            $checkinDate = Carbon::createFromFormat('d/m/Y', $booking_info['start_date']);
            $checkoutDate = Carbon::createFromFormat('d/m/Y', $booking_info['end_date']);
            $total_day = $checkinDate->diffInDays($checkoutDate);
            $room = Room::findOrFail($request->room_id);
            $final_total = 0;
            if ($room) {
                $final_total = $total_day * $room->price;
            }
            // Convert date formats
            // $checkinDate = Carbon::createFromFormat('d/m/Y', $request->checkin_date)->format('Y-m-d');
            // $checkoutDate = Carbon::createFromFormat('d/m/Y', $request->checkout_date)->format('Y-m-d');


            $rate_plan = RatePlan::find($request->rate_plan_id);
            // dd($rate_plan);
            $total_price = 0;
            $date_price = [];
            if ($rate_plan) {
                $query = RoomDate::query();
                $query->where('type', 'price');
                $query->where('rate_plan_id', $request->rate_plan_id);
                $query->where('start_date', '>=', date('Y-m-d H:i:s', strtotime($request->query('start_date'))));
                $query->where('end_date', '<=', date('Y-m-d H:i:s', strtotime($request->query('end_date'))));
                // $query->orderBy('price', 'desc');
                $rate_plan_dates = $query->get();
                // dd($rate_plan_dates->pluck('id', 'price'));
                $period = GlobalFunction::periodDate($checkinDate, $checkoutDate, false);
                // dd($period);
                // $total_price = 0;
                foreach ($period as $dt) {
                    $price_each_date = $rate_plan->price;
                    if ($rate_plan_dates->count() > 0) {
                        $rateplan_each_date = $rate_plan_dates->where('start_date', $dt->format('Y-m-d'))->first();
                        if ($rateplan_each_date) {
                            $price_each_date = $rateplan_each_date->price;
                        }
                    }
                    $date_price[$dt->format('Y-m-d')] = $price_each_date;
                    $total_price += $price_each_date;
                }
            }

            // dd($total_price);
            $final_price = $total_price + $final_total;

            $transaction = new Transaction;
            $transaction->guest_info = $request->except('_token', 'comment', 'confirm_email');
            $transaction->room_id = $request->room_id;
            $transaction->night_stay = $total_day;
            // $transaction->night_stay = $request->night_stay ?? $calculatedNightStay;
            $transaction->checkin_date = $checkinDate;
            $transaction->checkout_date = $checkoutDate;
            // $transaction->checkin_date       = $booking_info['start_date'];
            // $transaction->checkout_date      = $booking_info['end_date'];
            $transaction->final_total = $final_price;
            $transaction->booking_package_id = $request->rate_plan_id;
            // $transaction->customer_id = 1;
            $transaction->status = 'processing';
            $transaction->payment_method = 'ABA';
            $transaction->price_each_date    = $date_price;
            $invoice_no = Str::random(4) . date('Ymd');
            $transaction->invoice_no = $invoice_no;
            $transaction->save();

            DB::commit();

            return redirect()->back()->with([
                'success' => true,
                'msg' => 'Booking successfully',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->back()->with([
                'success' => false,
                'msg' => 'Something went wrong',
            ]);
        }
    }

    public function showBookingSuccess(Request $request)
    {
        // $
        if (session()->has('customer')) {
            $customer = session('customer');
        }

        if (session()->has('transaction')) {
            $transaction = session('transaction');
        }

        return view('frontends.booking.booking-confirm', compact('customer', 'transaction'));
    }

    public function bookingHistory()
    {
        $transactions = Transaction::where('customer_id', auth()->guard('customer')->id())->latest('id')->get();

        return view('frontends.booking.booking-history', compact('transactions'));
    }

    public function package()
    {
        $data['packages'] = RatePlan::has('room')
            ->where('status', 'active')
            ->where('type', 'package')
            ->get();
        return view('frontends.package.package', $data);
    }

    public function packageDetail($id)
    {

        // $package = RatePlan::findOrFail($id);
        $room = Room::findOrFail(request('room_id'));
        $home_stay_gallery = HomeStayGallery::where('home_stay_id', $room->id)->first();
        $rate_plan = RatePlan::where('room_id', $room->id)->get();
        return view('frontends.homestay.homestay_detail', compact('room', 'home_stay_gallery', 'rate_plan'));

        // $home_stay_gallery = HomeStayGallery::where('home_stay_id', $package->room_id)->first();
        // return view('frontends.package.package_detail', compact('package', 'home_stay_gallery'));
    }
    public function checkLoginStatus()
    {
        return response()->json(['success' => auth()->guard('customer')->check()]);
    }
}
