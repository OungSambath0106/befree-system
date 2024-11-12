<?php

namespace App\Http\Controllers\Backends;

use App\helpers\ImageManager;
use File;
use Exception;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Translation;
use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::latest('id')->paginate(10);
        return view('backends.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::all();
        $products = Product::with('brand')->get();
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];

        return view('backends.product.create', compact('products', 'brands', 'language', 'default_lang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'brand_id' => 'required',
        ]);

        if (is_null($request->name[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'name',
                    'Name field is required!'
                );
            });
        }
        if (is_null($request->description[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'description',
                    'description field is required!'
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
            // dd($request->all());
            DB::beginTransaction();

            $pro = new Product;
            $pro->name = $request->name[array_search('en', $request->lang)];
            $pro->description = $request->description[array_search('en', $request->lang)];
            $pro->brand_id = $request->brand_id;
            $pro->created_by = auth()->user()->id;

            $products_info = [];
            if ($request->products_info) {
                foreach ($request->products_info['product_size'] as $key => $number) {
                    $item['product_size'] = $number;
                    $item['product_price'] = number_format((float)$request->products_info['product_price'][$key], 2, '.', '');
                    $item['product_qty'] = $request->products_info['product_qty'][$key];
                    array_push($products_info, $item);
                }
                $pro->product_info =$products_info;
            }

            if ($request->hasFile('image')) {
                $pro->image = ImageManager::upload('uploads/products/', $request->image);
            }

            $pro->save();

            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->name[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Product',
                        'translationable_id' => $pro->id,
                        'locale' => $key,
                        'key' => 'name',
                        'value' => $request->name[$index],
                    ));
                }
                if ($request->description[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Product',
                        'translationable_id' => $pro->id,
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
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            $output = [
                'success' => 0,
                'msg' => __('Something went wrong')
            ];
        }

        return redirect()->route('admin.product.index')->with($output);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        $product = Product::withoutGlobalScopes()->with('translations')->with('brand')->findOrFail($id);
        $brands = Brand::all();

        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];

        return view('backends.product.edit', compact('product', 'brands', 'language', 'default_lang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'brand_id' => 'required',
        ]);

        // dd($validator);
        if (is_null($request->name[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'name',
                    'Name field is required!'
                );
            });
        }
        if (is_null($request->description[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'description',
                    'description field is required!'
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

            // dd($request -> all());
            DB::beginTransaction();

            $product = Product::findOrFail($id);
            $product->name = $request->name[array_search('en', $request->lang)];
            $product->description = $request->description[array_search('en', $request->lang)];
            $product->brand_id = $request->brand_id;

            $products_info = [];
            if ($request->products_info) {
                foreach ($request->products_info['product_size'] as $key => $number) {
                    $item['product_size'] = $number;
                    $item['product_price'] = number_format((float)$request->products_info['product_price'][$key], 2, '.', '');
                    $item['product_qty'] = $request->products_info['product_qty'][$key];
                    array_push($products_info, $item);
                }
                $product->product_info =$products_info;
            }

            if ($request->hasFile('image')) {
                if ($product->image && file_exists(public_path('uploads/products/' . $product->image))) {
                    unlink(public_path('uploads/products/' . $product->image));
                }

                $product->image = ImageManager::update('uploads/products/', null, $request->image);
            }

            $product->save();

            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->name[$index] && $key != 'en') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\Product',
                            'translationable_id' => $product->id,
                            'locale' => $key,
                            'key' => 'name'
                        ],
                        ['value' => $request->name[$index]]
                    );
                }
            }
            foreach ($request->lang as $index => $key) {
                if (isset($request->description[$index]) && $key != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Models\Product',
                            'translationable_id' => $product->id,
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
                'msg' => __('Created successfully')
            ];
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            $output = [
                'success' => 0,
                'msg' => __('Something went wrong')
            ];
        }

        return redirect()->route('admin.product.index')->with($output);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $product = Product::findOrFail($id);
            $translation = Translation::where('translationable_type', 'App\Models\Product')
                ->where('translationable_id', $product->id);
            $translation->delete();
            $product->delete();

            $products = Product::latest('id')->paginate(10);
            $view = view('backends.product._table', compact('products'))->render();

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

    public function updateStatus(Request $request)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($request->id);
            $product->status = $product->status == 1 ? 0 : 1;
            $product->save();

            $output = ['status' => 1, 'msg' => __('Status updated')];

            DB::commit();
        } catch (Exception $e) {

            $output = ['status' => 0, 'msg' => __('Something went wrong')];
            DB::rollBack();
        }

        return response()->json($output);
    }
}
