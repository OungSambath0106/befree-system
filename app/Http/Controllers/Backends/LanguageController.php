<?php

namespace App\Http\Controllers\Backends;

use Exception;
use Illuminate\Http\Request;
use RecursiveIteratorIterator;
use App\Models\BusinessSetting;
use RecursiveDirectoryIterator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;

class LanguageController extends Controller
{

    public function index ()
    {
        $language = BusinessSetting::where('type', 'language')->first()->value;
        return view('backends.setting.language.index', compact('language'));
    }

    public function create ()
    {
        return view('backends.setting.language.partials.create');
    }

    public function store (Request $request)
    {
        // dd(1);
        // dd($request->all());
        $request->validate([
            'name' => 'required',
            'code' => 'required',
        ], [
            'name.required'  => 'Language is required!',
            'code.required'  => 'Country Code is required!',
        ]);

        try {
            DB::beginTransaction();

            $language = BusinessSetting::where('type', 'language')->first();
            $lang_array = [];
            $codes = [];
            foreach (json_decode($language['value'], true) as $key => $data) {
                // dd($language);
                if ($data['code'] != $request['code']) {
                    if (!array_key_exists('default', $data)) {
                        $default = array('default' => ($data['code'] == 'gb') ? true : false);
                        $data = array_merge($data, $default);
                    }
                    array_push($lang_array, $data);
                    array_push($codes, $data['code']);
                }
            }
            array_push($codes, $request['code']);

            // if (!file_exists(base_path('resources/lang/' . $request['code']))) {
            //     mkdir(base_path('resources/lang/' . $request['code']), 0777, true);
            // }

            $lang_file = fopen(base_path('resources/lang/' . $request['code'] . '.json'), "w") or die("Unable to open file!");
            $read = file_get_contents(base_path('resources/lang/en.json'));
            fwrite($lang_file, $read);

            $last_array = last(json_decode($language['value'], true));
            array_push($lang_array, [
                'id' => $last_array['id'] + 1,
                'name' => $request['name'],
                'code' => $request['code'],
                'direction' => $request['direction'] ?? 'ltr',
                'status' => 1,
                'default' => false,
            ]);

            BusinessSetting::updateOrInsert(['type' => 'language'], [
                'value' => $lang_array
            ]);

            DB::table('business_settings')->updateOrInsert(['type' => 'pnc_language'], [
                'value' => json_encode($codes),
            ]);

            DB::commit();

            $output = [
                'success' => 1,
                'msg' => __('Create successfully'),
            ];
        } catch (Exception $e) {
            DB::rollBack();

            $output = [
                'success' => 0,
                'msg' => __('Something went wrong'),
            ];
        }

        return redirect()->back()->with($output);
        // Toastr::success('Language Added!');
    }

    public function edit (Request $request)
    {
        $language = BusinessSetting::where('type', 'language')->first();
        $lang = [];
        foreach (json_decode($language['value'], true) as $key => $data) {
            if ($data['id'] == $request['id']) {
                $lang = [
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'code' => $data['code'],
                ];
            }

        }

        return view('backends.setting.language.partials.edit', compact('lang'));
    }

    public function update (Request $request)
    {
        $request->validate([
            'name' => 'required',
        ], [
            'name.required'  => 'Language is required!',
        ]);

        try {
            DB::beginTransaction();

            $language = BusinessSetting::where('type', 'language')->first();
            $lang_array = [];
            $lang_name_code = [];
            foreach (json_decode($language['value'], true) as $key => $data) {
                if ($data['id'] == $request['id']) {
                    $lang = [
                        'id' => $data['id'],
                        'name' => $request['name'],
                        'direction' => $request['direction'] ?? 'ltr',
                        'code' => $request['code'],
                        'status' => 0,
                        'default' => (array_key_exists('default', $data) ? $data['default'] : (($data['code'] == 'en') ? true : false)),
                    ];
                    array_push($lang_array, $lang);
                    // array_push($lang_name_code, [$lang['name'] => $lang['code']]);
                    $lang_name_code += [$lang['name'] => $lang['code']];

                } else {
                    $lang = [
                        'id' => $data['id'],
                        'name' => $data['name'],
                        'direction' => $data['direction'] ?? 'ltr',
                        'code' => $data['code'],
                        'status' => $data['status'],
                        'default' => (array_key_exists('default', $data) ? $data['default'] : (($data['code'] == 'en') ? true : false)),
                    ];
                    array_push($lang_array, $lang);
                    $lang_name_code += [$lang['name'] => $lang['code']];
                }
            }
            BusinessSetting::where('type', 'language')->update([
                'value' => $lang_array
            ]);

            // available_locales
            config()->set('app.available_locales', $lang_name_code);

            DB::commit();

            $output = [
                'success' => 1,
                'msg' => __('Update Successfully')
            ];

        } catch (Exception $e) {
            DB::rollBack();

            $output = [
                'success' => 0,
                'msg' => __('Something went wrong'),
            ];
        }

        return redirect()->back()->with($output);

    }

    public function delete (Request $request)
    {
        // dd($request->all());

        try {
            DB::beginTransaction();

            $language = BusinessSetting::where('type', 'language')->first();

            $code = $request->code;
            $del_default = false;
            foreach (json_decode($language['value'], true) as $key => $data) {
                if ($data['code'] == $code && array_key_exists('default', $data) && $data['default'] == true) {
                    $del_default = true;
                }
            }

            $lang_array = [];
            foreach (json_decode($language['value'], true) as $key => $data) {
                if ($data['id'] != $request->id) {
                    $lang_data = [
                        'id' => $data['id'],
                        'name' => $data['name'],
                        'direction' => $data['direction'] ?? 'ltr',
                        'code' => $data['code'],
                        'status' => ($del_default == true && $data['code'] == 'en') ? 1 : $data['status'],
                        'default' => ($del_default == true && $data['code'] == 'en') ? true : (array_key_exists('default', $data) ? $data['default'] : (($data['code'] == 'en') ? true : false)),
                    ];
                    array_push($lang_array, $lang_data);
                }
            }

            BusinessSetting::where('type', 'language')->update([
                'value' => $lang_array
            ]);

            $file = base_path('resources/lang/' . $request->code . '.json');
            if (file_exists($file)) {
                unlink($file);
            }

            $languages = array();
            $pnc_language = BusinessSetting::where('type', 'pnc_language')->first();
            foreach (json_decode($pnc_language['value'], true) as $key => $data) {
                if ($data != $request->code) {
                    array_push($languages, $data);
                }
            }
            if (in_array('en', $languages)) {
                unset($languages[array_search('en', $languages)]);
            }
            array_unshift($languages, 'en');

            DB::table('business_settings')->updateOrInsert(['type' => 'pnc_language'], [
                'value' => json_encode($languages),
            ]);

            $language = BusinessSetting::where('type', 'language')->first()->value;
            $view = view('backends.setting.language.partials._table', compact('language'))->render();

            DB::commit();

            $output = [
                'status' => 1,
                'msg' => __('Deleted Successfully'),
                'view' => $view,
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

    public function translate (Request $request)
    {
        $lang = $request->code;
        $full_data = json_decode(File::get(base_path('resources/lang/' . $lang . '.json')));

        $lang_data = [];
        // ksort($full_data);
        foreach ($full_data as $key => $data) {
            array_push($lang_data, ['key' => $key, 'value' => $data]);
        }

        // dd($lang_data);

        return view('backends.setting.language.translate', compact('lang', 'lang_data'));
    }

    public function translate_submit(Request $request, $lang)
    {
        $full_data = json_decode(File::get(base_path('resources/lang/' . $lang . '.json')));

        $key = $request->key;
        $value = mb_convert_encoding($request->value, 'UTF-8'); // UTF-8 encoding
        $full_data->$key = $value;

        file_put_contents(base_path('resources/lang/' . $lang . '.json'), json_encode($full_data, JSON_PRETTY_PRINT));
    }

    public function updateStatus (Request $request)
    {
        try {
            DB::beginTransaction();

            $language = BusinessSetting::where('type', 'language')->first();
            $lang_array = [];
            foreach (json_decode($language['value'], true) as $key => $data) {
                if ($data['id'] == $request['id']) {
                    $lang = [
                        'id' => $data['id'],
                        'name' => $data['name'],
                        'direction' => $data['direction'] ?? 'ltr',
                        'code' => $data['code'],
                        'status' => $data['status'] == 1 ? 0 : 1,
                        'default' => (array_key_exists('default', $data) ? $data['default'] : (($data['code'] == 'en') ? true : false)),
                    ];
                    array_push($lang_array, $lang);
                } else {
                    $lang = [
                        'id' => $data['id'],
                        'name' => $data['name'],
                        'direction' => $data['direction'] ?? 'ltr',
                        'code' => $data['code'],
                        'status' => $data['status'],
                        'default' => (array_key_exists('default', $data) ? $data['default'] : (($data['code'] == 'en') ? true : false)),
                    ];
                    array_push($lang_array, $lang);
                }
            }
            $businessSetting = BusinessSetting::where('type', 'language')->update([
                'value' => $lang_array
            ]);

            $output = ['status' => 1, 'msg' => __('Status updated')];

            DB::commit();
        } catch (Exception $e) {

            $output = ['status' => 0, 'msg' => __('Something went wrong')];
            DB::rollBack();
        }

        return response()->json($output);
    }

    public function update_default_status(Request $request)
    {
        try {
            DB::beginTransaction();

            $language = BusinessSetting::where('type', 'language')->first();
            $lang_array = [];
            foreach (json_decode($language['value'], true) as $key => $data) {
                if ($data['id'] == $request['id']) {
                    $lang = [
                        'id' => $data['id'],
                        'name' => $data['name'],
                        'direction' => $data['direction'] ?? 'ltr',
                        'code' => $data['code'],
                        'status' => 1,
                        'default' => true,
                    ];
                    array_push($lang_array, $lang);
                } else {
                    $lang = [
                        'id' => $data['id'],
                        'name' => $data['name'],
                        'direction' => $data['direction'] ?? 'ltr',
                        'code' => $data['code'],
                        'status' => $data['status'],
                        'default' => false,
                    ];
                    array_push($lang_array, $lang);
                }
            }
            BusinessSetting::where('type', 'language')->update([
                'value' => $lang_array
            ]);
            $language = BusinessSetting::where('type', 'language')->first()->value;
            // $view = view('backends.setting.language.partials._table', compact('language'))->render();

            DB::commit();

            $output = [
                'status' => 1,
                'msg' => __('Update Successfully'),
                // 'view' => $view,
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

}
