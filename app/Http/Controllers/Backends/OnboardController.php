<?php

namespace App\Http\Controllers\Backends;

use Exception;
use App\Models\Onboard;
use App\Models\Translation;
use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class OnboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $onboards = onboard::orderBy('sort_order', 'asc')->paginate(10);

        // $onboards = onboard::latest('id')->paginate(10);
        return view('backends.onboard.index', compact('onboards'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];
        return view('backends.onboard.create', compact('language', 'default_lang'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'sort_order' => 'required|integer',
        ]);

        if (is_null($request->title[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'title',
                    'title field is required!'
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
            DB::beginTransaction();

            $onboard = new Onboard();
            $onboard->title = $request->title[array_search('en', $request->lang)];
            $onboard->description = $request->description[array_search('en', $request->lang)];
            // $onboard->sort_order = $request->sort_order;
            // Check if there are existing items with sort_order = 1
            $existingItem = Onboard::where('sort_order', 1)->first();

            if ($existingItem) {
                // Increment sort_order of all existing items by 1
                Onboard::where('sort_order', '>=', 1)->increment('sort_order');
            }

            // Set sort_order of the new item to 1
            $onboard->sort_order = 1;

            if ($request->filled('images')) {
                $onboard->image = $request->images;
                $directory = public_path('uploads/onboards');
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0777, true);
                }

                $image = File::move(public_path('/uploads/temp/' . $request->images), public_path('uploads/onboards/' . $request->images));
            }


            $onboard->save();


            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->title[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Onboard',
                        'translationable_id' => $onboard->id,
                        'locale' => $key,
                        'key' => 'title',
                        'value' => $request->title[$index],
                    ));
                }
                if ($request->description[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Onboard',
                        'translationable_id' => $onboard->id,
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
        return redirect()->route('admin.onboard.index')->with($output);
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
        // $onboard = onboard::orderByRaw('sort_order = 1 DESC')->orderBy('sort_order')->get();
        $onboard = Onboard::withoutGlobalScopes()->with('translations')->findOrFail($id);
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];
        return view('backends.onboard.edit', compact('onboard', 'language', 'default_lang'));
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
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'sort_order' => 'required|integer',
        ]);

        if (is_null($request->title[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'title',
                    'title field is required!'
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
            DB::beginTransaction();

            $onboard =  Onboard::findOrFail($id);
            $onboard->title = $request->title[array_search('en', $request->lang)];
            $onboard->description = $request->description[array_search('en', $request->lang)];
            // $onboard->sort_order = $request->sort_order;

            $newSortOrder = $request->input('sort_order');
            // If the new sort order is different from the current one
            if ($onboard->sort_order != $newSortOrder) {
                // Increment sort order of items greater than or equal to the new sort order
                Onboard::where('sort_order', '>=', $newSortOrder)
                    ->where('id', '!=', $id) // Exclude the current item
                    ->increment('sort_order');

                // Update the sort order of the current item
                $onboard->sort_order = $newSortOrder;
            }
            $onboard->save();

            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if ($onboard->image) {
                    $oldImagePath = public_path('uploads/onboards/' . $onboard->image);

                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath); // Delete the old image file
                    }
                }

                // Upload and save the new image
                $image = $request->file('image');

                // Generate a unique filename based on current date and unique identifier
                $imageName = now()->format('Y-m-d') . '-' . uniqid() . '.' . $image->getClientOriginalExtension();

                // Move the uploaded file to the onboards directory
                $image->move(public_path('uploads/onboards'), $imageName);

                // Update the image attribute of the onboard model
                $onboard->image = $imageName;

                // Save the updated onboard model
                $onboard->save();
            }
            $onboard->save();

            $data = [];
            foreach ($request->lang as $index => $key) {
                if (isset($request->title[$index]) && $key != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Models\Onboard',
                            'translationable_id' => $onboard->id,
                            'locale' => $key,
                            'key' => 'title'],
                        ['value' => $request->title[$index]]
                    );
                }
            }
            foreach ($request->lang as $index => $key) {
                if (isset($request->description[$index]) && $key != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Models\Onboard',
                            'translationable_id' => $onboard->id,
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
        return redirect()->route('admin.onboard.index')->with($output);
    }

    public function updateStatus(Request $request)
    {
        try {
            DB::beginTransaction();

            $onboard = Onboard::findOrFail($request->id);
            $onboard->status = $onboard->status == 1 ? 0 : 1;
            $onboard->save();

            $output = ['status' => 1, 'msg' => __('Status updated')];

            DB::commit();
        } catch (Exception $e) {

            $output = ['status' => 0, 'msg' => __('Something went wrong')];
            DB::rollBack();
        }

        return response()->json($output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $onboard = Onboard::findOrFail($id);
            $translation = Translation::where('translationable_type', 'App\Models\Onboard')
                ->where('translationable_id', $onboard->id);
            $translation->delete();
            $onboard->delete();

            $onboards = Onboard::latest('id')->paginate(10);
            $view = view('backends.onboard._table', compact('onboards'))->render();

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
}
