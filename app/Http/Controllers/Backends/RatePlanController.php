<?php

namespace App\Http\Controllers\Backends;

use Exception;
use App\Models\Room;
use App\Models\RatePlan;
use App\Models\Translation;
use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use App\Models\HomeStayAmenity;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RatePlanController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->can('rate.view')) {
            abort(403, 'Unauthorized action.');
        }
        $room_id = $room_id = $request->input('room_id');
        $ratePlans = RatePlan::where('room_id',$room_id)->latest('id')->paginate(10);
        return view('backends.rate-plan.index',compact('ratePlans','room_id'));
    }
    public function create()
    {
        if (!auth()->user()->can('rate.create')) {
            abort(403, 'Unauthorized action.');
        }
        $amenities = HomeStayAmenity::withoutGlobalScopes()->findorFail(3);
        // return json_decode(json_decode($amenities->value, true), true);
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];
        return view('backends.rate-plan.create',compact('language','default_lang','amenities'));
    }
    public function store(Request $request)
    {
        if (!auth()->user()->can('rate.create')) {
            abort(403, 'Unauthorized action.');
        }
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ]);

        if (is_null($request->title[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'title', 'The title field is required!'
                );
            });
        }
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
            $ratePlan = new RatePlan();
            $ratePlan->title = $request->title[array_search('en', $request->lang)];
            $ratePlan->description = $request->description[array_search('en', $request->lang)];
            $ratePlan->created_by = auth()->user()->id;
            $ratePlan->room_id = $request->room_id;
            $ratePlan->type = $request->type;
            $ratePlan->price = $request->price;
            if($ratePlan->type == 'package'){
                $selectedPackages = $request->input('special_package', []);
                $ratePlan->special_package = $selectedPackages;
            }
            // dd($request->all());
            $ratePlan->save();
            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->title[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\RatePlan',
                        'translationable_id' => $ratePlan->id,
                        'locale' => $key,
                        'key' => 'title',
                        'value' => $request->title[$index],
                    ));
                }
                if ($request->description[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\RatePlan',
                        'translationable_id' => $ratePlan->id,
                        'locale' => $key,
                        'key' => 'description',
                        'value' => $request->description[$index],
                    ));
                }
            }
            Translation::insert($data);

            DB::commit();
            $table = $this->renderTable();
            $view = $table['view'];
            $output = [
                'success' => 1,
                'msg' => __('Create successfully'),
                'view' => $view,
            ];
        }catch(Exception $e){
            dd($e);
            DB::rollBack();
            $output = [
                'success' => 0,
                'msg' => __('Something went wrong'),
            ];
        }
        return redirect()->route('admin.rate_plan.index',['room_id' => $request->room_id])->with($output);
    }
    public function show($id)
    {
        //
    }
    public function edit($id)
    {
        if (!auth()->user()->can('rate.edit')) {
            abort(403, 'Unauthorized action.');
        }
        $ratePlan = RatePlan::withoutGlobalScopes()->with('translations')->findOrFail($id);
        $amenities = HomeStayAmenity::findorFail(3);
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];
        return view('backends.rate-plan.edit',compact('language','default_lang','ratePlan','amenities'));
    }
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('rate.edit')) {
            abort(403, 'Unauthorized action.');
        }
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ]);

        if (is_null($request->title[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'title', 'The title field is required!'
                );
            });
        }
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
            $ratePlan = RatePlan::findOrFail($id);
            $ratePlan->title = $request->title[array_search('en', $request->lang)];
            $ratePlan->description = $request->description[array_search('en', $request->lang)];
            $ratePlan->created_by = auth()->user()->id;
            // $ratePlan->room_id = $request->room_id;
            $ratePlan->type = $request->type;
            $ratePlan->price = $request->price;
            if($ratePlan->type == 'package'){
                $selectedPackages = $request->input('special_package', []);
                $ratePlan->special_package = $selectedPackages;
            }
            $ratePlan->save();
            $data = [];
            foreach ($request->lang as $index => $key) {
               if ($request->title[$index] && $key != 'en') {
                   Translation::updateOrInsert(
                       ['translationable_type' => 'App\Models\RatePlan',
                           'translationable_id' => $ratePlan->id,
                           'locale' => $key,
                           'key' => 'title'],
                       ['value' => $request->title[$index]]
                   );
               }
               if ($request->description[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    ['translationable_type' => 'App\Models\RatePlan',
                        'translationable_id' => $ratePlan->id,
                        'locale' => $key,
                        'key' => 'description'],
                    ['value' => $request->description[$index]]
                );
            }
            }
            Translation::insert($data);

            DB::commit();
            // $table = $this->renderTable();
            // $view = $table['view'];
            $output = [
                'success' => 1,
                'msg' => __('Update successfully'),
                // 'view' => $view,
            ];
        }catch(Exception $e){
            DB::rollBack();
            dd($e);
            $output = [
                'success' => 0,
                'msg' => __('Something went wrong'),
            ];
        }
        return redirect()->route('admin.rate_plan.index', ['room_id' => $request->room_id])->with($output);
    }
    public function destroy($id)
    {
        if (!auth()->user()->can('rate.delete')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            DB::beginTransaction();
            $ratePlan = RatePlan::findOrFail($id);
            $translation = Translation::where('translationable_type','App\Models\RatePlan')
                                        ->where('translationable_id',$ratePlan->id);

            $translation->delete();
            $ratePlan->delete();

            $table = $this->renderTable();
            $view = $table['view'];

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

            $ratePlan = RatePlan::findOrFail($request->id);
            $ratePlan->status = $ratePlan->status == 'active' ? 'inactive' : 'active';
            $ratePlan->save();

            $output = ['status' => 1, 'msg' => __('Status updated')];

            DB::commit();
        } catch (Exception $e) {
            $output = ['status' => 0, 'msg' => __('Something went wrong')];
            DB::rollBack();
        }

        return response()->json($output);
    }
    public function renderTable()
    {
        $ratePlans = RatePlan::latest('id')->paginate(10);
        $view = view('backends.rate-plan._table', compact('ratePlans'))->render();

        return ['view' => $view];
    }
}
