<?php

namespace App\Http\Controllers\Backends;

use Exception;
use App\Models\Page;
use App\Models\Translation;
use App\Models\SectionTitle;
use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SectionTitleController extends Controller
{
    public function index()
    {
        $sectionTitles = SectionTitle::latest('id')->paginate(10);
        return view('backends.section-title.index', compact('sectionTitles'));
    }
    public function create()
    {
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];
        $pages = Page::pluck('title', 'id');
        return view('backends.section-title.create', compact('pages','language', 'default_lang'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);
        if (is_null($request->title[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'Title', 'The title field is required!'
                );
            });
        }

        try {
            DB::beginTransaction();
            $sectionTitle = new SectionTitle();
            $sectionTitle->title = $request->title[array_search('en', $request->lang)];
            $sectionTitle->default_title = $request->default_title[array_search('en', $request->lang)];
            $sectionTitle->page_id = $request->page_id;
            $sectionTitle->save();

            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->title[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\SectionTitle',
                        'translationable_id' => $sectionTitle->id,
                        'locale' => $key,
                        'key' => 'title',
                        'value' => $request->title[$index],
                    ));
                }
                if ($request->default_title[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\SectionTitle',
                        'translationable_id' => $sectionTitle->id,
                        'locale' => $key,
                        'key' => 'default_title',
                        'value' => $request->default_title[$index],
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

        return redirect()->route('admin.section_title.index')->with($output);
    }
    public function show($id)
    {
        //
    }
    public function edit($id)
    {
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];
        $sectionTitle = SectionTitle::withoutGlobalScopes()->with('translations')->findOrFail($id);
        $pages = Page::pluck('title', 'id');
        return view('backends.section-title.edit', compact('pages','sectionTitle', 'language', 'default_lang'));
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);
        if (is_null($request->title[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'Title', 'The title field is required!'
                );
            });
        }
        try{
        DB::beginTransaction();
            $sectionTitle = SectionTitle::findOrFail($id);
            $sectionTitle->title = $request->title[array_search('en', $request->lang)];
            $sectionTitle->default_title = $request->default_title[array_search('en', $request->lang)];
            $sectionTitle->page_id = $request->page_id;
            $sectionTitle->save();

            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->title[$index] && $key != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Models\SectionTitle',
                            'translationable_id' => $sectionTitle->id,
                            'locale' => $key,
                            'key' => 'title'],
                        ['value' => $request->title[$index]]
                    );
                }
                if ($request->default_title[$index] && $key != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Models\SectionTitle',
                            'translationable_id' => $sectionTitle->id,
                            'locale' => $key,
                            'key' => 'default_title'],
                        ['value' => $request->default_title[$index]]
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
        return redirect()->route('admin.section_title.index')->with($output);
    }
    public function renderTable()
    {
        $sectionTitles = SectionTitle::latest('id')->paginate(10);
        $view = view('backends.section-title._table', compact('sectionTitles'))->render();

        return ['view' => $view];
    }
}
