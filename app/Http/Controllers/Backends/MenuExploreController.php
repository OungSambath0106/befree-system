<?php

namespace App\Http\Controllers\Backends;

use Exception;
use App\helpers\AppHelper;
use App\Models\MenuExplore;
use App\Models\Translation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MenuExploreController extends Controller
{ 
    public function index()
    {
        if (!auth()->user()->can('menu.explore.view')) {
            abort(403, 'Unauthorized action.');
        }
        $menu_explores = MenuExplore::latest('id')->paginate(10);
        return view('backends.menu-explore.index', compact('menu_explores'));
    }
    public function create()
    {
        if (!auth()->user()->can('menu.explore.create')) {
            abort(403, 'Unauthorized action.');
        }
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];

        return view('backends.menu-explore._create', compact('language', 'default_lang'));
    }
    public function store(Request $request)
    {
        if (!auth()->user()->can('menu.explore.create')) {
            abort(403, 'Unauthorized action.');
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if (is_null($request->name[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'name', 'Name field is required!'
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

            $menu_explore = new MenuExplore();
            $menu_explore->name = $request->name[array_search('en', $request->lang)];
            $menu_explore->slug = Str::slug($request->name[array_search('en', $request->lang)]);
            $menu_explore->menu_url = $request->menu_url;
            $menu_explore->created_by = auth()->user()->id;
            $menu_explore->save();

            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->name[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\MenuExplore',
                        'translationable_id' => $menu_explore->id,
                        'locale' => $key,
                        'key' => 'name',
                        'value' => $request->name[$index],
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

        return redirect()->route('admin.explore_menu.index')->with($output);
    }
    public function show($id)
    {
        //
    }
    public function edit($id)
    {
        if (!auth()->user()->can('menu.explore.edit')) {
            abort(403, 'Unauthorized action.');
        }
        $menu_explore = MenuExplore::withoutGlobalScopes()->with('translations')->findOrFail($id);
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];
        return view('backends.menu-explore._edit', compact('menu_explore', 'language', 'default_lang'));
    }
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('menu.explore.edit')) {
            abort(403, 'Unauthorized action.');
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if (is_null($request->name[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'name', 'Name field is required!'
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

            $menu_explore = MenuExplore::findOrFail($id);
            $menu_explore->name = $request->name[array_search('en', $request->lang)];
            $menu_explore->slug = Str::slug($request->name[array_search('en', $request->lang)]);
            $menu_explore->menu_url = $request->menu_url;
            $menu_explore->save();

            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->name[$index] && $key != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Models\MenuExplore',
                            'translationable_id' => $menu_explore->id,
                            'locale' => $key,
                            'key' => 'name'],
                        ['value' => $request->name[$index]]
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

        return redirect()->route('admin.explore_menu.index')->with($output);
    }
    public function destroy($id)
    {
        if (!auth()->user()->can('menu.explore.delete')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            DB::beginTransaction();
            $menu_explore = MenuExplore::findOrFail($id);
            $translation = Translation::where('translationable_type','App\Models\MenuExplore')
                                        ->where('translationable_id',$menu_explore->id);

            $translation->delete();
            $menu_explore->delete();

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

            $menu_explore = MenuExplore::findOrFail($request->id);
            $menu_explore->status = $menu_explore->status == 'active' ? 'inactive' : 'active';
            $menu_explore->save();

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
        $menu_explores = MenuExplore::latest('id')->paginate(10);
        $view = view('backends.menu-explore._table', compact('menu_explores'))->render();

        return ['view' => $view];
    }
}
