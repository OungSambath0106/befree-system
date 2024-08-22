<?php

namespace App\Http\Controllers\Backends;

use Exception;
use App\helpers\AppHelper;
use App\Models\Translation;
use Illuminate\Support\Str;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BlogCategoryController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('category.view')) {
            abort(403, 'Unauthorized action.');
        }
        $blog_categories = BlogCategory::latest('id')->paginate(10);
        return view('backends.blog-category.index', compact('blog_categories'));
    }
    public function create()
    {
        if (!auth()->user()->can('category.create')) {
            abort(403, 'Unauthorized action.');
        }
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];

        return view('backends.blog-category._create', compact('language', 'default_lang'));
    }
    public function store(Request $request)
    {
        if (!auth()->user()->can('category.create')) {
            abort(403, 'Unauthorized action.');
        }
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if (is_null($request->title[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'title',
                    __('Title field is required!')
                );
            });
        }

        if ($validator->fails()) {
            return response()->json(['errors' => AppHelper::error_processor($validator)]);
        }

        try {
            DB::beginTransaction();

            $category = new BlogCategory;
            $category->title = $request->title[array_search('en', $request->lang)];
            $category->slug = Str::slug($request->title[array_search('en', $request->lang)]);
            $category->created_by = auth()->user()->id;
            $category->save();

            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->title[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\BlogCategory',
                        'translationable_id' => $category->id,
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

        return redirect()->route('admin.blog-category.index')->with($output);
    }
    public function show($id)
    {
        //
    }
    public function edit($id)
    {
        if (!auth()->user()->can('category.edit')) {
            abort(403, 'Unauthorized action.');
        }
        $category = BlogCategory::withoutGlobalScopes()->with('translations')->findOrFail($id);
        $language = BusinessSetting::where('type', 'language')->first();

        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];

        return view('backends.blog-category._edit', compact('category', 'language', 'default_lang'));
    }
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('category.edit')) {
            abort(403, 'Unauthorized action.');
        }
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if (is_null($request->title[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'title',
                    __('Title field is required!')
                );
            });
        }

        if ($validator->fails()) {
            return response()->json(['errors' => AppHelper::error_processor($validator)]);
        }

        try {
            DB::beginTransaction();

            $category = BlogCategory::findOrFail($id);
            $category->title = $request->title[array_search('en', $request->lang)];
            $category->slug = Str::slug($request->title[array_search('en', $request->lang)]);
            $category->created_by = auth()->user()->id;
            $category->save();

            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->title[$index] && $key != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Models\BlogCategory',
                            'translationable_id' => $category->id,
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
            $output = [
                'success' => 0,
                'msg' => __('Something went wrong'),
            ];
        }

        return redirect()->route('admin.blog-category.index')->with($output);
    }
    public function destroy($id)
    {
        if (!auth()->user()->can('category.delete')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            DB::beginTransaction();
            $category = BlogCategory::findOrFail($id);
            $translation = Translation::where('translationable_type', 'App\Models\BlogCategory')
                ->where('translationable_id', $category->id);

            $translation->delete();
            $category->delete();

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
                'msg' => __('Something went wrong'),
            ];
        }

        return response()->json($output);
    }
    public function updateStatus (Request $request)
    {
        try {
            DB::beginTransaction();

            $category = BlogCategory::findOrFail($request->id);
            $category->status = $category->status == 'active' ? 'inactive' : 'active';
            $category->save();

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
        $blog_categories = BlogCategory::latest('id')->paginate(10);
        $view = view('backends.blog-category._table', compact('blog_categories'))->render();

        return ['view' => $view];
    }
}
