<?php

namespace App\Http\Controllers\Backends;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\helpers\ImageManager;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->can('user.view')) {
            abort(403, 'Unauthorized action.');
        }
        $users = User::when($request->start_date && $request->end_date, function ($query) use ($request) {
            $query->whereDate('created_at', '>=', $request->start_date)
                ->whereDate('created_at', '<=', $request->end_date);
        })
            ->latest('id')
            ->paginate(10);
            
        return view('backends.user.index', compact('users'));
    }
    public function create()
    {
        if (!auth()->user()->can('user.create')) {
            abort(403, 'Unauthorized action.');
        }
        $roles = Role::select('name','id')
                ->pluck('name','id');

        return view('backends.user.create', compact('roles'));
    }
    public function store(Request $request)
    {
        if (!auth()->user()->can('user.create')) {
            abort(403, 'Unauthorized action.');
        }
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required',
            // 'user_id' => 'required|unique:users,user_id',
            'phone' => 'required',
            'role' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with(['success' => 0, 'msg' => __('Invalid form input')]);
        }

        try {
            DB::beginTransaction();

            $user = new User;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->name = $request->username;
            // $user->user_id = $request->user_id;
            $user->phone = $request->phone;
            $user->telegram = $request->telegram ?? null;
            $user->email = $request->email;
            $user->password = Hash::make($request['password']);

            $role = Role::findOrFail($request->role);
            $user->assignRole($role->name);

            if ($request->filled('image_names')) {
                $user->image = $request->image_names;
                $directory = public_path('uploads/users');
                if (!\File::exists($directory)) {
                    \File::makeDirectory($directory, 0777, true);
                }

                $image = \File::move(public_path('uploads/temp/' . $request->image_names), public_path('uploads/users/'. $request->image_names));

            }

            $user->save();
            DB::commit();
            $output = [
                'success' => 1,
                'msg' => __('Created successfully')
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $output = [
                'success' => 0,
                'msg' => __('Something went wrong')
            ];
        }

        return redirect()->route('admin.user.index')->with($output);
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
        if (!auth()->user()->can('user.edit')) {
            abort(403, 'Unauthorized action.');
        }
        $user = User::findOrFail($id);
        $roles = Role::select('name','id')
                ->pluck('name','id');
        return view('backends.user.edit', compact('user', 'roles'));
    }
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('user.edit')) {
            abort(403, 'Unauthorized action.');
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required',
            // 'user_id' => 'required|unique:users,user_id,' . $id,
            'phone' => 'required',
            'role' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            // 'email' => ['required', 'email', Rule::unique('users')->ignore($id)],
            'password' => 'nullable|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with(['success' => 0, 'msg' => __('Invalid form input')]);
        }

        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->name = $request->username;
            // $user->user_id = $request->user_id;
            $user->phone = $request->phone;
            $user->telegram = $request->telegram ?? null;
            $user->email = $request->email;

            $role_id        = $request->role;
            $user_role      = $user->roles->first();
            $previous_role  = !empty($user_role->id) ? $user_role->id : 0;
            if ($previous_role != $role_id) {
                if (!empty($previous_role)) {
                    $user->removeRole($user_role->name);
                }

                $role = Role::findOrFail($role_id);
                $user->assignRole($role->name);
            }

            if ($request->password) {
                $user->password = Hash::make($request['password']);
            }

            if ($request->filled('image_names')) {
                $user->image = $request->image_names;
                $directory = public_path('uploads/users');
                if (!\File::exists($directory)) {
                    \File::makeDirectory($directory, 0777, true);
                }

                $image = \File::move(public_path('uploads/temp/' . $request->image_names), public_path('uploads/users/'. $request->image_names));

            }

            $user->save();

            DB::commit();
            $output = [
                'success' => 1,
                'msg' => __('Updated successfully')
            ];
        } catch (Exception $e) {
            dd($e);
            DB::rollBack();
            $output = [
                'success' => 0,
                'msg' => __('Something went wrong')
            ];
        }

        return redirect()->route('admin.user.index')->with($output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('user.delete')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            DB::beginTransaction();
            $user = User::findOrFail($id);
            if ($user->image) {
                ImageManager::delete(public_path('uploads/users/' . $user->image));
            }
            $user->delete();
            $users = User::latest('id')->paginate(10);
            $view = view('backends.user._table', compact('users'))->render();

            DB::commit();
            $output = [
                'status' => 1,
                'view'  => $view,
                'msg' => __('User Deleted successfully')
            ];
        } catch (Exception $e) {
            DB::rollBack();

            $output = [
                'status' => 0,
                'msg' => __('Something when wrong')
            ];
        }

        return response()->json($output);
    }

    public function showProfile()
    {
        return view('backends.user.profile');
    }
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required',
            'phone' => 'required',
            // 'role' => 'required',
            'email' => 'required|email|unique:users,email,' . auth()->user()->id,
            'password' => 'nullable|min:8',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with(['success' => 0, 'msg' => __('Invalid form input')]);
        }

        try {
            DB::beginTransaction();
            // $users = User::where('id' != $id)->where('email', $request->email)->get();
            // if(count($users) > 0) {
            //     return redirect()->back()
            //         ->withErrors($validator)
            //         ->withInput()
            //         ->with(['success' => 0, 'msg' => __('Email already exists')]);
            // }
            $id = auth()->user()->id;
            $user = User::findOrFail($id);
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->name = $request->username;
            $user->phone = $request->phone;
            $user->telegram = $request->telegram ?? null;
            $user->email = $request->email;
            if ($request->password) {
                $user->password = Hash::make($request['password']);
            }

            if ($request->filled('image_names')) {
                $user->image = $request->image_names;
                $directory = public_path('uploads/users');
                if (!\File::exists($directory)) {
                    \File::makeDirectory($directory, 0777, true);
                }

                $image = \File::move(public_path('uploads/temp/' . $request->image_names), public_path('uploads/users/'. $request->image_names));

            }

            $user->save();

            DB::commit();
            $output = [
                'success' => 1,
                'msg' => __('Updated successfully')
            ];
        } catch (Exception $e) {
            dd($e);
            DB::rollBack();
            $output = [
                'success' => 0,
                'msg' => __('Something went wrong')
            ];
        }

        return redirect()->back()->with($output);
    }
}
