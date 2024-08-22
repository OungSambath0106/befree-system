<?php

namespace App\Http\Controllers\Backends;

use Exception;
use App\Models\Menu;
use App\helpers\AppHelper;
use App\Models\Translation;
use Illuminate\Support\Str;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('menu.view')) {
            abort(403, 'Unauthorized action.');
        }
        $menus = Menu::latest('id')->paginate(10);
        return view('backends.menu.index', compact('menus'));
    }
    public function create()
    {
        if (!auth()->user()->can('menu.create')) {
            abort(403, 'Unauthorized action.');
        }
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];

        return view('backends.menu._create', compact('language', 'default_lang'));
    }
    public function store(Request $request)
    {
        if (!auth()->user()->can('menu.create')) {
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

            $menu = new Menu;
            $menu->name = $request->name[array_search('en', $request->lang)];
            $menu->slug = Str::slug($request->name[array_search('en', $request->lang)]);
            $menu->menu_url = $request->menu_url;
            $menu->created_by = auth()->user()->id;
            $menu->save();

            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->name[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Menu',
                        'translationable_id' => $menu->id,
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

        return redirect()->route('admin.menu.index')->with($output);
    }
    public function show($id)
    {
        //
    }
    public function edit($id)
    {
        if (!auth()->user()->can('menu.edit')) {
            abort(403, 'Unauthorized action.');

        }
        $menu = Menu::withoutGlobalScopes()->with('translations')->findOrFail($id);
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];
        return view('backends.menu._edit', compact('menu', 'language', 'default_lang'));
    }
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('menu.edit')) {
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

            $menu = Menu::findOrFail($id);
            $menu->name = $request->name[array_search('en', $request->lang)];
            $menu->slug = Str::slug($request->name[array_search('en', $request->lang)]);
            $menu->menu_url = $request->menu_url;
            $menu->save();

            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->name[$index] && $key != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Models\Menu',
                            'translationable_id' => $menu->id,
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

        return redirect()->route('admin.menu.index')->with($output);
    }
    public function destroy($id)
    {
        if (!auth()->user()->can('menu.delete')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            DB::beginTransaction();
            $menu = Menu::findOrFail($id);
            $translation = Translation::where('translationable_type','App\Models\Menu')
                                        ->where('translationable_id',$menu->id);

            $translation->delete();
            $menu->delete();

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

            $menu = Menu::findOrFail($request->id);
            $menu->status = $menu->status == 'active' ? 'inactive' : 'active';
            $menu->save();

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
        $menus = Menu::latest('id')->paginate(10);
        $view = view('backends.menu._table', compact('menus'))->render();

        return ['view' => $view];
    }
}
