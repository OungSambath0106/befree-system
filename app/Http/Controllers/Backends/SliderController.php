<?php

namespace App\Http\Controllers\Backends;

use Exception;
use App\Models\Slider;
use App\helpers\AppHelper;
use App\Models\Translation;
use Illuminate\Http\Request;
use App\helpers\ImageManager;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('slider.view')) {
            abort(403, 'Unauthorized action.');
        }
        $sliders = Slider::latest('id')->paginate(10);
        return view('backends.slider.index', compact('sliders'));
    }
    public function create()
    {
        if (!auth()->user()->can('slider.create')) {
            abort(403, 'Unauthorized action.');
        }
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];
        return view('backends.slider.create', compact('language', 'default_lang'));
    }
    public function store(Request $request)
    {
        if (!auth()->user()->can('slider.create')) {
            abort(403, 'Unauthorized action.');
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'short_des' => 'required',
        ]);

        if (is_null($request->name[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'name', 'Name field is required!'
                );
            });
        }
        if (is_null($request->short_des[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'short_des', 'Short Description field is required!'
                );
            });
        }

        if($validator->fails()){
            return redirect()->back()
            ->withErrors($validator)
            ->withInput()
            ->with(['success' =>0, 'msg' => __('Invalid form input')]);
        }

        try {
            DB::beginTransaction();

            $slider = new Slider;
            $slider->name = $request->name[array_search('en', $request->lang)];
            $slider->short_des = $request->short_des[array_search('en', $request->lang)];
            $slider->type = $request->type;
            if ($request->filled('image_names')) {
                $slider->image = $request->image_names;
                $directory = public_path('uploads/sliders');
                if (!\File::exists($directory)) {
                    \File::makeDirectory($directory, 0777, true);
                }

                $image = \File::move(public_path('uploads/temp/' . $request->image_names), public_path('uploads/sliders/'. $request->image_names));

            }
            $slider->created_by = auth()->user()->id;
            $slider->save();
            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->name[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Slider',
                        'translationable_id' => $slider->id,
                        'locale' => $key,
                        'key' => 'name',
                        'value' => $request->name[$index],
                    ));
                }
                if ($request->short_des[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Slider',
                        'translationable_id' => $slider->id,
                        'locale' => $key,
                        'key' => 'short_des',
                        'value' => $request->short_des[$index],
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

        return redirect()->route('admin.slider.index')->with($output);
    }
    public function show($id)
    {
        //
    }
    public function edit($id)
    {
        if (!auth()->user()->can('slider.edit')) {
            abort(403, 'Unauthorized action.');
        }
        $slider = Slider::withoutGlobalScopes()->with('translations')->findOrFail($id);
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];
        return view('backends.slider.edit', compact('slider', 'language', 'default_lang'));
    }
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('slider.edit')) {
            abort(403, 'Unauthorized action.');
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'short_des' => 'required',
        ]);

        if (is_null($request->name[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'name', 'Name field is required!'
                );
            });
        }
        if (is_null($request->short_des[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'short_des', 'Short Description field is required!'
                );
            });
        }

        if($validator->fails()){
            return redirect()->back()
            ->withErrors($validator)
            ->withInput()
            ->with(['success' =>0, 'msg' => __('Invalid form input')]);
        }

        try {
            DB::beginTransaction();
            $slider = Slider::findOrFail($id);
            $slider->name = $request->name[array_search('en', $request->lang)];
            $slider->short_des = $request->short_des[array_search('en', $request->lang)];
            $slider->type = $request->type;
            if ($request->filled('image_names')) {
                $slider->image = $request->image_names;
                $directory = public_path('uploads/sliders');
                if (!\File::exists($directory)) {
                    \File::makeDirectory($directory, 0777, true);
                }

                $image = \File::move(public_path('uploads/temp/' . $request->image_names), public_path('uploads/sliders/'. $request->image_names));

            }
            $slider->save();
            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->name[$index] && $key != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Models\Slider',
                            'translationable_id' => $slider->id,
                            'locale' => $key,
                            'key' => 'name'],
                        ['value' => $request->name[$index]]
                    );
                }
                if ($request->short_des[$index] && $key != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Models\Slider',
                            'translationable_id' => $slider->id,
                            'locale' => $key,
                            'key' => 'short_des'],
                        ['value' => $request->short_des[$index]]
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
            $output = [
                'success' => 0,
                'msg' => __('Something went wrong'),
            ];
        }

        return redirect()->route('admin.slider.index')->with($output);
    }
    public function destroy($id)
    {
        if (!auth()->user()->can('slider.delete')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            DB::beginTransaction();
            $slider = Slider::findOrFail($id);
            $slider->delete();

            if ($slider->image) {
                ImageManager::delete(public_path('uploads/sliders/' . $slider->image));
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

            $slider = Slider::findOrFail($request->id);
            $slider->status = $slider->status == 'active' ? 'inactive' : 'active';
            $slider->save();

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
        $sliders = Slider::latest('id')->paginate(10);
        $view = view('backends.slider._table', compact('sliders'))->render();

        return ['view' => $view];
    }
}
