<?php

namespace App\Http\Controllers\Backends;

use Exception;
use App\Models\Facility;
use App\Models\Translation;
use Illuminate\Http\Request;
use App\helpers\ImageManager;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class FacilityController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('facility.view')) {
            abort(403, 'Unauthorized action.');
        }
        $facilities = Facility::latest('id')->paginate(10);
        return view('backends.facility.index', compact('facilities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('facility.create')) {
            abort(403, 'Unauthorized action.');
        }
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];

        if (request()->ajax()) {
            $lang = request('lang');
            $key = request('key');
        }

        return view('backends.facility.create', compact('language', 'default_lang'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('facility.create')) {
            abort(403, 'Unauthorized action.');
        }
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ]);
        if (is_null($request->title[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'Title', 'The title field is required!'
                );
            });
        }
        if (is_null($request->description[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'Description', 'The description field is required!'
                );
            });
        }

        try {
        // dd($request->all());
            DB::beginTransaction();
            $facility = new Facility;
            $facility->title = $request->title[array_search('en', $request->lang)];
            $facility->description = $request->description[array_search('en', $request->lang)];
            $facility->created_by = auth()->user()->id;
            if ($request->hasFile('thumbnail')) {
                $facility->thumbnail = ImageManager::upload('uploads/facility',$request->thumbnail);
            }
            if ($request->hasFile('image')) {
                $facility->image = ImageManager::upload('uploads/facility',$request->image);
            }
            $facility->save();

            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->title[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Facility',
                        'translationable_id' => $facility->id,
                        'locale' => $key,
                        'key' => 'title',
                        'value' => $request->title[$index],
                    ));
                }

                if ($request->description[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Facility',
                        'translationable_id' => $facility->id,
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

        } catch (Exception $e) {
            DB::rollBack();
            $output = [
                'success' => 0,
                'msg' => __('Something went wrong'),
            ];
        }

        return redirect()->route('admin.facility.index')->with($output);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('facility.edit')) {
            abort(403, 'Unauthorized action.');
        }
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];

        if (request()->ajax()) {
            $lang = request('lang');
            $key = request('key');
        }

        $facility = Facility::withoutGlobalScopes()->with('translations')->findOrFail($id);
        return view('backends.facility.edit', compact('facility', 'language', 'default_lang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('facility.edit')) {
            abort(403, 'Unauthorized action.');
        }
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ]);
        if (is_null($request->title[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'Title', 'The title field is required!'
                );
            });
        }
        if (is_null($request->description[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'Description', 'The description field is required!'
                );
            });
        }
        try{
            // dd($request->all());
        DB::beginTransaction();
            $facility = Facility::findOrFail($id); //Facility;
            $facility->title = $request->title[array_search('en', $request->lang)];
            $facility->description = $request->description[array_search('en', $request->lang)];
            $facility->created_by = auth()->user()->id;
            if ($request->hasFile('thumbnail')) {
                $facility->thumbnail = ImageManager::update('uploads/facility/', $facility->thumbnail, $request->thumbnail);
            }
            if ($request->hasFile('image')) {
                $facility->image = ImageManager::update('uploads/facility/', $facility->image, $request->image);
            }
            $facility->save();

            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->title[$index] && $key != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Models\Facility',
                            'translationable_id' => $facility->id,
                            'locale' => $key,
                            'key' => 'title'],
                        ['value' => $request->title[$index]]
                    );
                }

                if ($request->description[$index] && $key != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Models\Facility',
                            'translationable_id' => $facility->id,
                            'locale' => $key,
                            'key' => 'description'],
                        ['value' => $request->description[$index]]
                    );
                }

            }
            Translation::insert($data);

            DB::commit();
            $table = $this->renderTable();
            $view = $table['view'];

            $output = [
                'success' => 1,
                'msg' => __('Edited successfully'),
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
        return redirect()->route('admin.facility.index')->with($output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('facility.delete')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            DB::beginTransaction();
            $facility = Facility::findOrFail($id);
            $facility->delete();

            if ($facility->thumbnail) {
                ImageManager::delete(public_path('uploads/facility/' . $facility->thumbnail));
            }
            if ($facility->image) {
                ImageManager::delete(public_path('uploads/facility/' . $facility->image));
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

            $facility = Facility::findOrFail($request->id);
            $facility->status = $facility->status == 'active' ? 'inactive' : 'active';
            $facility->save();

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
        $facilities = Facility::latest('id')->paginate(10);
        $view = view('backends.facility._table', compact('facilities'))->render();

        return ['view' => $view];
    }
}
