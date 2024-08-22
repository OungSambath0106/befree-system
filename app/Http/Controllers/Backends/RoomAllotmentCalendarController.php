<?php

namespace App\Http\Controllers\Backends;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\RoomDate;
use Illuminate\Http\Request;
use App\helpers\GlobalFunction;
use App\Http\Controllers\Controller;
use App\Models\RatePlan;
use Illuminate\Support\Facades\Validator;

class RoomAllotmentCalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('allotment.view')) {
            abort(403, 'Unauthorized action.');
        }
        $rooms = Room::pluck('title', 'id');
        return view('backends.room_allotment_calendar.index', compact('rooms'));
    }

    public function loadDates(Request $request)
    {
        $request->validate([
            'id'=>'required',
            'start'=>'required',
            'end'=>'required',
        ]);

        // if(!$this->hasHotelPermission($hotel_id))
        // {
        //     return $this->sendError(__("Hotel not found"));
        // }
        /**
         * @var $room HotelRoom
         */

        // dd($request->all());
        $room = Room::find($request->query('id'));
        if(empty($room)){
            return $this->sendError(__('rate plan not found'));
        }

        $is_single = $request->query('for_single');
        $query = RoomDate::query();
        $query->where('type', 'number');
        $query->where('room_id',$request->query('id'));
        $query->where('start_date','>=',date('Y-m-d H:i:s',strtotime($request->query('start'))));
        $query->where('end_date','<=',date('Y-m-d H:i:s',strtotime($request->query('end'))));

        $rows =  $query->take(100)->get();
        $allDates = [];

        $period = GlobalFunction::periodDate($request->input('start'),$request->input('end'),false);
        foreach ($period as $dt) {
            $date = [
                'id'=>rand(0,999),
                'is_active'=>0,
                // 'price'=> $room->price ?? 0,
                'number' => $room->number,
                'is_instant'=>0,
                'is_default'=>true,
                'textColor'=>'#2791fe'
            ];
            // $date['price_html'] = format_money($date['price']);
            $date['price_html'] = $date['number'];
            if(!$is_single){
                // $date['price_html'] = format_money_main($date['price']);
                $date['price_html'] = $date['number'];
            }
            $date['title'] = $date['event']  = $date['price_html'];
            $date['start'] = $date['end'] = $dt->format('Y-m-d');

            $date['is_active'] = 1;
            $total_number = $date['number'];
            $allDates[$dt->format('Y-m-d')] = $date;
        }
        if(!empty($rows))
        {
            foreach ($rows as $row)
            {
                $row->start = date('Y-m-d',strtotime($row->start_date));
                $row->end = date('Y-m-d',strtotime($row->start_date));
                $row->textColor = '#2791fe';
                $number = $row->number ?? 0;
                if(empty($number)){
                    $number = $room->number;
                }
                // $row->title = $row->event = format_money($price);
                $row->title = $row->event = $number;
                if(!$is_single){
                    // $row->title = $row->event = focrmat_money_main($price).' x '.$row->number;
                    $row->title = $row->event = $number;
                }
                $row->number = $number;

                if(!$row->is_active)
                {
                    $row->title = $row->event = __('Cloose');
                    $row->backgroundColor = '#fe2727';
                    $row->classNames = ['blocked-event'];
                    $row->textColor = '#fe2727';
                    $row->is_active = 0;
                }else{
                    $row->classNames = ['active-event'];
                    $row->is_active = 1;
                    // if($row->is_instant){
                    //     $row->title = '<i class="fa fa-bolt"></i> '.$row->title;
                    // }
                }
                $row->total_number = $row->number;
                $allDates[date('Y-m-d',strtotime($row->start_date))] = $row->toArray();

            }
        }
        // dd($request->query('start'), $request->query('end'));
        $bookings = $room->getBookingsInRange($request->query('start'),$request->query('end'));
        // dd($bookings->count());
        if($bookings->count() > 0 && !empty($bookings))
        {
            foreach ($bookings as $booking){
                $booking_end_date = Carbon::parse($booking->checkout_date);
                $period = GlobalFunction::periodDate($booking->checkin_date,$booking_end_date,false);
                foreach ($period as $dt){
                    // dd(1);
                    $date = $dt->format('Y-m-d');
                    if(isset($allDates[$date])){
                        // dd($allDates[$date]);
                        $allDates[$date]['total_number'] = $allDates[$date]['total_number'] ?? $total_number;
                        $allDates[$date]['number'] -= 1;
                        $allDates[$date]['event'] = $allDates[$date]['title'] = $allDates[$date]['number'];

                        if ($allDates[$date]['is_active'] == 0) {
                            // dd($allDates[$date]);
                            $allDates[$date]['event'] = $allDates[$date]['title'] = __('Close');
                        }

                        if($allDates[$date]['number'] <= 0){
                            $allDates[$date]['active'] = 0;
                            $allDates[$date]['event'] = __('Full Book');
                            $allDates[$date]['title'] = __('Full Book');
                            $allDates[$date]['classNames'] = ['full-book-event'];
                        }
                    }
                }
                // $allDates[$date]['booking_number'] = $booking->sum('number');
            }
        }
        $data = array_values($allDates);
        // dd($data);
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $rules = [
            'target_id'=>'required',
            'start_date'=>'required',
            'end_date'=>'required',
            'total_number' => ['required', 'min:0'],
            // 'price' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['success' =>false, 'message' => $validator->messages()->first()]);
        }

        if (request('total_number') <= 0) {
            return response()->json(['success' =>false, 'message' => 'Total number must be greater than 0']);
        }

        if (request()->has('booking_number') && request('booking_number') > request('total_number')) {
            return response()->json(['success' =>false, 'message' => 'The total room must be lower than total booking']);
        }

        $room = Room::find($request->input('target_id'));
        $target_id = $request->input('target_id');

        if(empty($room)){
            return $this->sendError(__('Rate plan not found'));
        }

        // if(!$this->hasPermission('hotel_manage_others')){

        //     if($this->currentHotel->create_user != Auth::id()){
        //         return $this->sendError("You do not have permission to access it");
        //     }
        // }

        // DB::beginTransaction();
        $postData = $request->input();
        $period = GlobalFunction::periodDate($request->input('start_date'),$request->input('end_date'));
        // dd($period);
        // dd(Carbon::parse($request->input('end_date'))->format('Y-m-d'));
        $bookings = $room->getBookingsInRange(Carbon::parse($request->input('start_date'))->format('Y-m-d'),Carbon::parse($request->input('end_date'))->format('Y-m-d'));
        // // $bookings = $room->;
        // // dd($bookings);
        if($bookings->count() > 0 && !empty($bookings)) {
            $total_booking = $bookings->count();
            if (request('total_number') < $total_booking) {
                return response()->json(['success' =>false, 'message' => 'The total room must be lower than total booking']);
            }
        }
        // dd($period);
        foreach ($period as $dt){
            $date = RoomDate::where('start_date',$dt->format('Y-m-d'))->where('room_id',$target_id)->first();

            if(empty($date)){
                $date = new RoomDate();
                $date->room_id = $target_id;
            }
            $postData['start_date'] = $dt->format('Y-m-d H:i:s');
            $postData['end_date'] = $dt->format('Y-m-d H:i:s');


            // $date->fillByAttr([
            //     'start_date','end_date','price',
            // //    'max_guests','min_guests',
            //     // 'is_instant',
            //     'active',
            //     'number'
            // ],$postData);
            $date->room_id = $postData['target_id'];
            $date->start_date = $postData['start_date'];
            $date->end_date = $postData['end_date'];
            $date->number = $postData['total_number'] ?? 0;
            $date->is_active = $postData['is_active'];
            $date->type = 'number';
            // dd($postData);
            // $date->number = $postData['total_number'];

            $date->save();
        }
        // DB::commit();
        return $this->sendSuccess([],__("Update Success"));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\cr  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\cr  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\cr  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\cr  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function sendSuccess($data = [],$message = '')
    {
        if(is_string($data))
        {
            return response()->json([
                'message'=>$data,
                'status'=>true
            ]);
        }

        if(!isset($data['status'])) $data['status'] = 1;

        if($message)
            $data['message'] = $message;

        return response()->json($data);
    }

    public function sendError($message,$data = []){

        $data['status'] = 0;

        return $this->sendSuccess($data,$message);

    }
}
