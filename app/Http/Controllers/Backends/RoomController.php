<?php

namespace App\Http\Controllers\Backends;

use Exception;
use App\Models\Room;
use App\Models\Translation;
use Illuminate\Http\Request;
use App\helpers\ImageManager;
use App\Models\BusinessSetting;
use App\Models\HomeStayAmenity;
use App\Models\HomeStayGallery;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('room.view')) {
            abort(403, 'Unauthorized action.');
        }
        $rooms = Room::latest('id')->paginate(10);
        return view('backends.room.index',compact('rooms'));
    }
    public function create()
    {
        if (!auth()->user()->can('room.create')) {
            abort(403, 'Unauthorized action.');
        }
        $amenities = HomeStayAmenity::findorFail(4);
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];
        if (request()->ajax()) {
            $lang = request('lang');
            $key = request('key');
            if(request()->type == 'checkin'){
                $tr = view('backends.room._checkin_detail_tr', compact('key', 'lang'))->render();
            }else{
                $tr = view('backends.room._checkout_detail_tr', compact('key', 'lang'))->render();
            }
            return response()->json([
                'tr' => $tr
            ]);
        }
        return view('backends.room.create', compact('language', 'default_lang', 'amenities'));
    }
    public function store(Request $request)
    {
        // dd($request->all());
        if (!auth()->user()->can('room.create')) {
            abort(403, 'Unauthorized action.');
        }
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'number' => 'required',
        ]);

        if (is_null($request->title[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'Title', 'The title field is required!'
                );
            });
        }
        // if (is_null($request->special_note[array_search('en', $request->lang)])) {
        //     $validator->after(function ($validator) {
        //         $validator->errors()->add(
        //             'special_note', 'The special note field is required!'
        //         );
        //     });
        // }
        if (is_null($request->description[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'description', 'The description field is required!'
                );
            });
        }

        if ($validator->fails()) {
            return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with(['success' => 0, 'msg' => __('Invalid form input')]);
        }

        try{
            DB::beginTransaction();
            $room = new Room;
            $room->title        = $request->title[array_search('en', $request->lang)];
            $room->special_note = $request->special_note[array_search('en', $request->lang)]??null;
            $room->description  = $request->description[array_search('en', $request->lang)];
            // $room->column_1     = $request->column_1[array_search('en', $request->lang)];
            // $room->column_2     = $request->column_2[array_search('en', $request->lang)];
            // $room->column_3     = $request->column_3[array_search('en', $request->lang)];
            // $room->video        = $request->video;
            $room->number       = $request->number;
            $room->adult        = $request->adult;
            $room->child        = $request->child;
            $selectAmenities    = $request->input('amenities',[]);
            $room->amenities    = $selectAmenities;
            $room->pet          = $request->pet ? 1:0;
            $room->price = $request->price;
            // if ($request->filled('room_thumbnail_names')) {
            //     $room->thumbnail = $request->room_thumbnail_names;
            //     $directory = public_path('uploads/room');
            //     if (!\File::exists($directory)) {
            //         \File::makeDirectory($directory, 0777, true);
            //     }

            //     $thumbnail = \File::move(public_path('uploads/temp/' . $request->room_thumbnail_names), public_path('uploads/room/'. $request->room_thumbnail_names));

            // }

            // if ($request->filled('video_thumbnail_names')) {
            //     $room->video_thumbnail = $request->video_thumbnail_names;
            //     $directory = public_path('uploads/room');
            //     if (!\File::exists($directory)) {
            //         \File::makeDirectory($directory, 0777, true);
            //     }

            //     $video_thumbnail = \File::move(public_path('uploads/temp/' . $request->video_thumbnail_names), public_path('uploads/room/'. $request->video_thumbnail_names));

            // }

            // if($request->hasFile('video_thumbnail')){
            //     $room->video_thumbnail = ImageManager::upload('uploads/room', $request->video_thumbnail);
            // }
            // if($request->hasFile('thumbnail')){
            //     $room->thumbnail = ImageManager::upload('uploads/room', $request->thumbnail);
            // }
            $room->created_by = auth()->user()->id;
            $checkin = [];
            if ($request->checkin) {
                foreach ($request->checkin['en']['title'] as $key => $value) {
                    $checkinItem['title'] = $request->checkin['en']['title'][$key];
                    array_push($checkin, $checkinItem);
                }
                $room->checkin = $checkin;
            }

            $checkout = [];
            if ($request->checkout) {
                foreach ($request->checkout['en']['title'] as $key => $value) {
                    $checkoutItem['title'] = $request->checkout['en']['title'][$key];
                    array_push($checkout, $checkoutItem);
                }
                $room->checkout = $checkout;
            }
            $room->save();

            $home_stay_id = $room->id;
            $home_stay_gallery = new HomeStayGallery();
            $home_stay_gallery->home_stay_id = $home_stay_id;
            if ($request->filled('image_names')) {
                $home_stay_gallery->image = explode(' ', $request->image_names);
                foreach ($home_stay_gallery->image as $key => $image) {
                    $directory = public_path('uploads/home_stay_gallery');
                    if (!\File::exists($directory)) {
                        \File::makeDirectory($directory, 0777, true);
                    }

                    $image = \File::move(public_path('uploads/temp/' . $image), public_path('uploads/home_stay_gallery/'. $image));
                }
            }
            $home_stay_gallery->save();


            foreach($request->lang as $index =>$key){
                if($key != 'en'){
                    $checkin = [];
                    if(isset($request->checkin[$key])){
                        foreach($request->checkin[$key]['title'] as $value_key => $value){
                            $item['title'] = $request->checkin[$key]['title'][$value_key];
                            array_push($checkin, $item);
                        }
                    }
                    Translation::updateOrInsert(
                        [
                        'translationable_type' => 'App\Models\Room',
                        'translationable_id' => $room->id,
                        'locale' => $key,
                        'key' => 'checkin'
                        ],[
                        'value' => json_encode($checkin)
                        ]
                    );
                }
            }
            foreach($request->lang as $index =>$key){
                if($key != 'en'){
                    $checkout = [];
                    if(isset($request->checkout[$key])){
                        foreach($request->checkout[$key]['title'] as $value_key => $value){
                            $item['title'] = $request->checkout[$key]['title'][$value_key];
                            array_push($checkout, $item);
                        }
                    }
                    Translation::updateOrInsert(
                        [
                        'translationable_type' => 'App\Models\Room',
                        'translationable_id' => $room->id,
                        'locale' => $key,
                        'key' => 'checkout'
                        ],[
                        'value' => json_encode($checkout)
                        ]
                    );
                }
            }
            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->title[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Room',
                        'translationable_id' => $room->id,
                        'locale' => $key,
                        'key' => 'title',
                        'value' => $request->title[$index],
                    ));
                }
                if ($request->special_note[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Room',
                        'translationable_id' => $room->id,
                        'locale' => $key,
                        'key' => 'special_note',
                        'value' => $request->special_note[$index],
                    ));
                }
                if ($request->description[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Room',
                        'translationable_id' => $room->id,
                        'locale' => $key,
                        'key' => 'description',
                        'value' => $request->description[$index],
                    ));
                }
                // if ($request->column_1[$index] && $key != 'en') {
                //     array_push($data, array(
                //         'translationable_type' => 'App\Models\Room',
                //         'translationable_id' => $room->id,
                //         'locale' => $key,
                //         'key' => 'column_1',
                //         'value' => $request->column_1[$index],
                //     ));
                // }
                // if ($request->column_2[$index] && $key != 'en') {
                //     array_push($data, array(
                //         'translationable_type' => 'App\Models\Room',
                //         'translationable_id' => $room->id,
                //         'locale' => $key,
                //         'key' => 'column_2',
                //         'value' => $request->column_2[$index],
                //     ));
                // }
                // if ($request->column_3[$index] && $key != 'en') {
                //     array_push($data, array(
                //         'translationable_type' => 'App\Models\Room',
                //         'translationable_id' => $room->id,
                //         'locale' => $key,
                //         'key' => 'column_3',
                //         'value' => $request->column_3[$index],
                //     ));
                // }
            }
            Translation::insert($data);

            DB::commit();
            $output = [
                'success' => 1,
                'msg' => __('Created successfully')
            ];

        }catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            $output = [
                'success' => 0,
                'msg' => __('Something went wrong')
            ];
        }

        return redirect()->route('admin.room.index')->with($output);
    }
    public function show($id)
    {
        //
    }
    public function edit($id)
    {
        if (!auth()->user()->can('room.edit')) {
            abort(403, 'Unauthorized action.');
        }
        $room = Room::withoutGlobalScopes()->with('translations')->findOrFail($id);
        $amenities = HomeStayAmenity::findorFail(4);
        $home_stay_gallery = HomeStayGallery::where('home_stay_id', $room->id)->first();
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];
        if (request()->ajax()) {
            $lang = request('lang');
            $key = request('key');
            if(request()->type == 'checkin'){
                $tr = view('backends.room._checkin_detail_tr', compact('key', 'lang'))->render();
            }else{
                $tr = view('backends.room._checkout_detail_tr', compact('key', 'lang'))->render();
            }
            return response()->json([
                'tr' => $tr
            ]);
        }
        return view('backends.room.edit', compact('room', 'language', 'default_lang', 'amenities', 'home_stay_gallery'));
    }
    public function update(Request $request, $id)
    {
        // dd($request->all());
        if (!auth()->user()->can('room.edit')) {
            abort(403, 'Unauthorized action.');
        }
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'number' => 'required',

        ]);

        if (is_null($request->title[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'Title', 'The title field is required!'
                );
            });
        }
        // if (is_null($request->special_note[array_search('en', $request->lang)])) {
        //     $validator->after(function ($validator) {
        //         $validator->errors()->add(
        //             'special_note', 'The special note field is required!'
        //         );
        //     });
        // }
        if (is_null($request->description[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'description', 'The description field is required!'
                );
            });
        }

        if ($validator->fails()) {
            return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with(['success' => 0, 'msg' => __('Invalid form input')]);
        }

        try{
            DB::beginTransaction();
            $room = Room::findOrFail($id);
            $room->title        = $request->title[array_search('en', $request->lang)];
            $room->special_note = $request->special_note[array_search('en', $request->lang)]??null;
            $room->description  = $request->description[array_search('en', $request->lang)];
            // $room->column_1     = $request->column_1[array_search('en', $request->lang)];
            // $room->column_2     = $request->column_2[array_search('en', $request->lang)];
            // $room->column_3     = $request->column_3[array_search('en', $request->lang)];
            // $room->video        = $request->video;
            $room->number       = $request->number;
            $room->adult        = $request->adult;
            $room->child        = $request->child;
            $selectAmenities    = $request->input('amenities',[]);
            $room->amenities    = $selectAmenities;
            $room->pet = $request->pet ? 1 : 0;
            $room->price = $request->price;

            // if ($request->filled('room_thumbnail_names')) {
            //     $room->thumbnail = $request->room_thumbnail_names;
            //     $directory = public_path('uploads/room');
            //     if (!\File::exists($directory)) {
            //         \File::makeDirectory($directory, 0777, true);
            //     }

            //     $thumbnail = \File::move(public_path('uploads/temp/' . $request->room_thumbnail_names), public_path('uploads/room/'. $request->room_thumbnail_names));

            // }

            // if ($request->filled('video_thumbnail_names')) {
            //     $room->video_thumbnail = $request->video_thumbnail_names;
            //     $directory = public_path('uploads/room');
            //     if (!\File::exists($directory)) {
            //         \File::makeDirectory($directory, 0777, true);
            //     }

            //     $video_thumbnail = \File::move(public_path('uploads/temp/' . $request->video_thumbnail_names), public_path('uploads/room/'. $request->video_thumbnail_names));

            // }

            $room->created_by = auth()->user()->id;
            $checkin = [];
            if ($request->checkin) {
                foreach ($request->checkin['en']['title'] as $key => $value) {
                    $checkinItem['title'] = $request->checkin['en']['title'][$key];
                    array_push($checkin, $checkinItem);
                }
                $room->checkin = $checkin;
            }

            $checkout = [];
            if ($request->checkout) {
                foreach ($request->checkout['en']['title'] as $key => $value) {
                    $checkoutItem['title'] = $request->checkout['en']['title'][$key];
                    array_push($checkout, $checkoutItem);
                }
                $room->checkout =$checkout;
            }
            $room->save();

            $home_stay_id = $room->id;
            $home_stay_gallery = HomeStayGallery::where('home_stay_id', $home_stay_id)->first();
            $home_stay_gallery->home_stay_id = $home_stay_id;
            if ($request->filled('gallery_image_names')) {
                $home_stay_gallery->image = explode(' ', $request->gallery_image_names);
                foreach ($home_stay_gallery->image as $key => $image) {
                    $directory = public_path('uploads/home_stay_gallery');
                    if (!\File::exists($directory)) {
                        \File::makeDirectory($directory, 0777, true);
                    }

                    $image = \File::move(public_path('uploads/temp/' . $image), public_path('uploads/home_stay_gallery/'. $image));
                }
            }
            $home_stay_gallery->save();

            foreach($request->lang as $index => $key){
                if($key != 'en'){
                    $checkin = [];
                    if(isset($request->checkin[$key])){
                        foreach($request->checkin[$key]['title'] as $value_key => $value){
                            $item['title'] = $request->checkin[$key]['title'][$value_key];
                            array_push($checkin, $item);
                        }
                    }
                    Translation::updateOrInsert(
                        [
                        'translationable_type' => 'App\Models\Room',
                        'translationable_id' => $room->id,
                        'locale' => $key,
                        'key' => 'checkin'
                        ],[
                        'value' => json_encode($checkin)
                        ]
                    );
                }
            }
            foreach($request->lang as $index =>$key){
                if($key != 'en'){
                    $checkout = [];
                    if(isset($request->checkout[$key])){
                        foreach($request->checkout[$key]['title'] as $value_key => $value){
                            $item['title'] = $request->checkout[$key]['title'][$value_key];
                            array_push($checkout, $item);
                        }
                    }
                    Translation::updateOrInsert(
                        [
                        'translationable_type' => 'App\Models\Room',
                        'translationable_id' => $room->id,
                        'locale' => $key,
                        'key' => 'checkout'
                        ],[
                        'value' => json_encode($checkout)
                        ]
                    );
                }
            }
            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->title[$index] && $key != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Models\Room',
                            'translationable_id' => $room->id,
                            'locale' => $key,
                            'key' => 'title'],
                        ['value' => $request->title[$index]]
                    );
                }
                if ($request->special_note[$index] && $key != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Models\Room',
                            'translationable_id' => $room->id,
                            'locale' => $key,
                            'key' => 'special_note'],
                        ['value' => $request->special_note[$index]]
                    );
                }
                if ($request->description[$index] && $key != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Models\Room',
                            'translationable_id' => $room->id,
                            'locale' => $key,
                            'key' => 'description'],
                        ['value' => $request->description[$index]]
                    );
                }
                // if ($request->column_1[$index] && $key != 'en') {
                //     Translation::updateOrInsert(
                //         ['translationable_type' => 'App\Models\Room',
                //             'translationable_id' => $room->id,
                //             'locale' => $key,
                //             'key' => 'column_1'],
                //         ['value' => $request->column_1[$index]]
                //     );
                // }
                // if ($request->column_2[$index] && $key != 'en') {
                //     Translation::updateOrInsert(
                //         ['translationable_type' => 'App\Models\Room',
                //             'translationable_id' => $room->id,
                //             'locale' => $key,
                //             'key' => 'column_2'],
                //         ['value' => $request->column_2[$index]]
                //     );
                // }
                // if ($request->column_3[$index] && $key != 'en') {
                //     Translation::updateOrInsert(
                //         ['translationable_type' => 'App\Models\Room',
                //             'translationable_id' => $room->id,
                //             'locale' => $key,
                //             'key' => 'column_3'],
                //         ['value' => $request->column_3[$index]]
                //     );
                // }
            }
            Translation::insert($data);

            DB::commit();
            $output = [
                'success' => 1,
                'msg' => __('Updated successfully')
            ];

        }catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            $output = [
                'success' => 0,
                'msg' => __('Something went wrong')
            ];
        }

        return redirect()->route('admin.room.index')->with($output);
    }
    public function destroy($id)
    {
        if (!auth()->user()->can('room.delete')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            DB::beginTransaction();
            $room = Room::findOrFail($id);
            $home_stay_gallery = HomeStayGallery::where('home_stay_id', $room->id)->first();
            $translation = Translation::where('translationable_type','App\Models\Room')
                                        ->where('translationable_id',$room->id);

            $translation->delete();
            $home_stay_gallery->delete();
            $room->delete();
            if($home_stay_gallery->image){
                foreach ($home_stay_gallery->image as $key => $image) {
                    ImageManager::delete(public_path('uploads/home_stay_gallery/' . $image));
                }
            }
            $rooms = Room::latest('id')->paginate(10);
            $view = view('backends.room._table', compact('rooms'))->render();

            DB::commit();
            $output = [
                'status' => 1,
                'view' => $view,
                'msg' => __('Deleted successfully')
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $output = [
                'status' => 0,
                'msg' => __('Something went wrong')
            ];
        }

        return response()->json($output);
    }
    public function updateStatus (Request $request)
    {
        try {
            DB::beginTransaction();

            $room = Room::findOrFail($request->id);
            $room->status = $room->status == 'active' ? 'inactive' : 'active';
            $room->save();

            $output = ['status' => 1, 'msg' => __('Status updated')];

            DB::commit();
        } catch (Exception $e) {
            $output = ['status' => 0, 'msg' => __('Something went wrong')];
            DB::rollBack();
        }

        return response()->json($output);
    }
}
