<?php

namespace App\Http\Controllers\Backends;

use Exception;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use App\helpers\GlobalFunction;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactUsController extends Controller
{
    public function index()
    {
        $setting = BusinessSetting::all();
        $data['contact_description']  = @$setting->where('type', 'contact_description')->first()->value;
        $data['phone']                = @$setting->where('type', 'phone')->first()->value;
        $data['email']                = @$setting->where('type', 'email')->first()->value;
        $data['company_address']      = @$setting->where('type', 'company_address')->first()->value;
        return view('frontends.contact.contact',$data);
    }
    public function contact()
    {
        if (!auth()->user()->can('contact.view')) {
            abort(403, 'Unauthorized action.');

        }
        $customer_contacts = ContactUs::latest('id')->paginate(10);
        GlobalFunction::seenNotification('App\Models\ContactUs');
        return view('backends.contact_us.index',compact('customer_contacts'));
    }
    public function store(Request $request)
    {
            try{
                $validator = Validator::make($request->all(), [
                    'username' => 'required',
                    'email' => 'required',
                    'phone' => 'required',
                    'subject' => 'required',
                    'message' => 'required',
                ]);
                if ($validator->fails()) {
                    $firstError = $validator->errors()->first();
                    $output = [
                        'success' => 0,
                        'msg' => $firstError,
                    ];

                    return redirect()->back()->with($output);
                }
                $contact = new ContactUs();
                $contact->username = $request->username;
                $contact->email    = $request->email;
                $contact->phone    = $request->phone;
                $contact->subject  = $request->subject;
                $contact->message  = $request->message;
                $contact->save();
                GlobalFunction::storeNotification('App\Models\ContactUs', $contact->id);
                $setting = BusinessSetting::all();
                $data['email']     = @$setting->where('type', 'email')->first()->value??'';
                $reply_message     = @$setting->where('type', 'auto_reply')->first()->value??'';
                if($contact){
                    $data["title"]      = "Hi Admin, You have new contact ".$request->username;
                    $data["body"]       =
"This is new contact from your website phoum chaufea, Please kindly check with bellow . \nNew contact info: \nUsername : ".$request->username.",\nEmail : ".$request->email.",\nPhone : ".$request->phone.",\nSubject : ".$request->subject.",\nMessage : ".$request->message.",\nThanks you.";
                    $data['admin_email'] =$data['email'];
                    Mail::send([],[], function($message)use($data) {
                        $message->to($data['admin_email'], $data['admin_email'])
                                ->subject($data["title"])
                                ->setBody($data["body"]);
                    });

                    $data["email"]      = $request->email;
                    $data["title"]      = "Welcome ".$request->username;
                    $html_message = "
Dear " . $request->username . ",
" . $reply_message;

                    $plain_text_message = strip_tags($html_message);
                    $data["html_body"] = $html_message;
                    $data["plain_text_body"] = $plain_text_message;
                    Mail::send([],[], function($message)use($data) {
                        $message->to($data["email"], $data["email"])
                                ->subject($data["title"])
                                ->setBody($data["html_body"], 'text/html');
                                $message->addPart($data["plain_text_body"], 'text/plain');
                    });

                    $output = [
                        'success' => 1,
                        'msg' => __('Contact successfully'),
                    ];
                }

            }catch(Exception $e){
                // dd($e);
                DB::rollBack();
                $output = [
                    'success' => 0,
                    'msg' => __('Something went wrong'),
                ];

            }
        return redirect()->back()->with($output);
    }
    public function show($id)
    {
        if (!auth()->user()->can('contact.edit')) {
            abort(403, 'Unauthorized action.');
        }
        $data['contact'] = ContactUs::findOrFail($id);
        return view('backends.contact_us.view',$data);
    }
    public function adminReply(Request $request, $id)
    {
        if (!auth()->user()->can('contact.edit')) {
            abort(403, 'Unauthorized action.');
        }
        $customer = ContactUs::findOrFail($id);
        $data['email'] = $customer->email;
        $data['title'] = $request->input('subject');
        $reply_message = "
Dear ".$customer->username.",\n".
$request->input('message')."
Best regards,
phoum chaufea Team";

        $data['body']  = $reply_message;
        Mail::send([], [], function ($message) use ($data) {
            $message->to($data['email'], $data['email'])
                ->subject($data['title'])
                ->setBody($data['body']);
        });

        $output = [
            'success' => 1,
            'msg' => __('Reply successfully'),
        ];

        return redirect()->route('admin.contact.index')->with($output);
    }
    public function destroy($id)
    {
        if (!auth()->user()->can('contact.delete')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            DB::beginTransaction();
            $contact = ContactUs::findOrFail($id);
            $contact->delete();
            $customer_contacts = ContactUs::latest('id')->paginate(10);
            $view = view('backends.contact_us._table', compact('customer_contacts'))->render();
            DB::commit();
            $output = [
                'status' => 1,
                'view' => $view,
                'msg' => __('Deleted successfully'),
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
