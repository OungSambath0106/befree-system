<?php

namespace App\Http\Controllers\Backends;

use Exception;
use App\Models\Translation;
use Illuminate\Http\Request;
use App\helpers\ImageManager;
use App\Models\BusinessSetting;
use App\Models\HomeStayAmenity;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class HomeStayAmenityController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('amenity.view')) {
            abort(403, 'Unauthorized action.');
        }
        $data['amenities'] = HomeStayAmenity::latest('id')->paginate(10);
        return view('backends.amenity.index', $data);
    }
    public function create()
    {
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];
        if (request()->ajax()) {
            $lang = request('lang');
            $key = request('key');
            $tr = view('backends.amenity._amenity_detail_tr', compact('key', 'lang'))->render();
            return response()->json([
                'tr' => $tr
            ]);
        }
        return view('backends.amenity.create', compact('language', 'default_lang'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if (is_null($request->title[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'title', 'The title field is required!'
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
            $amenity = new HomeStayAmenity();
            $amenity->title = $request->title[array_search('en', $request->lang)];
            $amenity->created_by = auth()->user()->id;
            $value = [];
            if ($request->value) {
                foreach ($request->value['en']['title'] as $key => $val) {
                    $item['title'] = $request->value['en']['title'][$key];
                    $item['description'] = $request->value['en']['description'][$key];

                    $request_image = $request->value['en']['image'] ?? 0;

                    if($request_image != 0) {
                        if (in_array($key, array_keys($request->value['en']['image']))) {
                            $image = ImageManager::upload('uploads/amenity/', $request->value['en']['image'][$key]);
                            $item['image'] = $image;
                        } else {
                            $item['image'] = $request->value['en']['old_image'][$key] ?? null;
                        }
                    } else {
                        $item['image'] = $request->value['en']['old_image'][$key];
                    }

                    array_push($value, $item);
                }
                $amenity->value = $value;
            }
            $amenity->save();

            foreach ($request->lang as $index => $key) {
                if ($key != 'en') {
                    $value = [];
                    if (isset($request->value[$key])) {
                        foreach ($request->value[$key]['title'] as $value_key => $val) {
                            $item['title'] = $request->value[$key]['title'][$value_key];
                            $item['description'] = $request->value[$key]['description'][$value_key];
                            $item['image'] = json_decode($amenity->value, true)[$value_key]['image'];
                            array_push($value, $item);
                        }
                    }

                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\HomeStayAmenity',
                            'translationable_id' => $amenity->id,
                            'locale' => $key,
                            'key' => 'value'
                        ], [
                            'value' => json_encode($value)
                        ]
                    );
                }
            }
            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->title[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\HomeStayAmenity',
                        'translationable_id' => $amenity->id,
                        'locale' => $key,
                        'key' => 'title',
                        'value' => $request->title[$index],
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

        } catch (Exception $e) {
            DB::rollBack();
            $output = [
                'success' => 0,
                'msg' => __('Something went wrong'),
            ];
        }
        return redirect()->route('admin.amenities.index')->with($output);
    }
    public function show($id)
    {
        //
    }
    public function edit($id)
    {
        if (!auth()->user()->can('amenity.edit')) {
            abort(403, 'Unauthorized action.');
        }
        $amenity = HomeStayAmenity::withoutGlobalScopes()->with('translations')->findOrFail($id);
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];
        if (request()->ajax()) {
            $lang = request('lang');
            $key = request('key');
            $tr = view('backends.amenity._amenity_detail_tr', compact('key', 'lang'))->render();
            return response()->json([
                'tr' => $tr
            ]);
        }
        return view('backends.amenity.edit', compact('amenity', 'language', 'default_lang'));
    }
    public function update(Request $request, $id)
    {
        // dd($request->all());
        if (!auth()->user()->can('amenity.edit')) {
            abort(403, 'Unauthorized action.');
        }
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if (is_null($request->title[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'title', 'The title field is required!'
                );
            });
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with(['success' => 0, 'msg' => __('Invalid form input')]);
        }

        try {
            DB::beginTransaction();
            $amenity = HomeStayAmenity::find($id);
            $amenity->title = $request->title[array_search('en', $request->lang)];
            $amenity->created_by = auth()->user()->id;
            $value = [];
            if ($request->value) {
                foreach ($request->value['en']['title'] as $key => $val) {
                    $item['title'] = $request->value['en']['title'][$key];
                    $item['description'] = $request->value['en']['description'][$key];
                    $request_image = $request->value['en']['image'] ?? 0;

                    if($request_image != 0) {
                        if (in_array($key, array_keys($request->value['en']['image']))) {
                            $image = ImageManager::update('uploads/amenity/', $request->value['en']['old_image'][$key], $request->value['en']['image'][$key]);
                            $item['image'] = $image;
                        } else {
                            $item['image'] = $request->value['en']['old_image'][$key] ?? null;
                        }
                    } else {
                        $item['image'] = $request->value['en']['old_image'][$key];
                    }

                    array_push($value, $item);
                }
                $amenity->value = $value;
            }

            $amenity->save();


           foreach ($request->lang as $index => $key) {
               if ($key != 'en') {
                   $value = [];
                   if (isset($request->value[$key])) {
                       foreach ($request->value[$key]['title'] as $value_key => $val) {

                           $item['title'] = $request->value[$key]['title'][$value_key];
                           $item['description'] = $request->value[$key]['description'][$value_key];
                           $item['image'] = $amenity->value[$value_key]['image'];
                           array_push($value, $item);
                       }
                   }

                   Translation::updateOrInsert(
                       [
                           'translationable_type' => 'App\Models\HomeStayAmenity',
                           'translationable_id' => $amenity->id,
                           'locale' => $key,
                           'key' => 'value'
                       ], [
                           'value' => json_encode($value)
                       ]
                   );
               }
           }

            $data = [];
            foreach ($request->lang as $index => $key) {
               if ($request->title[$index] && $key != 'en') {
                   Translation::updateOrInsert(
                       ['translationable_type' => 'App\Models\HomeStayAmenity',
                           'translationable_id' => $amenity->id,
                           'locale' => $key,
                           'key' => 'title'],
                       ['value' => $request->title[$index]]
                   );
               }
            }
            Translation::insert($data);
            DB::commit();

            $table = $this->renderTable();
            $view = $table['view'];
            $output = [
                'success' => 1,
                'msg' => __('Update successfully'),
                'view' => $view,
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            $output = [
                'success' => 0,
                'msg' => __('Something went wrong'),
            ];
        }

        return redirect()->route('admin.amenities.index')->with($output);

    }
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $amenity = HomeStayAmenity::findOrFail($id);
            $translation = Translation::where('translationable_type','App\Models\HomeStayAmenity')
                                        ->where('translationable_id',$amenity->id);
            $translation->delete();
            $amenity->delete();
            if ($amenity->image) {
                ImageManager::delete(public_path('uploads/amenity/' . $amenity->image));
            }

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

            $amenity = HomeStayAmenity::findOrFail($request->id);
            $amenity->status = $amenity->status == 'active' ? 'inactive' : 'active';
            $amenity->save();

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
        $amenities = HomeStayAmenity::latest('id')->paginate(10);
        $view = view('backends.amenity._table', compact('amenities'))->render();

        return ['view' => $view];
    }
}
