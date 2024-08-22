<?php

namespace App\Http\Controllers\Backends;

use Exception;
use Carbon\Carbon;
use App\Models\Setting;
use App\Models\Translation;
use Illuminate\Http\Request;
use App\helpers\ImageManager;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class BusinessSettingController extends Controller
{
    public function index ()
    {
        $data = [];
        $language = BusinessSetting::where('type', 'language')->first();
        $data['language'] = $language->value ?? null;
        $default_lang = 'en';
        $data['default_lang'] = json_decode($data['language'], true)[0]['code'];

        $setting = new BusinessSetting();
        $data['settings']                  = BusinessSetting::withoutGlobalScopes()->with('translations')->get();
        $data['company_name']              = @$setting->where('type', 'company_name')->first()->value;
        $data['phone']                     = @$setting->where('type', 'phone')->first()->value;
        $data['email']                     = @$setting->where('type', 'email')->first()->value;
        $data['company_address']           = @$setting->where('type', 'company_address')->first()->value;
        $data['copy_right_text']           = @$setting->where('type', 'copy_right_text')->first()->value;
        $data['timezone']                  = @$setting->where('type', 'timezone')->first()->value;
        $data['currency']                  = @$setting->where('type', 'currency')->first()->value;
        $data['link_google_map']           = @$setting->where('type', 'link_google_map')->first()->value;
        $data['company_description']       = @$setting->where('type', 'company_description')->first()->value;
        $data['company_short_description'] = @$setting->where('type', 'company_short_description')->first()->value;
        $data['whatsapp_number']           = @$setting->where('type', 'whatsapp_number')->first()->value??'';
        $data['history_of_chaufea']        = @$setting->where('type', 'history_of_chaufea')->first()->value;
        $data['foundation']                = @$setting->where('type', 'foundation')->first()->value??'';


        // contact info
        $data['contact_us_phone_number']   = @$setting->where('type', 'contact_us_phone_number')->first()->value;
        $data['contact_description']       = @$setting->where('type', 'contact_description')->first()->value;
        $data['extra_service_description'] = @$setting->where('type', 'extra_service_description')->first()->value;
        $data['video_trailer']             = @$setting->where('type', 'video_trailer')->first()->value??'';
        $data['link_full_video']           = @$setting->where('type', 'link_full_video')->first()->value??'';
        $data['company_sub_title']         = @$setting->where('type', 'company_sub_title')->first()->value??'';
        $data['auto_reply']                = @$setting->where('type', 'auto_reply')->first()->value??'';
        $data['sales_email']               = @$setting->where('type', 'sales_email')->first()->value??'';
        // imagee
        $data['image1'] = @$setting->where('type', 'image1')->first()->value;
        $data['image2'] = @$setting->where('type', 'image2')->first()->value;
        $data['image3'] = @$setting->where('type', 'image3')->first()->value;
        $data['image4'] = @$setting->where('type', 'image4')->first()->value;
        // account info
        $data['account_holder'] = @$setting->where('type', 'account_holder')->first()->value;
        $data['account_number'] = @$setting->where('type', 'account_number')->first()->value;
        $data['bank']           = @$setting->where('type', 'bank')->first()->value;
        $data['swift_code']     = @$setting->where('type', 'swift_code')->first()->value;
        $data['bank_address']   = @$setting->where('type', 'bank_address')->first()->value;
        $data['account_holder_address'] = @$setting->where('type', 'account_holder_address')->first()->value;
        $data['booking_policy'] = @$setting->where('type', 'booking_policy')->first()->value??'';
        $data['getInTouch']     = @$setting->where('type', 'getInTouch')->first()->value??'';
        $data['aboutUs_des']    = @$setting->where('type', 'aboutUs_des')->first()->value??'';
        $data['about_us_description']   = @$setting->where('type', 'about_us_description')->first()->value??'';
        $data['slider_title']           = @$setting->where('type', 'slider_title')->first()->value??'';
        $data['slider_description']     = @$setting->where('type', 'slider_description')->first()->value??'';

        $data['contacts']  = [];
        $contact = $setting->where('type', 'contact')->first();
        if ($contact) {
            $data['contacts'] = $contact->value;
        }

        $data['social_medias']  = [];
        $social_media = $setting->where('type', 'social_media')->first();
        if ($social_media) {
            $data['social_medias'] = $social_media->value;
        }
        
        $data['payments']  = [];
        $payment = $setting->where('type', 'payment')->first();
        if ($payment) {
            $data['payments'] = $payment->value;
        }

        $data['web_header_logo'] = @$setting->where('type', 'web_header_logo')->first()->value;
        $data['web_banner_logo'] = @$setting->where('type', 'web_banner_logo')->first()->value;
        $data['fav_icon'] = @$setting->where('type', 'fav_icon')->first()->value;

        if (request()->ajax()) {
            $key = request('key');
            if(request()->type == 'key_social'){
                $tr = view('backends.setting.partials._social_media_tr', compact('key'))->render();
            }elseif(request()->type == 'key_payment'){
                $tr = view('backends.setting.partials._payment_tr', compact('key'))->render();
            }else{
                $tr = view('backends.setting.partials._contact_tr', compact('key'))->render();
            }
            return response()->json([
                'tr' => $tr
            ]);
        }
        return view('backends.setting.index', $data);
    }

    public function update (Request $request)
    {
        // dd($request->all());
        $request->validate([

        ]);
        try {
            DB::beginTransaction();
            $all_input = $request->all();
            foreach ($all_input as $input_name => $input_value) {
                // save video
                if ($input_name == 'video_trailer') {
                    $old_video = BusinessSetting::where('type', $input_name)->first()->value;
                    $video = $request->$input_name;
                    $extension = $video->getClientOriginalExtension()??'mp4';
                    $oldFilePath = public_path('uploads/business_settings/' . $old_video);
                    if ($old_video && File::exists($oldFilePath)) {
                        File::delete($oldFilePath);
                    }
                    $fileName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $extension;
                    $filePath = public_path('uploads/business_settings');
                    $video->move($filePath, $fileName);
                    BusinessSetting::updateOrCreate(
                        [
                            'type' => $input_name,
                        ], [
                            'value' => $fileName,
                        ]
                    );
                    continue;
                }
                // save image
                if ($request->hasFile($input_name) && !in_array($input_name, ['social_media', 'payment', 'contact', 'image1_names', 'image2_names', 'image3_names', 'image4_names'])) {
                    $old_image = BusinessSetting::where('type', $input_name)->first();
                    $image = ImageManager::update('uploads/business_settings/', $old_image, $request->$input_name);

                    BusinessSetting::updateOrCreate(
                        [
                            'type' => $input_name,
                        ], [
                            'value' => $image,
                        ]
                    );
                    continue;
                }

                // save text
                if (!in_array($input_name, ['_token', '_method', 'social_media', 'payment', 'contact', 'image1_names', 'image2_names', 'image3_names', 'image4_names','lang','video_trailer','image_contact'])) {
                    if ($input_name == 'sales_email') {
                        BusinessSetting::updateOrCreate(
                            [
                                'type' => $input_name,
                            ], [
                                'value' => $input_value,
                            ]
                        );
                        continue;
                    }
                    if (in_array($input_name, ['company_name', 'company_address', 'copy_right_text', 'company_short_description', 'company_description','contact_description','getInTouch','extra_service_description','about_us_description','booking_policy','slider_title','slider_description','history_of_chaufea','foundation','company_sub_title'])) {

                        BusinessSetting::updateOrCreate(
                            [
                                'type' => $input_name,
                            ], [
                                'value' => $input_value[array_search('en', $request->lang)],
                            ]
                        );
                        $setting = BusinessSetting::where('type', $input_name)->first();

                        $data = [];
                        foreach ($request->lang as $index => $key) {
                            if (is_array($request->$input_name) && isset($request->$input_name[$index])) {
                                Translation::updateOrInsert(
                                    [
                                        'translationable_type' => 'App\Models\BusinessSetting',
                                        'translationable_id' => $setting->id,
                                        'locale' => $key,
                                        'key' => $input_name
                                    ],
                                    [
                                        'value' => $request->$input_name[$index]
                                    ]
                                );
                            }
                        }
                        Translation::insert($data);

                    } else {
                        BusinessSetting::updateOrCreate(
                            [
                                'type' => $input_name,
                            ], [
                                'value' => $input_value,
                            ]
                        );
                    }
                }


                // dd($request->all());

            }
            // save the four image in homepage
            if ($request->image1_names && $request->filled('image1_names')) {
                $image1_names = $request->image1_names;
                // dd($image1_names);
                BusinessSetting::updateOrCreate(
                    [
                        'type' => 'image1',
                    ], [
                        'value' => $image1_names,
                    ]
                );
                // $service_gallery->image = explode(' ', $request->image_names);
                // foreach ($service_gallery->image as $key => $image) {

                    if (file_exists(public_path('uploads/temp/' . $image1_names))) {
                        $image = File::move(public_path('uploads/temp/' . $image1_names), public_path('uploads/business_settings/'. $image1_names));
                    }
                // }
            }

            if ($request->image2_names && $request->filled('image2_names')) {
                $image2_names = $request->image2_names;
                // dd($image2_names);
                BusinessSetting::updateOrCreate(
                    [
                        'type' => 'image2',
                    ], [
                        'value' => $image2_names,
                    ]
                );
                // $service_gallery->image = explode(' ', $request->image_names);
                // foreach ($service_gallery->image as $key => $image) {

                    if (file_exists(public_path('uploads/temp/' . $image2_names))) {
                        $image = File::move(public_path('uploads/temp/' . $image2_names), public_path('uploads/business_settings/'. $image2_names));
                    }
                // }
            }

            if ($request->image3_names && $request->filled('image3_names')) {
                $image3_names = $request->image3_names;
                BusinessSetting::updateOrCreate(
                    [
                        'type' => 'image3',
                    ], [
                        'value' => $image3_names,
                    ]
                );
                // $service_gallery->image = explode(' ', $request->image_names);
                // foreach ($service_gallery->image as $key => $image) {
                    // $directory = public_path('uploads/business_settings');

                    if (file_exists(public_path('uploads/temp/' . $image3_names))) {
                        $image = File::move(public_path('uploads/temp/' . $image3_names), public_path('uploads/business_settings/'. $image3_names));
                    }
                // }
            }

            if ($request->image4_names && $request->filled('image4_names')) {
                $image4_names = $request->image4_names;
                BusinessSetting::updateOrCreate(
                    [
                        'type' => 'image4',
                    ], [
                        'value' => $image4_names,
                    ]
                );
                // $service_gallery->image = explode(' ', $request->image_names);
                // foreach ($service_gallery->image as $key => $image) {
                    // $directory = public_path('uploads/business_settings');

                    if (file_exists(public_path('uploads/temp/' . $image4_names))) {
                        $image = File::move(public_path('uploads/temp/' . $image4_names), public_path('uploads/business_settings/'. $image4_names));
                    }
                // }
            }

            // contact
            $contact = [];

            if ($request->has('contact')) {
                foreach ($request->contact['title'] as $key => $value) {
                    $item = [];
                    $item['title'] = $request->contact['title'][$key];
                    $item['link'] = $request->contact['link'][$key];

                    // Handle file upload
                    if ($request->hasFile('contact.icon.'.$key)) {
                        $uploadedFile = $request->file('contact.icon.'.$key);
                        $icon = ImageManager::update('uploads/social_media/', $request->contact['old_icon'][$key], $uploadedFile);
                        // $item['icon'] = asset('uploads/social_media/'.$icon);
                        $item['icon'] = $icon;
                    } else {
                        // No new file uploaded, use the old icon if available
                        $item['icon'] = $request->contact['old_icon'][$key] ?? null;
                    }

                    // Check status
                    $item['status'] = $request->has('contact.status_'.$key) ? 1 : 0;

                    // Push the item into the $contact array
                    $contact[] = $item;
                }
            }

            BusinessSetting::updateOrCreate(
                [
                    'type' => 'contact',
                ], [
                    'value' => json_encode($contact),
                ]
            );

            // social media
            $social_media = [];

            if ($request->has('social_media')) {
                foreach ($request->social_media['title'] as $key => $value) {
                    $item = [];
                    $item['title'] = $request->social_media['title'][$key];
                    $item['link'] = $request->social_media['link'][$key];

                    // Handle file upload
                    if ($request->hasFile('social_media.icon.'.$key)) {
                        $uploadedFile = $request->file('social_media.icon.'.$key);
                        $icon = ImageManager::update('uploads/social_media/', $request->social_media['old_icon'][$key], $uploadedFile);
                        // $item['icon'] = asset('uploads/social_media/'.$icon);
                        $item['icon'] = $icon;
                    } else {
                        // No new file uploaded, use the old icon if available
                        $item['icon'] = $request->social_media['old_icon'][$key] ?? null;
                    }

                    // Check status
                    $item['status'] = $request->has('social_media.status_'.$key) ? 1 : 0;

                    // Push the item into the $social_media array
                    $social_media[] = $item;
                }
            }

            BusinessSetting::updateOrCreate(
                [
                    'type' => 'social_media',
                ], [
                    'value' => json_encode($social_media),
                ]
            );

           // payment method
           $payment = [];

           if ($request->has('payment')) {
               foreach ($request->payment['title'] as $key => $value) {
                   $item = [];
                   $item['title'] = $request->payment['title'][$key];

                   // Handle file upload
                   if ($request->hasFile('payment.icon.'.$key)) {
                       $uploadedFile = $request->file('payment.icon.'.$key);
                       $icon = ImageManager::update('uploads/social_media/', $request->payment['old_icon'][$key], $uploadedFile);
                    //    $item['icon'] = asset('uploads/social_media/'.$icon);
                       $item['icon'] = $icon;
                   } else {
                       // No new file uploaded, use the old icon if available
                       $item['icon'] = $request->payment['old_icon'][$key] ?? null;
                   }

                   // Check status
                   $item['status'] = $request->has('payment.status_'.$key) ? 1 : 0;

                   // Push the item into the $payment array
                   $payment[] = $item;
               }
           }

           BusinessSetting::updateOrCreate(
               [
                   'type' => 'payment',
               ], [
                   'value' => json_encode($payment),
               ]
           ); 

            DB::commit();
            return redirect()->route('admin.setting.index')->with([
                'success' => 1,
                'msg' => __('Updated sucessfully')
            ]);

        } catch (Exception $e) {
            dd($e);
            DB::rollBack();
            return redirect()->route('admin.setting.index')->with([
                'success' => 0,
                'msg' => __('Something went wrong')
            ]);

        }

    }

    public function webContent ()
    {
        $data = [];
        $setting = new BusinessSetting();
        $data['why_ci'] = @$setting->where('type', 'why_ci')->first()->value;
        $data['how_to_enter'] = @$setting->where('type', 'how_to_enter')->first()->value;
        $data['offline_application'] = @$setting->where('type', 'offline_application')->first()->value;
        $data['why_should_i_sponsor'] = @$setting->where('type', 'why_should_i_sponsor')->first()->value;
        $data['how_to_participate'] = @$setting->where('type', 'how_to_participate')->first()->value;

        // return json_decode($data['why_ci'], true);

        if (request()->ajax()) {
            if (request('table') == 'how_to_enter') {
                $key = request('key');
                $tr = view('backends.setting.partials._how_to_enter_tr', compact('key'))->render();
                return response()->json([
                    'tr' => $tr
                ]);
            }
            if (request('table') == 'why_sponsor') {
                $key = request('key');
                $tr = view('backends.setting.partials._why_should_i_sponser_tr', compact('key'))->render();
                return response()->json([
                    'tr' => $tr
                ]);
            }

        }

        return view('backends.setting.web_content', $data);
    }

    public function webContentUpdate (Request $request)
    {
        // return $request->all();
        try {
            DB::beginTransaction();

            $all_input = $request->all();
            foreach ($all_input as $input_name => $input_value) {
                // save file
                if ($request->hasFile($input_name)) {
                    $old_file = BusinessSetting::where('type', $input_name)->first()->value;
                    $file = ImageManager::update('uploads/business_settings/', $old_file, $request->$input_name);

                    BusinessSetting::updateOrCreate(
                        [
                            'type' => $input_name,
                        ], [
                            'value' => $file,
                        ]
                    );
                    continue;
                }

                // save text
                if (!in_array($input_name, ['_token', '_method', 'why_ci', 'how_to_enter', 'why_should_i_sponsor'])) {
                    BusinessSetting::updateOrCreate(
                        [
                            'type' => $input_name,
                        ], [
                            'value' => $input_value,
                        ]
                    );
                }
            }

            // why cigfg
            $why_ci = [];
            if ($request->why_ci) {
                foreach ($request->why_ci['title'] as $key => $value) {
                    $item['title'] = $request->why_ci['title'][$key];
                    $item['description'] = $request->why_ci['description'][$key];
                    array_push($why_ci, $item);
                }

                BusinessSetting::updateOrCreate(
                    [
                        'type' => 'why_ci',
                    ], [
                        'value' => json_encode($why_ci),
                    ]
                );
            }

            // how to enter
            $how_to_enter = [];
            if ($request->how_to_enter) {
                foreach ($request->how_to_enter['title'] as $key => $value) {
                    $item['title'] = $request->how_to_enter['title'][$key];
                    $item['description'] = $request->how_to_enter['description'][$key];
                    array_push($how_to_enter, $item);
                }

                BusinessSetting::updateOrCreate(
                    [
                        'type' => 'how_to_enter',
                    ], [
                        'value' => json_encode($how_to_enter),
                    ]
                );
            }

            // why should i sponsor
            $why_should_i_sponsor = [];
            if ($request->why_should_i_sponsor) {
                foreach ($request->why_should_i_sponsor['description'] as $key => $value) {
                    $item['description'] = $request->why_should_i_sponsor['description'][$key];

                    $request_icon = $request->why_should_i_sponsor['icon'] ?? 0;

                    if($request_icon != 0) {
                        if (in_array($key, array_keys($request->why_should_i_sponsor['icon']))) {
                            $icon = ImageManager::update('uploads/business_settings/', $request->why_should_i_sponsor['old_icon'][$key], $request->why_should_i_sponsor['icon'][$key]);
                            $item['icon'] = asset('uploads/business_settings/'.$icon);
                        } else {
                            $item['icon'] = $request->why_should_i_sponsor['old_icon'][$key] ?? null;
                        }
                    } else {
                        $item['icon'] = $request->why_should_i_sponsor['old_icon'][$key];
                    }

                    // if (array_key_exists('status_'. $key, $request->why_should_i_sponsor)) {
                    //     $item['status'] = 1;
                    // } else {
                    //     $item['status'] = 0;
                    // }

                    array_push($why_should_i_sponsor, $item);
                }
                BusinessSetting::updateOrCreate(
                    [
                        'type' => 'why_should_i_sponsor',
                    ], [
                        'value' => json_encode($why_should_i_sponsor),
                    ]
                );
            }

            DB::commit();
            return redirect()->back()->with([
                'success' => 1,
                'msg' => __('Updated sucessfully')
            ]);

        } catch (Exception $e) {
            dd($e);
            DB::rollBack();
            return redirect()->back()->with([
                'success' => 0,
                'msg' => __('Something went wrong')
            ]);

        }
    }

    public function smtp_settings()
    {
        return view('backends.setting.mail.index');
    }
    public function update_environment(Request $request)
    {
        try{
            foreach ($request->types as $key => $type) {
                $this->overWriteEnvFile($type, $request[$type]);
           }
           $output = [
               'success' => 1,
               'msg' => __('Updated sucessfully')
           ];
        }catch(Exception $e){
            // dd($e);
            $output = [
                'success' => 0,
                'msg' => $e->getMessage()
            ];
        }

       return redirect()->back()->with($output);
    }

    public function overWriteEnvFile($type, $val)
    {

            $path = base_path('.env');

            // Backup the .env file before making changes
            // $backupPath = base_path('.env.backup');
            // chmod($backupPath, 0664);
            // copy($path, $backupPath);
            if (file_exists($path)) {
                $val = '"' . trim($val) . '"';
                $envContent = file_get_contents($path);

                // Check if the type exists in the .env file
                if (is_numeric(strpos($envContent, $type)) && strpos($envContent, $type) >= 0) {
                    $envContent = str_replace(
                        $type . '="' . env($type) . '"',
                        $type . '=' . $val,
                        $envContent
                    );
                } else {
                    $envContent .= "\r\n" . $type . '=' . $val;
                }

                // Update the .env file
                file_put_contents($path, $envContent);
            }

    }

}
