<?php

namespace App\Http\Controllers\Backends;

use Exception;
use App\Models\Service;
use App\Models\Translation;
use Illuminate\Http\Request;
use App\helpers\ImageManager;
use App\Models\ServiceGallery;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('service.view')) {
            abort(403, 'Unauthorized action.');
        }
        $data['services'] = Service::latest('id')->paginate(10);
        return view('backends.service.index', $data);
    }

    public function create()
    {
        if (!auth()->user()->can('service.create')) {
            abort(403, 'Unauthorized action.');
        }
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];
        if (request()->ajax()) {
            $lang = request('lang');
            $key = request('key');
            $tr = view('backends.service._service_detail_tr', compact('key', 'lang'))->render();
            return response()->json([
                'tr' => $tr
            ]);
        }
        return view('backends.service.create', compact('language', 'default_lang'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('service.create')) {
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
            $service = new Service();
            $service->title = $request->title[array_search('en', $request->lang)];
            $service->description = $request->description[array_search('en', $request->lang)];
            $service->created_by = auth()->user()->id;

            if ($request->filled('thumbnail_image_names')) {
                $service->thumbnail = $request->thumbnail_image_names;
                $directory = public_path('uploads/service');
                if (!\File::exists($directory)) {
                    \File::makeDirectory($directory, 0777, true);
                }

                $image = \File::move(public_path('uploads/temp/' . $request->thumbnail_image_names), public_path('uploads/service/'. $request->thumbnail_image_names));

            }

            $extra_info = [];
            if ($request->extra_info) {
                foreach ($request->extra_info['en']['title'] as $key => $val) {
                    $item['title'] = $request->extra_info['en']['title'][$key];
                    $item['description'] = $request->extra_info['en']['description'][$key];
                    array_push($extra_info, $item);
                }
                $service->extra_info = $extra_info;
            }
            // dd($request->all());
            $service->save();
            $service_id = $service->id;
            $service_gallery = new ServiceGallery();
            $service_gallery->service_id = $service_id;
            if ($request->filled('image_names')) {
                $service_gallery->image = explode(' ', $request->image_names);
                foreach ($service_gallery->image as $key => $image) {
                    $directory = public_path('uploads/service_gallery');
                    if (!\File::exists($directory)) {
                        \File::makeDirectory($directory, 0777, true);
                    }

                    $image = \File::move(public_path('uploads/temp/' . $image), public_path('uploads/service_gallery/'. $image));
                }
            }
            $service_gallery->save();

            foreach ($request->lang as $index => $key) {
                if ($key != 'en') {
                    $extra_info = [];
                    if (isset($request->extra_info[$key])) {
                        foreach ($request->extra_info[$key]['title'] as $extra_info_key => $val) {
                            $item['title'] = $request->extra_info[$key]['title'][$extra_info_key];
                            $item['description'] = $request->extra_info[$key]['description'][$extra_info_key];
                            array_push($extra_info, $item);
                        }
                    }

                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\Service',
                            'translationable_id' => $service->id,
                            'locale' => $key,
                            'key' => 'extra_info'
                        ], [
                            'value' => json_encode($extra_info)
                        ]
                    );
                }
            }
            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->title[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Service',
                        'translationable_id' => $service->id,
                        'locale' => $key,
                        'key' => 'title',
                        'value' => $request->title[$index],
                    ));
                }
                if ($request->description[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Service',
                        'translationable_id' => $service->id,
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
            dd($e->getMessage(), $e->getTrace());
            $output = [
                'success' => 0,
                'msg' => __('Something went wrong'),
            ];
        }
        return redirect()->route('admin.services.index')->with($output);
    }
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        if (!auth()->user()->can('service.edit')) {
            abort(403, 'Unauthorized action.');
        }
        $service = Service::withoutGlobalScopes()->with('translations')->findOrFail($id);
        $language = BusinessSetting::where('type', 'language')->first();
        $service_gallery = ServiceGallery::where('service_id',$service->id)->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];
        if (request()->ajax()) {
            $lang = request('lang');
            $key = request('key');
            $tr = view('backends.service._service_detail_tr', compact('key', 'lang'))->render();
            return response()->json([
                'tr' => $tr
            ]);
        }
        return view('backends.service.edit', compact('service', 'language', 'default_lang', 'service_gallery'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('service.edit')) {
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
            $service =Service::findOrFail($id);
            $service->title = $request->title[array_search('en', $request->lang)];
            $service->description = $request->description[array_search('en', $request->lang)];
            $service->created_by = auth()->user()->id;
            if ($request->filled('thumbnail_image_names')) {
                $service->thumbnail = $request->thumbnail_image_names;
                $directory = public_path('uploads/service');
                if (!\File::exists($directory)) {
                    \File::makeDirectory($directory, 0777, true);
                }

                $image = \File::move(public_path('uploads/temp/' . $request->thumbnail_image_names), public_path('uploads/service/'. $request->thumbnail_image_names));

            }
            $extra_info = [];
            if ($request->extra_info) {
                foreach ($request->extra_info['en']['title'] as $key => $val) {
                    $item['title'] = $request->extra_info['en']['title'][$key];
                    $item['description'] = $request->extra_info['en']['description'][$key];
                    array_push($extra_info, $item);
                }
                $service->extra_info = $extra_info;
            }
            $service->save();
            $service_id = $service->id;
            $service_gallery = ServiceGallery::where('service_id', $service_id)->first();
            $service_gallery->service_id = $service_id;
            if ($request->filled('image_names')) {
                $service_gallery->image = explode(' ', $request->image_names);
                foreach ($service_gallery->image as $key => $image) {
                    $directory = public_path('uploads/service_gallery');
                    if (!\File::exists($directory)) {
                        \File::makeDirectory($directory, 0777, true);
                    }

                    $image = \File::move(public_path('uploads/temp/' . $image), public_path('uploads/service_gallery/'. $image));
                }
            }
            $service_gallery->save();

            foreach ($request->lang as $index => $key) {
                if ($key != 'en') {
                    $extra_info = [];
                    if (isset($request->extra_info[$key])) {
                        foreach ($request->extra_info[$key]['title'] as $extra_info_key => $val) {
                            $item['title'] = $request->extra_info[$key]['title'][$extra_info_key];
                            $item['description'] = $request->extra_info[$key]['description'][$extra_info_key];
                            array_push($extra_info, $item);
                        }
                    }

                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\Service',
                            'translationable_id' => $service->id,
                            'locale' => $key,
                            'key' => 'extra_info'
                        ], [
                            'value' => json_encode($extra_info)
                        ]
                    );
                }
            }
            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->title[$index] && $key != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Models\Service',
                            'translationable_id' => $service->id,
                            'locale' => $key,
                            'key' => 'title'],
                        ['value' => $request->title[$index]]
                    );
                }
                if ($request->description[$index] && $key != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Models\Service',
                            'translationable_id' => $service->id,
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
        return redirect()->route('admin.services.index')->with($output);
    }

    public function destroy($id)
    {
        if (!auth()->user()->can('service.delete')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            DB::beginTransaction();
            $service = Service::findOrFail($id);
            $service_gallery = ServiceGallery::where('service_id',$service->id)->first();
            $translation = Translation::where('translationable_type','App\Models\Service')
                                        ->where('translationable_id',$service->id);
            $translation->delete();
            $service_gallery->delete();
            $service->delete();
            if($service->thumbnail){
                ImageManager::delete(public_path('uploads/service/'.$service->thumbnail));
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

            $service = Service::findOrFail($request->id);
            $service->status = $service->status == 'active' ? 'inactive' : 'active';
            $service->save();

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
        $services = Service::latest('id')->paginate(10);
        $view = view('backends.service._table', compact('services'))->render();

        return ['view' => $view];
    }

}
