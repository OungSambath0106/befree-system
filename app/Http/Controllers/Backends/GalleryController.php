<?php

namespace App\Http\Controllers\Backends;

use Exception;
use App\Models\Gallery;
use App\helpers\AppHelper;
use App\Models\Translation;
use Illuminate\Http\Request;
use App\helpers\ImageManager;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class GalleryController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('gallery.view')) {
            abort(403, 'Unauthorized action.');
        }
        $categories = Category::all();
        $gallerys = Gallery::latest('id')->paginate(10);
        return view('backends.gallery.index', compact('gallerys', 'categories'));
    }
    public function create()
    {
        if (!auth()->user()->can('gallery.create')) {
            abort(403, 'Unauthorized action.');
        }
        $categories = Category::all();
        $products = Product::with('category')->get();
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];

        return view('backends.gallery._create', compact('categories', 'products', 'language', 'default_lang'));
    }
    public function store(Request $request)
    {
        if (!auth()->user()->can('gallery.create')) {
            abort(403, 'Unauthorized action.');
        }
        $validator = Validator::make($request->all(), [
            'image_names' => 'required',
            // 'description' => 'required',
            'category_id' => 'required',
        ]);
        // if (is_null($request->description[array_search('en', $request->lang)])) {
        //     $validator->after(function ($validator) {
        //         $validator->errors()->add(
        //             'description', 'Description field is required!'
        //         );
        //     });
        // }
        if($validator->fails()){
            return redirect()->back()
            ->withErrors($validator)
            ->withInput()
            ->with(['success' =>0, 'msg' => __('Invalid form input')]);
        }

        try {
            DB::beginTransaction();
            $gallery = new Gallery;
            // $gallery->description = $request->description[array_search('en', $request->lang)];
            // $gallery->category_id = $request->category_id;
            $gallery->created_by = auth()->user()->id;

            if ($request->filled('image_names')) {
                $gallery->image = $request->image_names;
                $directory = public_path('uploads/gallery');
                if (!\File::exists($directory)) {
                    \File::makeDirectory($directory, 0777, true);
                }

                $image = \File::move(public_path('uploads/temp/' . $request->image_names), public_path('uploads/gallery/'. $request->image_names));

            }
            $gallery->save();

            $gallery->categories()->attach($request->category_id);


            // $data = [];
            // foreach ($request->lang as $index => $key) {
            //     if ($request->description[$index] && $key != 'en') {
            //         array_push($data, array(
            //             'translationable_type' => 'App\Models\Gallery',
            //             'translationable_id' => $gallery->id,
            //             'locale' => $key,
            //             'key' => 'description',
            //             'value' => $request->description[$index],
            //         ));
            //     }
            // }
            // Translation::insert($data);
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

        return redirect()->route('admin.gallery.index')->with($output);
    }
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        if (!auth()->user()->can('gallery.edit')) {
            abort(403, 'Unauthorized action.');
        }
        $gallery = Gallery::withoutGlobalScopes()->with('translations')->with('categories')->findOrFail($id);
        $categories = Category::all();
        $category_selects = $gallery->categories->pluck('id')->toArray();

        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];
        return view('backends.gallery._edit', compact('gallery', 'category_selects', 'categories', 'language', 'default_lang'));
    }
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('gallery.edit')) {
            abort(403, 'Unauthorized action.');
        }
        $validator = Validator::make($request->all(), [
            'image_names' => 'nullable',
            // 'description' => 'required',
            'category_id' => 'required',
        ]);
        // if (is_null($request->description[array_search('en', $request->lang)])) {
        //     $validator->after(function ($validator) {
        //         $validator->errors()->add(
        //             'description', 'Description field is required!'
        //         );
        //     });
        // }
        if($validator->fails()){
            return redirect()->back()
            ->withErrors($validator)
            ->withInput()
            ->with(['success' =>0, 'msg' => __('Invalid form input')]);
        }

        try {
            // dd($request->all());
            DB::beginTransaction();
            $gallery = Gallery::findorFail($id);
            // $gallery->description = $request->description[array_search('en', $request->lang)];
            // $gallery->category_id = $request->category_id;
            if ($request->filled('image_names')) {
                $gallery->image = $request->image_names;
                $directory = public_path('uploads/gallery');
                if (!\File::exists($directory)) {
                    \File::makeDirectory($directory, 0777, true);
                }

                $image = \File::move(public_path('uploads/temp/' . $request->image_names), public_path('uploads/gallery/'. $request->image_names));

            }
            $gallery->save();

            $gallery->categories()->sync($request->category_id);

            // $data = [];
            // foreach ($request->lang as $index => $key) {
            //     if ($request->description[$index] && $key != 'en') {
            //         Translation::updateOrInsert(
            //             ['translationable_type' => 'App\Models\Gallery',
            //                 'translationable_id' => $gallery->id,
            //                 'locale' => $key,
            //                 'key' => 'description'],
            //             ['value' => $request->description[$index]]
            //         );
            //     }
            // }
            // Translation::insert($data);
            DB::commit();
            $table = $this->renderTable();
            $view = $table['view'];

            $output = [
                'success' => 1,
                'msg' => __('Update successfully'),
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

        return redirect()->route('admin.gallery.index')->with($output);
    }
    public function destroy($id)
    {
        if (!auth()->user()->can('gallery.delete')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            DB::beginTransaction();
            $gallery = Gallery::findOrFail($id);
            $translation = Translation::where('translationable_type','App\Models\Gallery')
                                        ->where('translationable_id',$gallery->id);
            $translation->delete();
            $gallery->delete();
            if ($gallery->image) {
                ImageManager::delete(public_path('uploads/gallery/' . $gallery->image));
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

            $gallery = Gallery::findOrFail($request->id);
            $gallery->status = $gallery->status == 'active' ? 'inactive' : 'active';
            $gallery->save();

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
        $gallerys = Gallery::latest('id')->paginate(10);
        $view = view('backends.gallery._table', compact('gallerys'))->render();

        return ['view' => $view];
    }
}
