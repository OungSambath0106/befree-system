<?php

namespace App\Http\Controllers\Backends;

use App\Models\Blog;
use App\Models\Translation;
use Illuminate\Http\Request;
use App\helpers\ImageManager;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\Customer;
use Exception;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('blog.view')) {
            abort(403, 'Unauthorized action.');
        }
        $blogs = Blog::latest('id')->paginate(10);
        return view('backends.blog.index',compact('blogs'));
    }
    public function create()
    {
        if (!auth()->user()->can('blog.create')) {
            abort(403, 'Unauthorized action.');
        }
        $language = BusinessSetting::where('type', 'language')->first();
        $categories = BlogCategory::pluck('title','id');
        $tages = BlogTag::pluck('title','id');
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];

        return view('backends.blog.create', compact('language', 'default_lang', 'categories', 'tages'));
    }
    public function store(Request $request)
    {
        if (!auth()->user()->can('blog.create')) {
            abort(403, 'Unauthorized action.');
        }
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ]);

        if (is_null($request->title[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'title', 'Title field is required!'
                );
            });
        }
        if (is_null($request->description[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'description', 'Description field is required!'
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

            $blog = new Blog;
            $blog->title       = $request->title[array_search('en', $request->lang)];
            $blog->slug        = str_replace(' ', '-', $request->title[array_search('en', $request->lang)]);
            $blog->description = $request->description[array_search('en', $request->lang)];
            $blog->category_id = $request->category_id??0;
            $blog->tage        = $request->tage??[];
            $blog->created_by  = auth()->user()->id;

            if ($request->filled('thumbnail_names')) {
                $blog->thumbnail = $request->thumbnail_names;
                $directory = public_path('uploads/blogs');
                if (!\File::exists($directory)) {
                    \File::makeDirectory($directory, 0777, true);
                }

                $thumbnail = \File::move(public_path('uploads/temp/' . $request->thumbnail_names), public_path('uploads/blogs/'. $request->thumbnail_names));

            }

            $blog->save();
            $customers = Customer::all();

            foreach ($customers as $customer) {
                $customer->notify(new \App\Notifications\BlogNotification($blog));
            }

            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->title[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Blog',
                        'translationable_id' => $blog->id,
                        'locale' => $key,
                        'key' => 'title',
                        'value' => $request->title[$index],
                    ));
                }
                if ($request->description[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Blog',
                        'translationable_id' => $blog->id,
                        'locale' => $key,
                        'key' => 'description',
                        'value' => $request->description[$index],
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

        return redirect()->route('admin.blog.index')->with($output);
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
    public function edit($id)
    {
        if (!auth()->user()->can('blog.edit')) {
            abort(403, 'Unauthorized action.');
        }
        $blog = Blog::withoutGlobalScopes()->with('translations')->findOrFail($id);
        $categories = BlogCategory::pluck('title','id');
        $tages = BlogTag::pluck('title','id');
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];

        return view('backends.blog.edit', compact('blog', 'language', 'default_lang', 'categories', 'tages'));
    }
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('blog.edit')) {
            abort(403, 'Unauthorized action.');
        }
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ]);

        if (is_null($request->title[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'title', 'Title field is required!'
                );
            });
        }
        if (is_null($request->description[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'description', 'Description field is required!'
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

            $blog = Blog::findOrFail($id);
            $blog->title = $request->title[array_search('en', $request->lang)];
            $blog->slug = str_replace(' ', '-', $request->title[array_search('en', $request->lang)]);
            $blog->description = $request->description[array_search('en', $request->lang)];
            $blog->category_id = $request->category_id;
            $blog->tage  = $request->tage;
            $blog->created_by = auth()->user()->id;

            if ($request->filled('thumbnail_names')) {
                $blog->thumbnail = $request->thumbnail_names;
                $directory = public_path('uploads/blogs');
                if (!\File::exists($directory)) {
                    \File::makeDirectory($directory, 0777, true);
                }

                $thumbnail = \File::move(public_path('uploads/temp/' . $request->thumbnail_names), public_path('uploads/blogs/'. $request->thumbnail_names));

            }

            $blog->save();

            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->title[$index] && $key != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Models\Blog',
                            'translationable_id' => $blog->id,
                            'locale' => $key,
                            'key' => 'title'],
                        ['value' => $request->title[$index]]
                    );
                }
                if ($request->description[$index] && $key != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Models\Blog',
                            'translationable_id' => $blog->id,
                            'locale' => $key,
                            'key' => 'description'],
                        ['value' => $request->description[$index]]
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

        return redirect()->route('admin.blog.index')->with($output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('blog.delete')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            DB::beginTransaction();
            $blog = Blog::findOrFail($id);
            $translation = Translation::where('translationable_type','App\Models\Blog')
                                        ->where('translationable_id',$blog->id);
            $translation->delete();
            $blog->delete();

            if ($blog->image_thumbnail) {
                ImageManager::delete(public_path('uploads/blogs/' . $blog->image_thumbnail));
            }

            $blogs = Blog::latest('id')->paginate(10);
            $view = view('backends.blog._table', compact('blogs'))->render();

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

            $blog = Blog::findOrFail($request->id);
            $blog->status = $blog->status == 'active' ? 'inactive' : 'active';
            $blog->save();

            $output = ['status' => 1, 'msg' => __('Status updated')];

            DB::commit();
        } catch (Exception $e) {
            $output = ['status' => 0, 'msg' => __('Something went wrong')];
            DB::rollBack();
        }

        return response()->json($output);
    }
}
