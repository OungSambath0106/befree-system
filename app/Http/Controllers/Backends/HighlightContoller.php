<?php

namespace App\Http\Controllers\Backends;

use App\helpers\ImageManager;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\Highlight;
use App\Models\Translation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HighlightContoller extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('highlight.view')) {
            abort(403, 'Unauthorized action.');
        }
        $highlights = Highlight::latest('id')->paginate(10);
        return view('backends.highlight.index', compact('highlights'));
    }

    public function create()
    {
        if (!auth()->user()->can('highlight.create')) {
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

        return view('backends.highlight.create', compact('language', 'default_lang'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('highlight.create')) {
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
            $highlight = new Highlight();
            $highlight->title = $request->title[array_search('en', $request->lang)];
            $highlight->description = $request->description[array_search('en', $request->lang)];
            $highlight->created_by = auth()->user()->id;

            if ($request->hasFile('thumbnail')) {
                $highlight->thumbnail = ImageManager::upload('uploads/highlight',$request->thumbnail);
            }

            if ($request->hasFile('icon')) {
                $highlight->icon = ImageManager::upload('uploads/highlight',$request->icon);
            }

            // if ($request->filled('image_names')) {
            //     $highlight->image = explode(' ', $request->image_names);
            //     foreach ($highlight->image as $key => $image) {
            //         $directory = public_path('uploads/highlight');
            //         if (!\File::exists($directory)) {
            //             \File::makeDirectory($directory, 0777, true);
            //         }

            //         $image = \File::move(public_path('uploads/temp/' . $image), public_path('uploads/highlight/'. $image));
            //     }
            // }           

            $highlight->save();

            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->title[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Highlight',
                        'translationable_id' => $highlight->id,
                        'locale' => $key,
                        'key' => 'title',
                        'value' => $request->title[$index],
                    ));
                }

                if ($request->description[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Highlight',
                        'translationable_id' => $highlight->id,
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
            dd($e);
            DB::rollBack();
            $output = [
                'success' => 0,
                'msg' => __('Something went wrong'),
            ];
        }

        return redirect()->route('admin.highlight.index')->with($output);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        if (!auth()->user()->can('highlight.edit')) {
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

        $highlight = Highlight::withoutGlobalScopes()->with('translations')->findOrFail($id);
        return view('backends.highlight.edit', compact('highlight', 'language', 'default_lang'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('highlight.edit')) {
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
            $highlight = Highlight::findOrFail($id); //highlight;
            $highlight->title = $request->title[array_search('en', $request->lang)];
            $highlight->description = $request->description[array_search('en', $request->lang)];
            $highlight->created_by = auth()->user()->id;

            if ($request->hasFile('thumbnail')) {
                $highlight->thumbnail = ImageManager::update('uploads/highlight/', $highlight->thumbnail, $request->thumbnail);
            }
            if ($request->hasFile('icon')) {
                $highlight->icon = ImageManager::update('uploads/highlight/', $highlight->icon, $request->icon);
            }

            // if ($request->filled('gallery_image_names')) {
            //     $highlight->image = explode(' ', $request->gallery_image_names);
            //     foreach ($highlight->image as $key => $image) {
            //         $directory = public_path('uploads/highlight');
            //         if (!\File::exists($directory)) {
            //             \File::makeDirectory($directory, 0777, true);
            //         }

            //         $image = \File::move(public_path('uploads/temp/' . $image), public_path('uploads/highlight/'. $image));
            //     }
            // }
            $highlight->save();

            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->title[$index] && $key != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Models\Highlight',
                            'translationable_id' => $highlight->id,
                            'locale' => $key,
                            'key' => 'title'],
                        ['value' => $request->title[$index]]
                    );
                }

                if ($request->description[$index] && $key != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Models\Highlight',
                            'translationable_id' => $highlight->id,
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
        return redirect()->route('admin.highlight.index')->with($output);
    }

    public function destroy($id)
    {
        if (!auth()->user()->can('highlight.delete')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            DB::beginTransaction();
            $highlight = Highlight::findOrFail($id);
            $highlight->delete();

            if ($highlight->thumbnail) {
                ImageManager::delete(public_path('uploads/highlight/' . $highlight->thumbnail));
            }

            if($highlight->image){
                foreach ($highlight->image as $key => $image) {
                    ImageManager::delete(public_path('uploads/highlight/' . $image));
                }
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

            $highlight = Highlight::findOrFail($request->id);
            $highlight->status = $highlight->status == 'active' ? 'inactive' : 'active';
            $highlight->save();

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
        $highlights = Highlight::latest('id')->paginate(10);
        $view = view('backends.highlight._table', compact('highlights'))->render();

        return ['view' => $view];
    }
    
}
