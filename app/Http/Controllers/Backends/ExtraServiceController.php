<?php

namespace App\Http\Controllers\Backends;

use Exception;
use App\Models\Translation;
use App\Models\ExtraService;
use Illuminate\Http\Request;
use App\helpers\ImageManager;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ExtraServiceController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('extra_service.view')) {
            abort(403, 'Unauthorized action.');
        }
        $data['extra_services'] = ExtraService::latest('id')->paginate(10);
        return view('backends.extra_service.index', $data);
    }

    public function create()
    {
        if (!auth()->user()->can('extra_service.create')) {
            abort(403, 'Unauthorized action.');
        }
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];
        if (request()->ajax()) {
            $lang = request('lang');
            $key = request('key');
            $tr = view('backends.extra_service._extra_service_detail_tr', compact('key', 'lang'))->render();
            return response()->json([
                'tr' => $tr
            ]);
        }
        return view('backends.extra_service.create', compact('language', 'default_lang'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('extra_service.create')) {
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
        try{
            DB::beginTransaction();
            $extra_service = new ExtraService();
            $extra_service->title = $request->title[array_search('en', $request->lang)];
            $extra_service->price = $request->price;
            $extra_service->created_by = auth()->user()->id;
            if ($request->hasFile('thumbnail')) {
                $extra_service->thumbnail = ImageManager::upload('uploads/service',$request->thumbnail);
            }
            $description = [];
            if ($request->description) {
                foreach ($request->description['en']['title'] as $key => $val) {
                    $item['option'] = $request->description['en']['option'][$key];
                    $item['title'] = $request->description['en']['title'][$key];
                    array_push($description, $item);
                }
                $extra_service->description = $description;
            }
            // dd($request->all());
            $extra_service->save();
        
            foreach ($request->lang as $index => $key) {
                if ($key != 'en') {
                    $description = [];
                    if (isset($request->description[$key])) {
                        foreach ($request->description[$key]['title'] as $description_key => $val) {
                            $item['option'] = $request->description[$key]['option'][$description_key];
                            $item['title'] = $request->description[$key]['title'][$description_key];
                            array_push($description, $item);
                        }
                    }

                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\ExtraService',
                            'translationable_id' => $extra_service->id,
                            'locale' => $key,
                            'key' => 'description',
                        ], [
                            'value' => json_encode($description)
                        ]
                    );
                }
            }          
            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->title[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\ExtraService',
                        'translationable_id' => $extra_service->id,
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
            dd($e->getMessage(), $e->getTrace());
            $output = [
                'success' => 0,
                'msg' => __('Something went wrong'),
            ];
        }
        return redirect()->route('admin.extra-service.index')->with($output);
    }
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        if (!auth()->user()->can('extra_service.edit')) {
            abort(403, 'Unauthorized action.');
        }
        $extra_service = ExtraService::withoutGlobalScopes()->with('translations')->findOrFail($id);
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];
        if (request()->ajax()) {
            $lang = request('lang');
            $key = request('key');
            $tr = view('backends.extra_service._extra_service_detail_tr', compact('key', 'lang'))->render();
            return response()->json([
                'tr' => $tr
            ]);
        }
        return view('backends.extra_service.edit', compact('extra_service', 'language', 'default_lang'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('extra_service.edit')) {
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
        try{
            DB::beginTransaction();
            $extra_service = ExtraService::findOrFail($id);
            $extra_service->title = $request->title[array_search('en', $request->lang)];
            $extra_service->price = $request->price;
            $extra_service->created_by = auth()->user()->id;
            if($request->hasFile('thumbnail')) {
                $extra_service->thumbnail = ImageManager::update('uploads/service',$extra_service->thumbnail, $request->thumbnail);
            }
            $description = [];
            if ($request->description) {
                foreach ($request->description['en']['title'] as $key => $val) {
                    $item['option'] = $request->description['en']['option'][$key];
                    $item['title'] = $request->description['en']['title'][$key];
                    array_push($description, $item);
                }
                $extra_service->description = $description;
            }
            $extra_service->save();
            
            foreach ($request->lang as $index => $key) {
                if ($key != 'en') {
                    $description = [];
                    if (isset($request->description[$key])) {
                        foreach ($request->description[$key]['title'] as $description_key => $val) {
                            $item['option'] = $request->description[$key]['option'][$description_key];
                            $item['title'] = $request->description[$key]['title'][$description_key];
                            array_push($description, $item);
                        }
                    }

                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\ExtraService',
                            'translationable_id' => $extra_service->id,
                            'locale' => $key,
                            'key' => 'description',
                        ], [
                            'value' => json_encode($description)
                        ]
                    );
                }
            }          
            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->title[$index] && $key != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Models\ExtraService',
                            'translationable_id' => $extra_service->id,
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
                'msg' => __('Updated successfully'),
                'view' => $view,
            ];

        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            $output = [
                'success' => 0,
                'msg' => __('Something went wrong'),
            ];
        }
        return redirect()->route('admin.extra-service.index')->with($output);
    }

    public function destroy($id)
    {
        if (!auth()->user()->can('extra_service.delete')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            DB::beginTransaction();
            $extra_service = ExtraService::findOrFail($id);
            $translation = Translation::where('translationable_type','App\Models\ExtraService')
                                        ->where('translationable_id',$extra_service->id);

            $translation->delete();
            $extra_service->delete();
            if ($extra_service->thumbnail) {
                ImageManager::delete(public_path('uploads/service/' . $extra_service->thumbnail));
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
            $extra_service = ExtraService::findOrFail($request->id);
            $extra_service->status = $extra_service->status == 'active' ? 'inactive' : 'active';
            $extra_service->save();

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
        $extra_services = ExtraService::latest('id')->paginate(10);
        $view = view('backends.extra_service._table', compact('extra_services'))->render();

        return ['view' => $view];
    }
}
