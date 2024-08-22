<?php

namespace App\Http\Controllers\Backends;

use Exception;
use App\Models\BlogTag;
use App\helpers\AppHelper;
use App\Models\Translation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BlogTagController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('tag.view')) {
            abort(403, 'Unauthorized action.');
        }   
        $blog_tags = BlogTag::latest('id')->paginate(10);
        return view('backends.blog-tag.index', compact('blog_tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('tag.create')) {
            abort(403, 'Unauthorized action.');
        }
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];

        return view('backends.blog-tag._create', compact('language', 'default_lang'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('tag.create')) {
            abort(403, 'Unauthorized action.');
        }
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if (is_null($request->title[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'title',
                    'Title field is required!'
                );
            });
        }

        if ($validator->fails()) {
            return response()->json(['errors' => AppHelper::error_processor($validator)]);
        }

        try {
            DB::beginTransaction();

            $tag = new BlogTag;
            $tag->title = $request->title[array_search('en', $request->lang)];
            $tag->slug = Str::slug($request->title[array_search('en', $request->lang)]);
            $tag->created_by = auth()->user()->id;
            $tag->save();

            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->title[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\BlogTag',
                        'translationable_id' => $tag->id,
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

        return redirect()->route('admin.blog-tag.index')->with($output);
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
        if (!auth()->user()->can('tag.edit')) {
            abort(403, 'Unauthorized action.');
        }
        $tag = BlogTag::withoutGlobalScopes()->with('translations')->findOrFail($id);
        $language = BusinessSetting::where('type', 'language')->first();

        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];

        return view('backends.blog-tag._edit', compact('tag', 'language', 'default_lang'));
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
        if (!auth()->user()->can('tag.edit')) {
            abort(403, 'Unauthorized action.');
        }
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if (is_null($request->title[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'title',
                    'Title field is required!'
                );
            });
        }

        if ($validator->fails()) {
            return response()->json(['errors' => AppHelper::error_processor($validator)]);
        }

        try {
            DB::beginTransaction();

            $tag = BlogTag::findOrFail($id);
            $tag->title = $request->title[array_search('en', $request->lang)];
            $tag->slug = Str::slug($request->title[array_search('en', $request->lang)]);
            $tag->created_by = auth()->user()->id;
            $tag->save();

            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->title[$index] && $key != 'en') {
                    Translation::updateOrInsert([
                        'translationable_type' => 'App\Models\BlogTag',
                        'translationable_id' => $tag->id,
                        'locale' => $key,
                        'key' => 'title'],
                        [
                            'value' => $request->title[$index]
                        ]
                    );
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

        return redirect()->route('admin.blog-tag.index')->with($output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('tag.delete')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            DB::beginTransaction();
            $tag = BlogTag::findOrFail($id);
            $translation = Translation::where('translationable_type', 'App\Models\BlogTag')
                ->where('translationable_id', $tag->id);

            $translation->delete();
            $tag->delete();

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

            $tag = BlogTag::findOrFail($request->id);
            $tag->status = $tag->status == 'active' ? 'inactive' : 'active';
            $tag->save();

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
        $blog_tags = BlogTag::latest('id')->paginate(10);
        $view = view('backends.blog-tag._table', compact('blog_tags'))->render();

        return ['view' => $view];
    }
}
