<?php

namespace App\Http\Controllers\Backends;

use App\helpers\ImageManager;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\HomeStayAmenity;
use App\Models\Staycation;
use App\Models\Translation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StaycationController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('staycation.view')) {
            abort(403, 'Unauthorized action.');
        }
        $staycations = Staycation::latest('id')->paginate(10);
        return view('backends.staycation.index',compact('staycations'));
    }

    public function create()
    {
        if (!auth()->user()->can('staycation.create')) {
            abort(403, 'Unauthorized action.');
        }
        $amenities = HomeStayAmenity::findorFail(5);
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];
        return view('backends.staycation.create', compact('language', 'default_lang', 'amenities'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('staycation.create')) {
            abort(403, 'Unauthorized action.');
        }
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if (is_null($request->title[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'Title', 'The title field is required!'
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
        // dd($request->all());

            DB::beginTransaction();
            $staycation = new Staycation();
            $staycation->title        = $request->title[array_search('en', $request->lang)];
            $selectAmenities = $request->input('amenities', []);
            $staycation->amenities = $selectAmenities;
            
            if ($request->hasFile('thumbnail')) {
                $staycation->thumbnail = ImageManager::upload('uploads/staycation',$request->thumbnail);
            }

            $staycation->created_by = auth()->user()->id;
            $staycation->save();

            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->title[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Staycation',
                        'translationable_id' => $staycation->id,
                        'locale' => $key,
                        'key' => 'title',
                        'value' => $request->title[$index],
                    ));
                }
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

        return redirect()->route('admin.staycation.index')->with($output);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        if (!auth()->user()->can('staycation.edit')) {
            abort(403, 'Unauthorized action.');
        }
        $staycation = Staycation::withoutGlobalScopes()->with('translations')->findOrFail($id);
        $amenities = HomeStayAmenity::findorFail(5);
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];

        return view('backends.staycation.edit', compact('staycation', 'language', 'default_lang', 'amenities'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        if (!auth()->user()->can('staycation.edit')) {
            abort(403, 'Unauthorized action.');
        }
        $validator = Validator::make($request->all(), [
            'title' => 'required',

        ]);

        if (is_null($request->title[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'Title', 'The title field is required!'
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
            $staycation = Staycation::findOrFail($id);
            $staycation->title        = $request->title[array_search('en', $request->lang)];
            $selectAmenities    = $request->input('amenities',[]);
            $staycation->amenities    = $selectAmenities;

            if ($request->hasFile('thumbnail')) {
                $staycation->thumbnail = ImageManager::update('uploads/staycation/', $staycation->thumbnail, $request->thumbnail);
            }

            $staycation->created_by = auth()->user()->id;
            $staycation->save();

            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->title[$index] && $key != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Models\Staycation',
                            'translationable_id' => $staycation->id,
                            'locale' => $key,
                            'key' => 'title'],
                        ['value' => $request->title[$index]]
                    );
                }
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

        return redirect()->route('admin.staycation.index')->with($output);
    }

    public function destroy($id)
    {
        if (!auth()->user()->can('staycation.delete')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            DB::beginTransaction();
            $staycation = Staycation::findOrFail($id);
            $translation = Translation::where('translationable_type','App\Models\Staycation')
                                        ->where('translationable_id',$staycation->id);

            $translation->delete();
            $staycation->delete();
            $staycations = Staycation::latest('id')->paginate(10);
            $view = view('backends.staycation._table', compact('staycations'))->render();

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

            $staycation = Staycation::findOrFail($request->id);
            $staycation->status = $staycation->status == 'active' ? 'inactive' : 'active';
            $staycation->save();

            $output = ['status' => 1, 'msg' => __('Status updated')];

            DB::commit();
        } catch (Exception $e) {
            $output = ['status' => 0, 'msg' => __('Something went wrong')];
            DB::rollBack();
        }

        return response()->json($output);
    }
}
