<?php

namespace App\Http\Controllers\Backends;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index()
    {
       $roles = Role::paginate(10);
       return view('backends.role.index',compact('roles'));
    }
    public function create()
    {
        return view('backends.role.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'permissions' => 'required'
        ]);

        $role_name      = $request->input('name');
        $permissions    = $request->input('permissions');

        $count = Role::where('name', $role_name)->count();
        if ($count == 0) {

            $role           = new Role;
            $role->name     = $role_name;
            $role->save();
            // return 2;
            $this->__createPermissionIfNotExists($permissions);

            if (!empty($permissions)) {
                $role->syncPermissions($permissions);
            }

            $output = [
                    'success' => 1,
                    'msg' => __("Created successfully")
                ];

        }else {
            $output = [
                    'success' => 0,
                        'msg' => __("Role already exists")
                    ];
        }

        return redirect()->route('admin.roles.index')->with($output);
    }

    public function edit($id)
    {
        $role = Role::with(['permissions'])->find($id);

        $role_permissions = [];
        foreach ($role->permissions as $role_perm) {
        $role_permissions[] = $role_perm->name;
        }
        return view('backends.role.edit',compact('role','role_permissions'));
    }

    public function update(Request $request,$id)
    {

        $this->validate($request,[
            'name' => 'required'
        ]);

        $role_name      = $request->input('name');
        $permissions    = $request->input('permissions');

        $count = Role::where('name', $role_name)->where('id', '!=', $id)->count();
        if ($count == 0) {
            $role           = Role::findOrFail($id);
            $role->name     = $role_name;
            $role->save();

            $this->__createPermissionIfNotExists($permissions);

            if (!empty($permissions)) {
                $role->syncPermissions($permissions);
            }

            $output = [
                    'success' => 1,
                    'msg' => __("Updated sucessfully")
                ];

        }else {
            $output = ['success' => 0,
                        'msg' => __("Role already exists")
                    ];
        }

        return redirect()->route('admin.roles.index')->with($output);
    }
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $role = Role::find($id);
            $role->delete();

            $roles = Role::paginate(10);
            $view = view('backends.role._table', compact('roles'))->render();

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
    private function __createPermissionIfNotExists($permissions)
    {

        $exising_permissions = Permission::whereIn('name', $permissions)
                                    ->pluck('name')
                                    ->toArray();

        $non_existing_permissions = array_diff($permissions, $exising_permissions);
        if (!empty($non_existing_permissions)) {

            foreach ($non_existing_permissions as $new_permission) {
                $time_stamp = Carbon::now()->toDateTimeString();
               Permission::create([
                    'name' => $new_permission,
                    'guard_name' => 'web'
                ]);

            }
        }
    }
}
